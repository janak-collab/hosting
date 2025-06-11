<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "Route Debugging\n";
echo "===============\n\n";

try {
    $router = new \App\Router();
    $dispatcher = $router->getDispatcher();
    
    // Get raw route data
    $data = $dispatcher->getData();
    
    echo "Static Routes:\n";
    if (isset($data[0])) {
        foreach ($data[0] as $method => $routes) {
            foreach ($routes as $route => $handler) {
                echo sprintf("  %s %s => %s\n", $method, $route, 
                    is_array($handler) ? $handler[0] : $handler);
            }
        }
    }
    
    echo "\nVariable Routes:\n";
    if (isset($data[1])) {
        foreach ($data[1] as $method => $routes) {
            foreach ($routes as $route) {
                echo sprintf("  %s %s => %s\n", $method, 
                    $route['regex'] ?? 'regex', 
                    is_array($route['handler']) ? $route['handler'][0] : $route['handler']);
            }
        }
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
