<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/status';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

echo "=== Testing Router ===\n";

$router = new \App\Core\Router();
echo "Router created\n";

// Load routes
$webRoutes = require '/home/gmpmus/app/routes/web.php';
$webRoutes($router);
echo "Routes loaded\n";

// Try to dispatch
try {
    echo "Dispatching GET /status\n";
    $result = $router->dispatch('GET', '/status');
    echo "Dispatch result: ";
    var_dump($result);
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
    echo "In file: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}
