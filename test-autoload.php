<?php
// Test autoloading
require_once 'app/vendor/autoload.php';
require_once 'app/src/bootstrap.php';

echo "Testing autoload...\n\n";

// Test if classes exist
$classes = [
    '\App\Core\Router',
    '\App\Services\Logger',
    '\App\Controllers\BaseController',
    '\App\Middleware\Auth',
    '\App\Services\ValidationService'
];

foreach ($classes as $class) {
    echo sprintf("%-40s %s\n", $class . ":", class_exists($class) ? "✓ Found" : "✗ Not found");
}

// Test helper functions
echo "\nTesting helper functions...\n";
$functions = ['url', 'asset', 'config', 'env', 'view'];

foreach ($functions as $func) {
    echo sprintf("%-20s %s\n", $func . "():", function_exists($func) ? "✓ Found" : "✗ Not found");
}

// Test Logger
echo "\nTesting Logger...\n";
try {
    \App\Services\Logger::channel('test')->info('Test message from CLI');
    echo "✓ Logger write successful\n";
    
    // Check if log file was created
    $logFile = 'app/storage/logs/test.log';
    if (file_exists($logFile)) {
        echo "✓ Log file created\n";
        echo "Last log entry: " . trim(shell_exec("tail -1 $logFile")) . "\n";
    }
} catch (Exception $e) {
    echo "✗ Logger error: " . $e->getMessage() . "\n";
}
