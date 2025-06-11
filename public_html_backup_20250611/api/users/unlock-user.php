<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\AuthService;
use App\Models\User;

try {
    $authService = new AuthService();
    $authService->requireRole('super_admin');
    
    $userId = $_GET['id'] ?? 0;
    if (!$userId) {
        throw new Exception('User ID required', 400);
    }
    
    $userModel = new User();
    $userModel->unlockUser($userId);
    
    echo json_encode(['success' => true, 'message' => 'User unlocked successfully']);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
