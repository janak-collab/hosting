<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

$username = 'admin';
$password = 'admin123';  // Change this!
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $db = Connection::getInstance()->getConnection();
    
    // Delete existing admin
    $db->exec("DELETE FROM users WHERE username = '$username'");
    
    // Insert new admin
    $stmt = $db->prepare("INSERT INTO users (username, password_hash, role, active) VALUES (?, ?, 'admin', 1)");
    $stmt->execute([$username, $hash]);
    
    echo "Admin user created successfully!\n";
    echo "Username: $username\n";
    echo "Password: $password\n";
    echo "Hash: $hash\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
