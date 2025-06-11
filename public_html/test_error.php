<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Testing IP Access Manager Route</h1>";

// Define constants
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', APP_PATH . '/config');
define('RESOURCE_PATH', APP_PATH . '/resources');

// Check if files exist
echo "<h2>File Checks:</h2>";
echo "Router exists: " . (file_exists(APP_PATH . '/src/Router.php') ? 'YES' : 'NO') . "<br>";
echo "Controller exists: " . (file_exists(APP_PATH . '/src/Controllers/Admin/IpAccessController.php') ? 'YES' : 'NO') . "<br>";
echo "Bootstrap exists: " . (file_exists(APP_PATH . '/src/bootstrap.php') ? 'YES' : 'NO') . "<br>";

// Try to load the router
try {
    require_once APP_PATH . '/vendor/autoload.php';
    require_once APP_PATH . '/src/bootstrap.php';
    
    echo "<h2>Router Test:</h2>";
    $router = new \App\Router();
    echo "Router created successfully!<br>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error:</h2>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
