<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== GMPM System Test ===\n\n";

// Define constants
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public_html');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', APP_PATH . '/config');
define('RESOURCE_PATH', APP_PATH . '/resources');

require_once APP_PATH . '/vendor/autoload.php';

// Test 1: Check autoloading
echo "1. Testing autoloading:\n";
$classes = [
    'App\Router',
    'App\Controllers\BaseController',
    'App\Controllers\AdminController',
    'App\Controllers\PortalController',
    'App\Controllers\ITSupportController',
    'App\Controllers\PhoneNoteController',
    'App\Middleware\Auth',
    'App\Middleware\RateLimit'
];

foreach ($classes as $class) {
    echo "   $class: " . (class_exists($class) ? "✓" : "✗") . "\n";
}

// Test 2: Check configuration files
echo "\n2. Testing configuration files:\n";
$configs = ['app', 'database', 'security', 'providers', 'email'];
foreach ($configs as $config) {
    $file = CONFIG_PATH . "/$config.php";
    echo "   $config.php: " . (file_exists($file) ? "✓" : "✗") . "\n";
}

// Test 3: Check views
echo "\n3. Testing view directories:\n";
$viewDirs = [
    'errors' => RESOURCE_PATH . '/views/errors',
    'portal' => RESOURCE_PATH . '/views/portal',
    'admin' => RESOURCE_PATH . '/views/admin'
];
foreach ($viewDirs as $name => $dir) {
    echo "   $name: " . (is_dir($dir) ? "✓" : "✗") . "\n";
}

// Test 4: Test Router instantiation
echo "\n4. Testing Router:\n";
try {
    $router = new App\Router();
    echo "   Router instantiation: ✓\n";
    
    // Test route data
    $routeData = $router->getRoutes();
    if (is_array($routeData)) {
        echo "   Routes loaded: ✓\n";
    }
} catch (Exception $e) {
    echo "   Router error: " . $e->getMessage() . "\n";
}

// Test 5: Database connection
echo "\n5. Testing database connection:\n";
try {
    if (class_exists('App\Database\Connection')) {
        $db = App\Database\Connection::getInstance();
        echo "   Database connection: ✓\n";
    } else {
        echo "   Database connection class not found\n";
    }
} catch (Exception $e) {
    echo "   Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Test Complete ===\n";
