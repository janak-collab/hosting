<?php
// Define paths
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

require_once APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/src/bootstrap.php';

use App\Controllers\UserManagementController;
use App\Services\AuthService;

// Check authentication
$authService = new AuthService();
if (!$authService->requireRole('super_admin')) {
    header('Location: /');
    exit;
}

$controller = new UserManagementController();

// Get the action from the URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
$parts = explode('/', $uri);

// Route to appropriate method
if (count($parts) === 2 && $parts[0] === 'admin' && $parts[1] === 'users') {
    // List users
    $controller->index();
} elseif (count($parts) === 3 && $parts[0] === 'admin' && $parts[1] === 'users') {
    $action = $parts[2];
    if ($action === 'create') {
        $controller->create();
    } elseif (is_numeric($action)) {
        // Edit user
        $controller->edit($action);
    }
} else {
    // Invalid route
    header('Location: /admin/users');
    exit;
}
