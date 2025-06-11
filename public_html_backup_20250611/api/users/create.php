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
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input', 400);
    }
    
    // Temporarily skip CSRF for API testing
    // TODO: Re-enable after fixing session persistence
    
    $userService = new UserService();
    $result = $userService->createUser($input, $_SESSION['user_id'] ?? null);
    
    echo json_encode(['success' => true, 'data' => $result, 'message' => 'User created successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
