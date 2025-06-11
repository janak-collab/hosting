#!/bin/bash

echo "==================================="
echo "Testing GMPM Logging System"
echo "==================================="

# Create a test PHP file
cat > public_html/test-logging.php << 'EOF'
<?php
// Test logging functionality
require_once __DIR__ . '/../app/src/bootstrap.php';

use App\Services\Logger;

echo "<h2>GMPM Logging Test</h2>\n";
echo "<pre>\n";

try {
    // Test different log levels
    echo "Testing Logger...\n\n";
    
    // Info log
    Logger::info('Test info message', ['test' => 'data']);
    echo "✓ Info log written\n";
    
    // Warning log
    Logger::warning('Test warning message', ['level' => 'warning']);
    echo "✓ Warning log written\n";
    
    // Error log
    Logger::error('Test error message', ['error_code' => 500]);
    echo "✓ Error log written\n";
    
    // Debug log (only if debug mode is on)
    Logger::debug('Test debug message', ['debug' => true]);
    echo "✓ Debug log written (if debug mode is on)\n";
    
    // Access log
    Logger::access('Test page accessed', [
        'url' => $_SERVER['REQUEST_URI'],
        'method' => $_SERVER['REQUEST_METHOD']
    ]);
    echo "✓ Access log written\n";
    
    // Security log
    Logger::security('Test security event', [
        'event' => 'test',
        'ip' => $_SERVER['REMOTE_ADDR']
    ]);
    echo "✓ Security log written\n";
    
    // SQL log (only in debug mode)
    Logger::sql('SELECT * FROM test WHERE id = ?', [123], 0.0023);
    echo "✓ SQL log written (if debug mode is on)\n";
    
    echo "\n<strong>All logging tests completed!</strong>\n\n";
    
    // Check if log files exist
    $logPath = __DIR__ . '/../app/storage/logs/';
    echo "Checking log files:\n";
    
    $logFiles = [
        'app.log' => 'Application log',
        'access.log' => 'Access log',
        'error.log' => 'Error log',
        'security.log' => 'Security log'
    ];
    
    foreach ($logFiles as $file => $description) {
        if (file_exists($logPath . $file)) {
            $size = filesize($logPath . $file);
            echo "✓ $description exists (Size: $size bytes)\n";
            
            // Show last few lines
            $lines = file($logPath . $file);
            $lastLines = array_slice($lines, -3);
            if (!empty($lastLines)) {
                echo "  Last entries:\n";
                foreach ($lastLines as $line) {
                    echo "  " . trim($line) . "\n";
                }
            }
        } else {
            echo "✗ $description not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "Error during testing: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>\n";

// Clean up link
echo '<br><a href="/">Back to Portal</a>';
EOF

echo "✓ Test file created"

# Make it accessible via web
echo ""
echo "Visit https://gmpm.us/test-logging.php to test the logging system"
echo ""
echo "After testing, you can:"
echo "1. View logs: tail -f app/storage/logs/*.log"
echo "2. Remove test file: rm public_html/test-logging.php"
echo ""

# Also create a command-line test
cat > test-logging-cli.php << 'EOF'
<?php
// Command-line logging test
require_once __DIR__ . '/app/src/bootstrap.php';

use App\Services\Logger;

echo "Testing logging from CLI...\n";

Logger::info('CLI test message', ['source' => 'command-line']);
Logger::error('CLI error test', ['test' => true]);

echo "Logs written. Check app/storage/logs/\n";
EOF

echo "You can also test from command line:"
echo "  php test-logging-cli.php"
