<?php
session_start();
header('Content-Type: application/json');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\AuthService;
use App\Services\UserService;

try {
    $authService = new AuthService();
    $authService->requireRole('super_admin');
    
    $userId = $_GET['id'] ?? 0;
    if (!$userId) {
        throw new Exception('User ID required', 400);
    }
    
    $userService = new UserService();
    $userService->deleteUser($userId, $_SESSION['user_id'] ?? 1);
    
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
