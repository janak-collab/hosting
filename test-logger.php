<?php
require_once __DIR__ . '/app/vendor/autoload.php';
require_once __DIR__ . '/app/src/bootstrap.php';

use App\Services\Logger;

try {
    Logger::channel('app')->info('Test log entry from test-logger.php');
    echo "Logger test complete - check app/storage/logs/\n";
    
    // List log files
    $logDir = __DIR__ . '/app/storage/logs/';
    if (is_dir($logDir)) {
        echo "\nLog files found:\n";
        foreach (glob($logDir . '*.log') as $file) {
            echo "- " . basename($file) . " (" . filesize($file) . " bytes)\n";
        }
    }
} catch (Exception $e) {
    echo "Logger error: " . $e->getMessage() . "\n";
}
