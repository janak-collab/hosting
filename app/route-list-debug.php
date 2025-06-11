<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "Starting route list debug...\n";

try {
    $router = new \App\Router();
    echo "✓ Router created\n";
    
    $dispatcher = $router->getDispatcher();
    echo "✓ Dispatcher obtained\n";
    
    $data = $dispatcher->getData();
    echo "✓ Route data retrieved\n";
    
    echo "\nData structure:\n";
    echo "Static routes (data[0]): " . (isset($data[0]) ? count($data[0]) . " methods" : "none") . "\n";
    echo "Dynamic routes (data[1]): " . (isset($data[1]) ? count($data[1]) . " methods" : "none") . "\n";
    
    if (!empty($data[0]) || !empty($data[1])) {
        echo "\nGMPM Application Routes\n";
        echo "======================\n\n";
        echo sprintf("%-8s %-40s %-40s\n", "METHOD", "URI", "ACTION");
        echo str_repeat("-", 90) . "\n";
        
        // Process routes...
        if (isset($data[0])) {
            foreach ($data[0] as $method => $routes) {
                foreach ($routes as $route => $handler) {
                    $action = is_array($handler) ? $handler[0] : $handler;
                    echo sprintf("%-8s %-40s %-40s\n", $method, $route, $action);
                }
            }
        }
    } else {
        echo "\nNo routes found. Router might not be initialized properly.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
