<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Manual setup
define('APP_PATH', '/home/gmpmus/app');
define('ROOT_PATH', dirname(APP_PATH));
define('CONFIG_PATH', APP_PATH . '/config');

// Simple autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = APP_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
    echo "Trying to load: $file<br>";
    if (file_exists($file)) {
        require_once $file;
        echo "Loaded: $class<br>";
    }
});

// Start session
session_start();
$_SESSION['admin_logged_in'] = true; // Fake login for testing

try {
    echo "<h1>Direct Controller Test</h1>";
    
    // Try to instantiate the controller
    $controller = new \App\Controllers\Admin\IpAccessController();
    echo "<p style='color:green'>Controller instantiated successfully!</p>";
    
    // Try to call the index method
    $controller->index();
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error:</h2>";
    echo "<pre>";
    echo $e->getMessage() . "\n";
    echo $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
    echo "</pre>";
}
?>
