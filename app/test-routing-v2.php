<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

// Start session for testing
session_start();

echo "GMPM Routing System Test\n";
echo "========================\n\n";

try {
    $router = new \App\Router();
    echo "✓ Router initialized\n";
    
    // Test FastRoute directly
    $dispatcher = $router->getDispatcher();
    echo "✓ FastRoute dispatcher created\n\n";
    
    // Test routes
    $tests = [
        ['GET', '/', 'Portal homepage'],
        ['GET', '/status', 'Status check'],
        ['GET', '/health', 'Health check'],
        ['GET', '/phone-note', 'Phone note form'],
        ['GET', '/it-support', 'IT support form'],
        ['GET', '/admin/login', 'Admin login'],
        ['GET', '/admin/tickets', 'Admin tickets'],
        ['GET', '/api/test', 'API test'],
        ['GET', '/nonexistent', 'Should be 404']
    ];
    
    echo "Route Tests:\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($tests as $test) {
        [$method, $uri, $description] = $test;
        
        $routeInfo = $dispatcher->dispatch($method, $uri);
        
        $status = match($routeInfo[0]) {
            FastRoute\Dispatcher::NOT_FOUND => '404',
            FastRoute\Dispatcher::METHOD_NOT_ALLOWED => '405',
            FastRoute\Dispatcher::FOUND => '200',
            default => '???'
        };
        
        $handler = $routeInfo[0] === FastRoute\Dispatcher::FOUND 
            ? $routeInfo[1] 
            : 'Not found';
            
        printf("%-6s %-20s => %s (%s)\n", 
            $method, 
            $uri, 
            $status,
            is_array($handler) ? $handler[0] : $handler
        );
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
