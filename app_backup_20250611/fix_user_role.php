<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    // Update jvidyarthi to super_admin
    $stmt = $db->prepare("UPDATE users SET role = 'super_admin' WHERE username = 'jvidyarthi'");
    $stmt->execute();
    
    // Verify
    $stmt = $db->query("SELECT * FROM users WHERE username = 'jvidyarthi'");
    $user = $stmt->fetch();
    
    echo "User 'jvidyarthi' role updated to: " . $user['role'] . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
