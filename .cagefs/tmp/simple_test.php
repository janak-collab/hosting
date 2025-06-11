<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting test...\n";

try {
    require_once '/home/gmpmus/app/vendor/autoload.php';
    echo "Autoload loaded\n";
    
    require_once '/home/gmpmus/app/src/bootstrap.php';
    echo "Bootstrap loaded\n";
    
    $userService = new App\Services\UserService();
    echo "UserService created\n";
    
    // Test password validation
    $result = $userService->validatePassword('ValidPass123!@#');
    echo "Password validation result: " . ($result ? 'VALID' : 'INVALID') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}
