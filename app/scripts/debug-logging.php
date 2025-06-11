<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

echo "Debugging Logger Paths\n";
echo "=====================\n\n";

echo "BASE_PATH: " . BASE_PATH . "\n";
echo "APP_PATH: " . APP_PATH . "\n";
echo "STORAGE_PATH: " . STORAGE_PATH . "\n";
echo "Log directory: " . STORAGE_PATH . '/logs/' . "\n\n";

echo "Checking if paths exist:\n";
echo "STORAGE_PATH exists: " . (is_dir(STORAGE_PATH) ? 'YES' : 'NO') . "\n";
echo "Logs dir exists: " . (is_dir(STORAGE_PATH . '/logs') ? 'YES' : 'NO') . "\n";
echo "Logs dir writable: " . (is_writable(STORAGE_PATH . '/logs') ? 'YES' : 'NO') . "\n\n";

// Try to create a test file directly
$testFile = STORAGE_PATH . '/logs/test.txt';
echo "Attempting to create test file: $testFile\n";
if (file_put_contents($testFile, "Test at " . date('Y-m-d H:i:s') . "\n") !== false) {
    echo "✓ Test file created successfully\n";
    echo "File exists: " . (file_exists($testFile) ? 'YES' : 'NO') . "\n";
    unlink($testFile);
} else {
    echo "✗ Failed to create test file\n";
    echo "Error: " . error_get_last()['message'] . "\n";
}

// Test Logger directly
echo "\nTesting Logger directly:\n";
use App\Services\Logger;

try {
    Logger::channel('app')->info('Direct test message');
    echo "✓ Logger called without errors\n";
} catch (Exception $e) {
    echo "✗ Logger error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

// Check for log files again
echo "\nChecking for log files:\n";
$logPath = STORAGE_PATH . '/logs/';
$files = scandir($logPath);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo "Found: $file\n";
    }
}
