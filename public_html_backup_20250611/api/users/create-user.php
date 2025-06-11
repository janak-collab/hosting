<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

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
    if (!$input) {
        throw new Exception('Invalid JSON input', 400);
    }
    
    // Validate required fields
    $required = ['username', 'password', 'full_name', 'email'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required", 400);
        }
    }
    
    // Extract password from input
    $password = $input['password'];
    unset($input['password']); // Remove password from data array
    
    // Set default role if not provided
    if (!isset($input['role'])) {
        $input['role'] = 'user';
    }
    
    $userService = new UserService();
    // Call createUser with correct parameters: data, password, createdBy
    $result = $userService->createUser($input, $password, $_SESSION['user_id'] ?? 1);
    
    echo json_encode(['success' => true, 'data' => $result, 'message' => 'User created successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
