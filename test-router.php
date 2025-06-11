<?php
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

$router = new \App\Core\Router();

// Test route registration
$router->get('/test', function() {
    echo "Test route works!";
});

// Check routes
echo "Routes registered: " . count($router->getRoutes()) . "\n";
