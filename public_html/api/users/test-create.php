<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

// Basic auth check
if (!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== 'jvidyarthi') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\UserService;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed', 405);
    }
    
    // Add test data
    $testData = [
        'username' => 'testapi' . time(),
        'password' => 'TestApi123!',
        'full_name' => 'Test API User',
        'email' => 'test' . time() . '@api.com',
        'role' => 'user'
    ];
    
    $userService = new UserService();
    $result = $userService->createUser($testData, 1); // Use admin user ID
    
    echo json_encode(['success' => true, 'data' => $result]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
