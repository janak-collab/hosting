<?php

namespace App\Models;

use App\Core\Database;
use App\Services\Logger;

class IpAccessModel
{
    protected $db;
    protected $table = 'ip_access_rules';
    
    // Configuration
    const HTACCESS_PATH = '/home/gmpmus/public_html/.htaccess';
    const BACKUP_DIR = '/home/gmpmus/htaccess_backups/';
    const CONFIG_PATH = CONFIG_PATH . '/security.php';
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all IP rules from database
     */
    public function getAllRules()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY location ASC, created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get active IP rules
     */
    public function getActiveRules()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY location ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get IPs from .htaccess file (legacy support)
     */
    public function getIPsFromHtaccess()
    {
        $ips = [];
        if (file_exists(self::HTACCESS_PATH)) {
            $content = file_get_contents(self::HTACCESS_PATH);
            
            // Find all LiteSpeed modules
            preg_match_all('/<IfModule LiteSpeed>(.*?)<\/IfModule>/s', $content, $matches);
            
            // Look for the one containing REMOTE_ADDR
            $moduleContent = '';
            foreach ($matches[1] as $match) {
                if (strpos($match, 'REMOTE_ADDR') !== false) {
                    $moduleContent = $match;
                    break;
                }
            }
            
            if (!empty($moduleContent)) {
                $lines = explode("\n", $moduleContent);
                $currentLocation = '';
                foreach ($lines as $line) {
                    // Check for location comments
                    if (preg_match('/^\s*#\s*(.+?)\s*(?:Office)?$/', $line, $commentMatch)) {
                        $location = trim($commentMatch[1]);
                        if (!in_array($location, ['Block all IPs except allowed ones', 'Force rewrite rules to process first', 
                            'IP whitelist entries (with location comments)', 'Server\'s own IP for curl tests'])) {
                            $currentLocation = $location;
                        }
                    }
                    // Check for IP lines
                    if (preg_match('/RewriteCond\s+%\{REMOTE_ADDR\}\s+!\^([0-9\.\\\\]+)\$/', $line, $ipMatch)) {
                        $ip = str_replace('\\', '', $ipMatch[1]);
                        if ($currentLocation) {
                            $ips[] = [
                                'ip_address' => $ip, 
                                'location' => $currentLocation,
                                'description' => $currentLocation,
                                'access_type' => 'allow',
                                'is_active' => 1
                            ];
                            $currentLocation = '';
                        } else {
                            $ips[] = [
                                'ip_address' => $ip, 
                                'location' => $ip,
                                'description' => $ip,
                                'access_type' => 'allow',
                                'is_active' => 1
                            ];
                        }
                    }
                }
            }
        }
        
        // Sort alphabetically by location
        usort($ips, function($a, $b) {
            return strcasecmp($a['location'], $b['location']);
        });
        
        return $ips;
    }
    
    /**
     * Add new IP rule
     */
    public function addRule($data)
    {
        $sql = "INSERT INTO {$this->table} 
                (ip_address, location, description, access_type, is_active, created_by, created_at) 
                VALUES 
                (:ip_address, :location, :description, :access_type, :is_active, :created_by, NOW())";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'ip_address' => $data['ip_address'],
            'location' => $data['location'] ?? $data['description'],
            'description' => $data['description'],
            'access_type' => $data['access_type'] ?? 'allow',
            'is_active' => $data['is_active'] ?? 1,
            'created_by' => $data['created_by'] ?? $_SESSION['admin_id'] ?? 0
        ]);
        
        if ($result) {
            $this->syncToHtaccess();
            $this->syncToConfig();
            Logger::channel('security')->info('IP rule added', [
                'ip' => $data['ip_address'],
                'location' => $data['location'] ?? $data['description']
            ]);
        }
        
        return $result;
    }
    
    /**
     * Update IP rule
     */
    public function updateRule($id, $data)
    {
        $sql = "UPDATE {$this->table} 
                SET ip_address = :ip_address,
                    location = :location,
                    description = :description,
                    access_type = :access_type,
                    is_active = :is_active,
                    updated_by = :updated_by,
                    updated_at = NOW()
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'id' => $id,
            'ip_address' => $data['ip_address'],
            'location' => $data['location'] ?? $data['description'],
            'description' => $data['description'],
            'access_type' => $data['access_type'] ?? 'allow',
            'is_active' => $data['is_active'] ?? 1,
            'updated_by' => $data['updated_by'] ?? $_SESSION['admin_id'] ?? 0
        ]);
        
        if ($result) {
            $this->syncToHtaccess();
            $this->syncToConfig();
            Logger::channel('security')->info('IP rule updated', ['id' => $id]);
        }
        
        return $result;
    }
    
    /**
     * Delete IP rule
     */
    public function deleteRule($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['id' => $id]);
        
        if ($result) {
            $this->syncToHtaccess();
            $this->syncToConfig();
            Logger::channel('security')->info('IP rule deleted', ['id' => $id]);
        }
        
        return $result;
    }
    
    /**
     * Deactivate all rules (for bulk update)
     */
    public function deactivateAllRules()
    {
        $sql = "UPDATE {$this->table} SET is_active = 0";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }
    
    /**
     * Sync database IPs to .htaccess for LiteSpeed
     */
    public function syncToHtaccess()
    {
        $this->backupHtaccess();
        
        $activeRules = $this->getActiveRules();
        $allowedIPs = array_filter($activeRules, function($rule) {
            return $rule['access_type'] === 'allow';
        });
        
        if (empty($allowedIPs)) {
            Logger::channel('security')->warning('No allowed IPs found - htaccess not updated');
            return false;
        }
        
        $content = file_get_contents(self::HTACCESS_PATH);
        
        // Find the LiteSpeed module with REMOTE_ADDR
        preg_match_all('/<IfModule LiteSpeed>(.*?)<\/IfModule>/s', $content, $matches, PREG_OFFSET_CAPTURE);
        
        $targetModuleIndex = -1;
        foreach ($matches[0] as $index => $match) {
            if (strpos($match[0], 'REMOTE_ADDR') !== false) {
                $targetModuleIndex = $index;
                break;
            }
        }
        
        if ($targetModuleIndex === -1) {
            Logger::channel('security')->error('Could not find LiteSpeed module with IP rules');
            return false;
        }
        
        $moduleStart = $matches[0][$targetModuleIndex][1];
        $moduleEnd = $moduleStart + strlen($matches[0][$targetModuleIndex][0]);
        $moduleContent = $matches[1][$targetModuleIndex][0];
        
        // Parse the module to preserve non-IP content
        if (preg_match('/(.*?)(#.*?IP.*?\n)?(.*?RewriteCond.*?REMOTE_ADDR.*?)(RewriteRule.*?\[F,L\].*?)$/s', $moduleContent, $parts)) {
            $beforeIPs = $parts[1];
            $afterIPs = $parts[4];
            
            // Build new IP section
            $newIpSection = "    # IP whitelist entries (with location comments)\n";
            foreach ($allowedIPs as $rule) {
                $escapedIP = str_replace('.', '\.', $rule['ip_address']);
                $location = $rule['location'] ?: $rule['description'];
                $newIpSection .= "    # " . $location . "\n";
                $newIpSection .= "    RewriteCond %{REMOTE_ADDR} !^" . $escapedIP . "$\n";
            }
            
            // Reconstruct the module
            $newModuleContent = $beforeIPs . $newIpSection . "    " . trim($afterIPs);
            
            // Replace in the original content
            $newContent = substr($content, 0, $moduleStart) .
                          "<IfModule LiteSpeed>\n" . $newModuleContent . "\n</IfModule>" .
                          substr($content, $moduleEnd);
            
            file_put_contents(self::HTACCESS_PATH, $newContent);
            Logger::channel('security')->info('Synced IPs to htaccess', ['count' => count($allowedIPs)]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Sync database IPs to config file for middleware
     */
    public function syncToConfig()
    {
        $activeRules = $this->getActiveRules();
        $configData = [];
        
        foreach ($activeRules as $rule) {
            if ($rule['access_type'] === 'allow') {
                $configData[$rule['ip_address']] = $rule['location'] ?: $rule['description'];
            }
        }
        
        // Load existing config
        $config = require self::CONFIG_PATH;
        $config['ip_whitelist'] = $configData;
        
        // Write back to config
        $configContent = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        file_put_contents(self::CONFIG_PATH, $configContent);
        
        Logger::channel('security')->info('Synced IPs to config', ['count' => count($configData)]);
        return true;
    }
    
    /**
     * Import IPs from htaccess to database (one-time migration)
     */
    public function importFromHtaccess()
    {
        $htaccessIPs = $this->getIPsFromHtaccess();
        $imported = 0;
        
        foreach ($htaccessIPs as $ipData) {
            // Check if IP already exists
            $sql = "SELECT id FROM {$this->table} WHERE ip_address = :ip";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['ip' => $ipData['ip_address']]);
            
            if (!$stmt->fetch()) {
                $this->addRule($ipData);
                $imported++;
            }
        }
        
        Logger::channel('security')->info('Imported IPs from htaccess', ['count' => $imported]);
        return $imported;
    }
    
    /**
     * Backup .htaccess file
     */
    private function backupHtaccess()
    {
        if (!file_exists(self::BACKUP_DIR)) {
            mkdir(self::BACKUP_DIR, 0750, true);
        }
        
        $backupFile = self::BACKUP_DIR . 'htaccess_' . date('Y-m-d_H-i-s') . '.bak';
        copy(self::HTACCESS_PATH, $backupFile);
        
        // Keep only last 30 backups
        $backups = glob(self::BACKUP_DIR . '*.bak');
        if (count($backups) > 30) {
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            for ($i = 0; $i < count($backups) - 30; $i++) {
                unlink($backups[$i]);
            }
        }
    }
    
    /**
     * Check if IP is allowed (for middleware use)
     */
    public function isIpAllowed($ip)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} 
                WHERE ip_address = :ip 
                AND access_type = 'allow' 
                AND is_active = 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['ip' => $ip]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Validate IP address
     */
    public function validateIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
}
