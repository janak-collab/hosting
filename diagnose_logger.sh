#!/bin/bash
# GMPM Logger Diagnostic Script
# This script diagnoses why Logger isn't writing files

echo "===================================="
echo "GMPM Logger Diagnostic Script"
echo "===================================="
echo ""

# 1. Check if Monolog is installed
echo "1. Checking Monolog installation..."
if [ -f "app/vendor/monolog/monolog/src/Monolog/Logger.php" ]; then
    echo "✓ Monolog is installed"
    echo "  Version info:"
    grep -m1 "VERSION" app/vendor/monolog/monolog/src/Monolog/Logger.php 2>/dev/null || echo "  Version not found in file"
else
    echo "✗ Monolog NOT found!"
    echo "  Installing Monolog..."
    cd app/ && composer require monolog/monolog && cd ..
fi
echo ""

# 2. Check Logger.php file
echo "2. Checking Logger.php file..."
if [ -f "app/src/Services/Logger.php" ]; then
    echo "✓ Logger.php exists"
    # Check if it uses correct namespace
    if grep -q "namespace App\\\\Services;" app/src/Services/Logger.php; then
        echo "✓ Namespace is correct"
    else
        echo "✗ Namespace issue detected"
    fi
else
    echo "✗ Logger.php NOT found at app/src/Services/Logger.php"
fi
echo ""

# 3. Check storage directories
echo "3. Checking storage directories..."
DIRS=("app/storage" "app/storage/logs" "storage" "storage/logs")
for dir in "${DIRS[@]}"; do
    if [ -d "$dir" ]; then
        echo "✓ Directory exists: $dir"
        echo "  Permissions: $(ls -ld $dir | awk '{print $1}')"
        echo "  Owner: $(ls -ld $dir | awk '{print $3":"$4}')"
    else
        echo "✗ Directory missing: $dir"
    fi
done
echo ""

# 4. Check if STORAGE_PATH is defined
echo "4. Creating test script to check STORAGE_PATH..."
cat > public_html/test-storage-path.php << 'EOF'
<?php
// Test STORAGE_PATH definition
echo "<pre>";
echo "=== STORAGE_PATH Test ===\n\n";

// Check if bootstrap exists and load it
$bootstrapPath = __DIR__ . '/../app/src/bootstrap.php';
if (file_exists($bootstrapPath)) {
    echo "Loading bootstrap...\n";
    require_once $bootstrapPath;
    echo "Bootstrap loaded successfully\n\n";
} else {
    echo "Bootstrap not found at: $bootstrapPath\n\n";
}

// Check defined constants
echo "Checking constants:\n";
$constants = ['STORAGE_PATH', 'APP_PATH', 'BASE_PATH', 'ROOT_PATH'];
foreach ($constants as $const) {
    if (defined($const)) {
        echo "✓ $const = " . constant($const) . "\n";
        if ($const === 'STORAGE_PATH' && is_dir(constant($const))) {
            echo "  Directory exists: YES\n";
            echo "  Writable: " . (is_writable(constant($const)) ? 'YES' : 'NO') . "\n";
        }
    } else {
        echo "✗ $const is NOT defined\n";
    }
}

echo "</pre>";
?>
EOF
echo "Created: public_html/test-storage-path.php"
echo ""

# 5. Create a comprehensive Logger test
echo "5. Creating comprehensive Logger test..."
cat > public_html/test-logger-detailed.php << 'EOF'
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<pre>";
echo "=== Detailed Logger Test ===\n\n";

// Step 1: Load bootstrap
echo "1. Loading bootstrap...\n";
$bootstrapPath = __DIR__ . '/../app/src/bootstrap.php';
if (!file_exists($bootstrapPath)) {
    die("Bootstrap not found at: $bootstrapPath\n");
}
require_once $bootstrapPath;
echo "✓ Bootstrap loaded\n\n";

// Step 2: Check autoloader
echo "2. Checking autoloader...\n";
$autoloadPath = __DIR__ . '/../app/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    die("Autoloader not found at: $autoloadPath\n");
}
echo "✓ Autoloader exists\n\n";

// Step 3: Try to use Logger
echo "3. Testing Logger class...\n";
try {
    // Check if class exists
    if (!class_exists('App\Services\Logger')) {
        echo "✗ Logger class not found\n";
        echo "Attempting to include directly...\n";
        $loggerPath = __DIR__ . '/../app/src/Services/Logger.php';
        if (file_exists($loggerPath)) {
            require_once $loggerPath;
            echo "✓ Logger.php included\n";
        } else {
            die("Logger.php not found at: $loggerPath\n");
        }
    } else {
        echo "✓ Logger class found\n";
    }
    
    // Try to get logger instance
    echo "\n4. Getting logger instance...\n";
    $logger = \App\Services\Logger::channel('test');
    echo "✓ Logger instance created\n";
    
    // Try to write a log
    echo "\n5. Writing test log...\n";
    $logger->info('Test log entry', ['timestamp' => date('Y-m-d H:i:s')]);
    echo "✓ Log write attempted\n";
    
    // Check if log file was created
    echo "\n6. Checking for log file...\n";
    $possiblePaths = [
        STORAGE_PATH . '/logs/test.log',
        APP_PATH . '/storage/logs/test.log',
        dirname(__DIR__) . '/storage/logs/test.log',
        __DIR__ . '/../storage/logs/test.log'
    ];
    
    $found = false;
    foreach ($possiblePaths as $path) {
        echo "Checking: $path ... ";
        if (file_exists($path)) {
            echo "FOUND!\n";
            echo "File size: " . filesize($path) . " bytes\n";
            echo "Last modified: " . date('Y-m-d H:i:s', filemtime($path)) . "\n";
            echo "\nLast 5 lines:\n";
            $lines = file($path);
            $lastLines = array_slice($lines, -5);
            foreach ($lastLines as $line) {
                echo "  " . trim($line) . "\n";
            }
            $found = true;
            break;
        } else {
            echo "not found\n";
        }
    }
    
    if (!$found) {
        echo "\n✗ Log file not found in any expected location\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>
EOF
echo "Created: public_html/test-logger-detailed.php"
echo ""

# 6. Create a manual write test
echo "6. Creating manual write test..."
cat > public_html/test-manual-write.php << 'EOF'
<?php
echo "<pre>";
echo "=== Manual Write Test ===\n\n";

// Test writing to different locations
$testLocations = [
    __DIR__ . '/../app/storage/logs/manual-test.log',
    __DIR__ . '/../storage/logs/manual-test.log',
    __DIR__ . '/manual-test.log'
];

foreach ($testLocations as $location) {
    echo "Testing: $location\n";
    $dir = dirname($location);
    
    echo "  Directory: $dir\n";
    echo "  Exists: " . (is_dir($dir) ? 'YES' : 'NO') . "\n";
    echo "  Writable: " . (is_writable($dir) ? 'YES' : 'NO') . "\n";
    
    if (is_dir($dir) && is_writable($dir)) {
        $testContent = "[" . date('Y-m-d H:i:s') . "] Manual write test\n";
        $result = file_put_contents($location, $testContent, FILE_APPEND | LOCK_EX);
        if ($result !== false) {
            echo "  ✓ Write successful! Wrote $result bytes\n";
            echo "  File now exists: " . (file_exists($location) ? 'YES' : 'NO') . "\n";
        } else {
            echo "  ✗ Write failed\n";
        }
    }
    echo "\n";
}

echo "</pre>";
?>
EOF
echo "Created: public_html/test-manual-write.php"
echo ""

# 7. Fix permissions
echo "7. Fixing permissions..."
echo "Current user: $(whoami)"
echo "Current directory: $(pwd)"
echo ""

# Try to create and set permissions on storage directories
for dir in "app/storage" "app/storage/logs" "storage" "storage/logs"; do
    if [ ! -d "$dir" ]; then
        echo "Creating $dir..."
        mkdir -p "$dir" 2>/dev/null && echo "✓ Created $dir" || echo "✗ Failed to create $dir"
    fi
    
    if [ -d "$dir" ]; then
        echo "Setting permissions on $dir..."
        chmod 775 "$dir" 2>/dev/null && echo "✓ Set 775 on $dir" || echo "✗ Failed to chmod $dir"
    fi
done
echo ""

# 8. Create a minimal Logger if missing
if [ ! -f "app/src/Services/Logger.php" ]; then
    echo "8. Creating minimal Logger.php..."
    mkdir -p app/src/Services
    cat > app/src/Services/Logger.php << 'EOFLOGGER'
<?php
namespace App\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Logger {
    private static $instances = [];
    
    public static function channel($channel = 'app') {
        if (!isset(self::$instances[$channel])) {
            $logger = new MonologLogger($channel);
            
            // Determine log path - try multiple locations
            $logPath = null;
            $possiblePaths = [
                (defined('STORAGE_PATH') ? STORAGE_PATH : null) . '/logs',
                __DIR__ . '/../../storage/logs',
                dirname(dirname(dirname(__DIR__))) . '/storage/logs',
                dirname(dirname(dirname(__DIR__))) . '/app/storage/logs'
            ];
            
            foreach ($possiblePaths as $path) {
                if ($path && is_dir($path) && is_writable($path)) {
                    $logPath = $path;
                    break;
                }
            }
            
            if (!$logPath) {
                // Create a fallback directory
                $fallback = dirname(dirname(__DIR__)) . '/storage/logs';
                if (!is_dir($fallback)) {
                    @mkdir($fallback, 0775, true);
                }
                $logPath = $fallback;
            }
            
            $logFile = $logPath . '/' . $channel . '.log';
            
            try {
                $handler = new StreamHandler($logFile, MonologLogger::DEBUG);
                $handler->setFormatter(new LineFormatter(
                    "[%datetime%] %channel%.%level_name%: %message% %context%\n",
                    'Y-m-d H:i:s'
                ));
                $logger->pushHandler($handler);
            } catch (\Exception $e) {
                // If all else fails, log to error_log
                error_log("Logger failed to initialize: " . $e->getMessage());
            }
            
            self::$instances[$channel] = $logger;
        }
        
        return self::$instances[$channel];
    }
    
    // Convenience methods
    public static function info($message, $context = []) {
        return self::channel('app')->info($message, $context);
    }
    
    public static function error($message, $context = []) {
        return self::channel('app')->error($message, $context);
    }
    
    public static function warning($message, $context = []) {
        return self::channel('app')->warning($message, $context);
    }
    
    public static function debug($message, $context = []) {
        return self::channel('app')->debug($message, $context);
    }
}
EOFLOGGER
    echo "✓ Created app/src/Services/Logger.php"
else
    echo "8. Logger.php already exists"
fi
echo ""

echo "===================================="
echo "Diagnostic Complete!"
echo "===================================="
echo ""
echo "Test these URLs:"
echo "1. https://gmpm.us/test-storage-path.php"
echo "2. https://gmpm.us/test-logger-detailed.php"
echo "3. https://gmpm.us/test-manual-write.php"
echo ""
echo "Then check for log files:"
echo "  find . -name '*.log' -type f -mtime -1"
echo ""
