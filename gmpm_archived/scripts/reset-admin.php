<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/bootstrap.php';

use App\Database\Connection;

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $db = Connection::getInstance()->getConnection();
    
    // Update the password
    $stmt = $db->prepare("UPDATE users SET password_hash = :hash, active = 1 WHERE username = :username");
    $result = $stmt->execute([
        ':hash' => $hash,
        ':username' => $username
    ]);
    
    echo "Password reset for user '$username'\n";
    echo "Result: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    echo "New password: $password\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
