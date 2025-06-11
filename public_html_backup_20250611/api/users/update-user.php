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
    
    $userId = $_GET['id'] ?? 0;
    if (!$userId) {
        throw new Exception('User ID required', 400);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new Exception('Invalid JSON input', 400);
    }
    
    // Extract password if provided
    $password = null;
    if (!empty($input['password'])) {
        $password = $input['password'];
        unset($input['password']);
    }
    
    $userService = new UserService();
    $result = $userService->updateUser($userId, $input, $password, $_SESSION['user_id'] ?? 1);
    
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
