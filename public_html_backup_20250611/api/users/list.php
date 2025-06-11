<?php
session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Models\User;

try {
    // Basic auth check
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        throw new Exception('Authentication required', 401);
    }
    
    $userModel = new User();
    $users = $userModel->getAllUsers();
    
    echo json_encode(['success' => true, 'data' => $users]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
