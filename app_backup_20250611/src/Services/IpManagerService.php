<?php
namespace App\Services;

class IpManagerService {
    private $htaccessPath;
    private $backupDir;
    private $logFile;
    
    public function __construct() {
        $this->htaccessPath = '/home/gmpmus/public_html/.htaccess';
        $this->backupDir = '/home/gmpmus/htaccess_backups/';
        $this->logFile = '/home/gmpmus/logs/ip_changes.log';
    }
    
    public function getCurrentIPs() {
        $ips = [];
        if (file_exists($this->htaccessPath)) {
            $content = file_get_contents($this->htaccessPath);
            $lines = explode("\n", $content);
            $currentLocation = '';
            
            foreach ($lines as $lineNum => $line) {
                // Look for location comments
                if (preg_match('/^\s*#\s*(.+)/', $line, $commentMatch)) {
                    $comment = trim($commentMatch[1]);
                    
                    // Check if next line has RewriteCond with REMOTE_ADDR
                    if (isset($lines[$lineNum + 1]) && 
                        strpos($lines[$lineNum + 1], 'RewriteCond') !== false &&
                        strpos($lines[$lineNum + 1], 'REMOTE_ADDR') !== false) {
                        $currentLocation = $comment;
                    }
                }
                
                // Look for IP address line
                if (preg_match('/RewriteCond\s+%\{REMOTE_ADDR\}\s+!\^([0-9\\.\\\\]+)\$/', $line, $ipMatch)) {
                    if ($currentLocation) {
                        $ip = str_replace('\\', '', $ipMatch[1]);
                        $ips[] = ['ip' => $ip, 'location' => $currentLocation];
                        $currentLocation = '';
                    }
                }
            }
        }
        
        // Sort alphabetically by location (case-insensitive)
        usort($ips, function($a, $b) {
            return strcasecmp($a['location'], $b['location']);
        });
        
        return $ips;
    }
    
    public function updateHtaccess($ips) {
        // Sort IPs alphabetically by location before saving
        usort($ips, function($a, $b) {
            return strcasecmp($a['location'], $b['location']);
        });
        
        $this->backupHtaccess();
        $content = file_get_contents($this->htaccessPath);
        
        // Find the IP section and replace it
        $lines = explode("\n", $content);
        $newLines = [];
        $inIpSection = false;
        $ipSectionStart = -1;
        $ipSectionEnd = -1;
        
        // Find the IP section
        for ($i = 0; $i < count($lines); $i++) {
            if (strpos($lines[$i], '# IP whitelist entres') !== false) {
                $inIpSection = true;
                $ipSectionStart = $i;
            }
            
            if ($inIpSection && strpos($lines[$i], 'RewriteRule') !== false && strpos($lines[$i], '[F,L]') !== false) {
                $ipSectionEnd = $i;
                $inIpSection = false;
                break;
            }
        }
        
        // Build new content
        for ($i = 0; $i < count($lines); $i++) {
            if ($i === $ipSectionStart) {
                // Add the header comment
                $newLines[] = $lines[$i];
                
                // Add all IPs
                foreach ($ips as $ip) {
                    $escapedIP = str_replace('.', '\.', $ip['ip']);
                    $newLines[] = "    # " . $ip['location'];
                    $newLines[] = "    RewriteCond %{REMOTE_ADDR} !^" . $escapedIP . "$";
                }
                
                // Skip to the end of IP section
                $i = $ipSectionEnd - 1;
            } elseif ($i > $ipSectionStart && $i < $ipSectionEnd) {
                // Skip old IP lines
                continue;
            } else {
                $newLines[] = $lines[$i];
            }
        }
        
        $newContent = implode("\n", $newLines);
        file_put_contents($this->htaccessPath, $newContent);
        
        $this->logChange('Updated IP addresses');
    }
    
    public function validateIP($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return false;
        }
        $parts = explode('.', $ip);
        if (count($parts) !== 4) {
            return false;
        }
        foreach ($parts as $part) {
            if (!is_numeric($part) || $part < 0 || $part > 255) {
                return false;
            }
        }
        return true;
    }
    
    private function backupHtaccess() {
        if (!file_exists($this->backupDir)) {
            mkdir($this->backupDir, 0750, true);
        }
        $backupFile = $this->backupDir . 'htaccess_' . date('Y-m-d_H-i-s') . '.bak';
        copy($this->htaccessPath, $backupFile);
        
        // Keep only last 30 backups
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
    
    private function logChange($action) {
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0750, true);
        }
        $entry = sprintf(
            "[%s] %s - User: %s, IP: %s\n",
            date('Y-m-d H:i:s'),
            $action,
            $_SERVER['PHP_AUTH_USER'] ?? 'Unknown',
            $_SERVER['REMOTE_ADDR']
        );
        file_put_contents($this->logFile, $entry, FILE_APPEND | LOCK_EX);
    }
}
