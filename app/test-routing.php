<?php
require_once __DIR__ . '/vendor/autoload.php';

// Test router initialization
try {
    $router = new \App\Router();
    echo "✓ Router initialized successfully\n";
    
    // Test route resolution
    $tests = [
        ['GET', '/'],
        ['GET', '/status'],
        ['GET', '/phone-note'],
        ['GET', '/it-support'],
        ['GET', '/admin/login'],
        ['GET', '/admin/tickets']
    ];
    
    echo "\nTesting route resolution:\n";
    foreach ($tests as $test) {
        [$method, $uri] = $test;
        $result = $router->dispatch($method, $uri);
        echo sprintf("%-6s %-20s => %s\n", 
            $method, 
            $uri, 
            is_array($result) ? json_encode($result) : 'Not found'
        );
    }
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}
