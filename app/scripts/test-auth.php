<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/bootstrap.php';

use App\Services\AuthService;
use App\Database\Connection;

// Test direct password verification
$username = 'admin';
$password = 'admin123';

echo "Testing authentication for: $username\n";

try {
    $db = Connection::getInstance()->getConnection();
    
    // Get the user
    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "User found: ID={$user['id']}, Username={$user['username']}\n";
        echo "Password hash: " . substr($user['password_hash'], 0, 20) . "...\n";
        
        // Test password verification
        $verifyResult = password_verify($password, $user['password_hash']);
        echo "Password verify result: " . ($verifyResult ? "SUCCESS" : "FAILED") . "\n";
        
        // Test with AuthService
        $authService = new AuthService();
        $authResult = $authService->authenticate($username, $password);
        echo "AuthService result: " . ($authResult ? "SUCCESS" : "FAILED") . "\n";
        
        echo "\nSession data after auth:\n";
        print_r($_SESSION);
    } else {
        echo "User not found!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
