<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Models\User;

try {
    $username = $_GET['username'] ?? '';
    
    if (empty($username)) {
        throw new Exception('Username required', 400);
    }
    
    $userModel = new User();
    $existing = $userModel->getByUsername($username);
    
    echo json_encode([
        'success' => true,
        'available' => !$existing
    ]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
