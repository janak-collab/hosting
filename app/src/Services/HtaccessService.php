<?php
namespace App\Services;

class HtaccessService {
    private $htaccessPath;
    private $backupDir;
    
    public function __construct() {
        $this->htaccessPath = '/home/gmpmus/public_html/.htaccess';
        $this->backupDir = '/home/gmpmus/htaccess_backups/';
    }
    
    /**
     * Get current IPs from .htaccess
     */
    public function getCurrentIPs() {
        $ips = [];
        
        if (!file_exists($this->htaccessPath)) {
            throw new \Exception('.htaccess file not found');
        }
        
        $content = file_get_contents($this->htaccessPath);
        
        // Look for the LiteSpeed module section
        if (preg_match('/<IfModule LiteSpeed>(.*?)<\/IfModule>/s', $content, $moduleMatch)) {
            $moduleContent = $moduleMatch[1];
            $lines = explode("\n", $moduleContent);
            $currentLocation = '';
            
            foreach ($lines as $line) {
                // Check for location comments
                if (preg_match('/^\s*#\s*(.+?)\s*(?:Office|Home)?$/', $line, $commentMatch)) {
                    $location = trim($commentMatch[1]);
                    // Skip certain comments
                    if (!in_array($location, ['Block all IPs except allowed ones', 'Force rewrite rules to process first', 'Server\'s own IP for curl tests', 'Allowed IPs'])) {
                        $currentLocation = $location;
                    }
                }
                
                // Check for IP rules
                if (preg_match('/RewriteCond\s+%\{REMOTE_ADDR\}\s+!\^([0-9\.\\\\]+)\$/', $line, $ipMatch)) {
                    if ($currentLocation) {
                        $ip = str_replace('\\', '', $ipMatch[1]);
                        $ips[] = [
                            'ip' => $ip,
                            'location' => $currentLocation
                        ];
                        $currentLocation = '';
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
     * Update IPs in .htaccess
     */
    public function updateIPs($ips) {
        // Create backup first
        $this->backupHtaccess();
        
        // Read current content
        $content = file_get_contents($this->htaccessPath);
        
        // Pattern to match the IP blocking section
        $pattern = '/(<IfModule LiteSpeed>.*?# Allowed IPs\s*\n)(.*?)(# Server\'s own IP.*?RewriteRule.*?\[F,L\]\s*\n)/s';
        
        if (!preg_match($pattern, $content, $matches)) {
            throw new \Exception('Could not find LiteSpeed IP blocking section in .htaccess');
        }
        
        // Sort IPs alphabetically by location
        usort($ips, function($a, $b) {
            return strcasecmp($a['location'], $b['location']);
        });
        
        // Build new IP conditions
        $newConditions = $matches[1];
        
        foreach ($ips as $ipData) {
            $escapedIP = str_replace('.', '\\.', $ipData['ip']);
            $newConditions .= "    # " . $ipData['location'] . "\n";
            $newConditions .= "    RewriteCond %{REMOTE_ADDR} !^" . $escapedIP . "$\n";
        }
        
        $newConditions .= "    " . $matches[3];
        
        // Replace the section
        $newContent = preg_replace($pattern, $newConditions, $content);
        
        // Write back to file
        if (file_put_contents($this->htaccessPath, $newContent) === false) {
            throw new \Exception('Failed to write .htaccess file');
        }
        
        return true;
    }
    
    /**
     * Create backup of .htaccess
     */
    private function backupHtaccess() {
        if (!file_exists($this->backupDir)) {
            mkdir($this->backupDir, 0750, true);
        }
        
        $backupFile = $this->backupDir . 'htaccess_' . date('Y-m-d_H-i-s') . '.bak';
        
        if (!copy($this->htaccessPath, $backupFile)) {
            throw new \Exception('Failed to create backup');
        }
        
        // Clean old backups (keep last 30)
        $backups = glob($this->backupDir . '*.bak');
        if (count($backups) > 30) {
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            for ($i = 0; $i < count($backups) - 30; $i++) {
                unlink($backups[$i]);
            }
        }
    }
}
