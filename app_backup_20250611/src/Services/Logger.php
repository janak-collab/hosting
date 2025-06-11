<?php
namespace App\Services;

class Logger {
    private static $channels = [];
    private $channel;
    private $logPath;
    
    private function __construct($channel = 'app') {
        $this->channel = $channel;
        $this->logPath = STORAGE_PATH . '/logs/' . $channel . '.log';
        
        // Ensure log directory exists
        $logDir = dirname($this->logPath);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }
    
    public static function channel($channel = 'app') {
        if (!isset(self::$channels[$channel])) {
            self::$channels[$channel] = new self($channel);
        }
        return self::$channels[$channel];
    }
    
    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }
    
    public function debug($message, $context = []) {
        if (env('APP_DEBUG', false)) {
            $this->log('DEBUG', $message, $context);
        }
    }
    
    private function log($level, $message, $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextJson = !empty($context) ? ' ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] {$level}: {$message}{$contextJson}\n";
        
        file_put_contents($this->logPath, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    // Static convenience methods
    public static function logError($message, $context = []) {
        self::channel('app')->error($message, $context);
    }
    
    public static function logInfo($message, $context = []) {
        self::channel('app')->info($message, $context);
    }
}
