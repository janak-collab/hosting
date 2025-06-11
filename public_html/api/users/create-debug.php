<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

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
    if (!$input) {
        throw new Exception('Invalid JSON input', 400);
    }
    
    // Debug: Check what we received
    error_log("DEBUG: Received data: " . json_encode($input));
    
    // Validate required fields
    $required = ['username', 'password', 'full_name', 'email'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required", 400);
        }
    }
    
    // Debug: Test password validation directly
    $userService = new UserService();
    $passwordValid = $userService->validatePassword($input['password']);
    error_log("DEBUG: Password validation result: " . ($passwordValid ? 'VALID' : 'INVALID'));
    
    if (!$passwordValid) {
        throw new Exception('Password validation failed at endpoint level', 400);
    }
    
    // Try to create user
    $result = $userService->createUser($input, $_SESSION['user_id'] ?? 1);
    
    echo json_encode(['success' => true, 'data' => $result]);
    
} catch (Exception $e) {
    error_log("DEBUG: Exception: " . $e->getMessage());
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
