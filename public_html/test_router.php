<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define all constants BEFORE including anything
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('CONFIG_PATH', APP_PATH . '/config');
define('RESOURCE_PATH', APP_PATH . '/resources');

try {
    // Include files
    require_once APP_PATH . '/vendor/autoload.php';
    require_once APP_PATH . '/src/bootstrap.php';
    
    echo "Bootstrap loaded OK<br>";
    
    // Try to create router
    $router = new \App\Router();
    echo "Router created OK<br>";
    
    // Try to dispatch
    $router->dispatch('GET', '/test');
    
} catch (Exception $e) {
    echo "<h2>Error:</h2>";
    echo $e->getMessage() . "<br>";
    echo $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
