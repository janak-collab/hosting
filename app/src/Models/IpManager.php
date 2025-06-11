<?php
namespace App\Models;

class IpManager {
    private $logFile;
    
    public function __construct() {
        $this->logFile = '/home/gmpmus/logs/ip_changes.log';
    }
    
    /**
     * Log IP changes
     */
    public function logChange($action, $user, $ip, $data = []) {
        $logDir = dirname($this->logFile);
        if (!file_exists($logDir)) {
            mkdir($logDir, 0750, true);
        }
        
        $entry = sprintf(
            "[%s] %s - User: %s, IP: %s",
            date('Y-m-d H:i:s'),
            $action,
            $user,
            $ip
        );
        
        if (!empty($data)) {
            $entry .= " - Data: " . json_encode($data);
        }
        
        $entry .= "\n";
        
        return file_put_contents($this->logFile, $entry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get recent log entries
     */
    public function getRecentLogs($limit = 50) {
        if (!file_exists($this->logFile)) {
            return [];
        }
        
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $lines = array_reverse($lines);
        
        return array_slice($lines, 0, $limit);
    }
}
