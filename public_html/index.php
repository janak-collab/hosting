<?php
/**
 * GMPM Application Entry Point - Full Featured Version with FastRoute
    case $requestUri === '/dictation':
        require APP_PATH . '/public-endpoints/dictation.php';
        break;
        
 */

// Define paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Security check
if (file_exists(__DIR__ . '/.env')) {
    die('Security Error: .env file should not be in public directory!');
}

// Check vendor directory
if (!file_exists(APP_PATH . '/vendor/autoload.php')) {
    die('Error: Vendor directory not found. Please run composer install.');
}

// Autoload
require_once APP_PATH . '/vendor/autoload.php';

// Bootstrap application
require_once APP_PATH . '/src/bootstrap.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize the FastRoute-based router
try {
    $router = new \App\Router();
    
    // Get request method and URI
    $method = $_SERVER['REQUEST_METHOD'];
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    // Log the request for debugging
    error_log("Routing request: $method $uri");
    
    // Dispatch request
    $router->dispatch($method, $uri);
    
} catch (Exception $e) {
    // Log error
    error_log("Application error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    
    // Show 500 error
    http_response_code(500);
    if (file_exists(__DIR__ . '/errors/500.html')) {
        include __DIR__ . '/errors/500.html';
    } else {
        echo "500 - Internal Server Error";
    }
}
