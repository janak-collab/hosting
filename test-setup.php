<?php
// Test Setup Script
echo "=== GMPM Setup Test ===\n\n";

// Check paths
echo "Checking directory structure...\n";
$paths = [
    'app' => is_dir('app'),
    'app/config' => is_dir('app/config'),
    'app/src' => is_dir('app/src'),
    'app/resources' => is_dir('app/resources'),
    'app/routes' => is_dir('app/routes'),
    'app/storage' => is_dir('app/storage'),
    'public_html' => is_dir('public_html'),
    'public_html/assets' => is_dir('public_html/assets')
];

foreach ($paths as $path => $exists) {
    echo sprintf("  %-25s %s\n", $path . ":", $exists ? "✓" : "✗");
}

// Check key files
echo "\nChecking key files...\n";
$files = [
    'app/.env' => file_exists('app/.env'),
    'app/composer.json' => file_exists('app/composer.json'),
    'app/src/bootstrap.php' => file_exists('app/src/bootstrap.php'),
    'app/src/Core/Router.php' => file_exists('app/src/Core/Router.php'),
    'app/routes/web.php' => file_exists('app/routes/web.php'),
    'app/routes/api.php' => file_exists('app/routes/api.php')
];

foreach ($files as $file => $exists) {
    echo sprintf("  %-25s %s\n", $file . ":", $exists ? "✓" : "✗");
}

// Check PHP version and extensions
echo "\nPHP Environment...\n";
echo "  PHP Version: " . phpversion() . "\n";
echo "  PDO MySQL: " . (extension_loaded('pdo_mysql') ? "✓" : "✗") . "\n";
echo "  Session: " . (extension_loaded('session') ? "✓" : "✗") . "\n";
echo "  JSON: " . (extension_loaded('json') ? "✓" : "✗") . "\n";

echo "\n=== End of Test ===\n";
