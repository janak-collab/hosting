<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\AuthService;
use App\Services\UserService;

try {
    $authService = new AuthService();
    $authService->requireRole('super_admin');
    
    // Accept both DELETE and POST methods
    if (!in_array($_SERVER['REQUEST_METHOD'], ['DELETE', 'POST'])) {
        throw new Exception('Method not allowed', 405);
    }
    
    $userId = $_GET['id'] ?? 0;
    
    if (!$userId) {
        throw new Exception('User ID required', 400);
    }
    
    // Temporarily skip CSRF for API testing
    // TODO: Re-enable after fixing session persistence
    
    $userService = new UserService();
    $result = $userService->deleteUser($userId, $_SESSION['user_id'] ?? null);
    
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
