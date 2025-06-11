#!/bin/bash
# Diagnose Router Issues

echo "==================================="
echo "Diagnosing Router Implementation"
echo "==================================="
echo ""

# Check PHP CLI
echo "Testing PHP CLI execution..."
cd public_html
php_output=$(php -r 'echo "PHP CLI works\n";' 2>&1)
echo "$php_output"

# Test index.php directly
echo ""
echo "Testing index.php directly..."
php_test=$(php -r 'ini_set("display_errors", 1); error_reporting(E_ALL); $_SERVER["REQUEST_METHOD"] = "GET"; $_SERVER["REQUEST_URI"] = "/"; 
$_SERVER["REMOTE_ADDR"] = "127.0.0.1"; require "index.php";' 2>&1)
if [ $? -eq 0 ]; then
    echo "✓ No fatal errors"
else
    echo "✗ PHP errors found:"
    echo "$php_test" | head -n 20
fi

# Check autoload
echo ""
echo "Checking composer autoload..."
if [ -f "../app/vendor/autoload.php" ]; then
    echo "✓ Autoload file exists"
    php -r 'require "../app/vendor/autoload.php"; echo "✓ Autoload works\n";' 2>&1
else
    echo "✗ Autoload file missing"
fi

# Check namespaces
echo ""
echo "Checking if classes can be loaded..."
cd ..
php -r '
require "app/vendor/autoload.php";
$classes = [
    "App\Core\Router",
    "App\Controllers\BaseController",
    "App\Controllers\PortalController",
    "App\Services\Logger"
];
foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✓ $class\n";
    } else {
        echo "✗ $class not found\n";
    }
}
' 2>&1

# Check recent errors
echo ""
echo "Recent PHP errors in error_log:"
if [ -f "public_html/error_log" ]; then
    tail -n 20 public_html/error_log | grep -E "(PHP|Fatal|Warning|Notice)" | tail -n 10
else
    echo "No error_log file"
fi

# Test a simple PHP file
echo ""
echo "Creating test.php to verify basic PHP works..."
cat > public_html/test.php << 'EOF'
<?php
echo "PHP is working!\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n";
EOF

echo ""
echo "Test with: https://gmpm.us/test.php"
echo ""
echo "==================================="
echo "Diagnosis complete!"
echo "====================================="
