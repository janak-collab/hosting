<?php
// app/scripts/test-logging.php
// Run this to test if logging is working properly

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Services\Logger;

echo "Testing GMPM Logger Service\n";
echo "==========================\n\n";

// Test different log levels
echo "1. Testing app channel...\n";
Logger::channel('app')->debug('This is a debug message');
Logger::channel('app')->info('This is an info message');
Logger::channel('app')->warning('This is a warning message');
Logger::channel('app')->error('This is an error message');
echo "   ✓ App channel tested\n\n";

// Test access logging
echo "2. Testing access logging...\n";
Logger::logAccess('Test access log', [
    'action' => 'test_script',
    'timestamp' => date('Y-m-d H:i:s')
]);
echo "   ✓ Access log tested\n\n";

// Test security logging
echo "3. Testing security logging...\n";
Logger::logSecurity('Test security event', [
    'event_type' => 'test',
    'severity' => 'low'
]);
echo "   ✓ Security log tested\n\n";

// Test SQL logging (only logs if APP_DEBUG=true or LOG_SQL=true)
echo "4. Testing SQL logging...\n";
Logger::logSql('SELECT * FROM users WHERE id = ?', [1], 0.5);
echo "   ✓ SQL log tested (check if APP_DEBUG=true)\n\n";

// Test mail logging
echo "5. Testing mail logging...\n";
Logger::logMail('test@example.com', 'Test Subject', 'sent', [
    'mailer' => 'PHPMailer'
]);
echo "   ✓ Mail log tested\n\n";

// Test exception logging
echo "6. Testing exception logging...\n";
try {
    throw new Exception('This is a test exception');
} catch (Exception $e) {
    Logger::logException($e, ['context' => 'test script']);
}
echo "   ✓ Exception log tested\n\n";

// Check if log files were created
echo "7. Checking log files...\n";
$logPath = STORAGE_PATH . '/logs/';
$today = date('Y-m-d');
$logFiles = [
    "app-{$today}.log",
    "access-{$today}.log",
    "security-{$today}.log",
    "mail-{$today}.log",
    "error-{$today}.log"
];

foreach ($logFiles as $file) {
    if (file_exists($logPath . $file)) {
        $size = filesize($logPath . $file);
        echo "   ✓ {$file} exists (size: {$size} bytes)\n";
        
        // Show last line from each log
        $lastLine = trim(shell_exec("tail -n 1 " . escapeshellarg($logPath . $file)));
        if ($lastLine) {
            echo "     Last entry: " . substr($lastLine, 0, 80) . "...\n";
        }
    } else {
        echo "   ✗ {$file} not found\n";
    }
}

// Also show all log files
echo "\nAll log files in directory:\n";
$files = glob($logPath . '*.log');
foreach ($files as $file) {
    echo "   - " . basename($file) . " (" . filesize($file) . " bytes)\n";
}
