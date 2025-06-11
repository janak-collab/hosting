<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    // First check current status
    $stmt = $db->query("SELECT * FROM users WHERE username = 'jvidyarthi'");
    $user = $stmt->fetch();
    
    echo "Current status for jvidyarthi:\n";
    echo "  - ID: " . ($user['id'] ?? 'NOT FOUND') . "\n";
    echo "  - Role: " . ($user['role'] ?? 'NULL') . "\n";
    echo "  - Active: " . ($user['is_active'] ?? 'NULL') . "\n\n";
    
    // Update to super_admin
    $stmt = $db->prepare("UPDATE users SET role = 'super_admin', is_active = 1, full_name = 'J Vidyarthi' WHERE username = 'jvidyarthi'");
    $result = $stmt->execute();
    
    if ($result) {
        echo "✅ Updated jvidyarthi to super_admin\n\n";
    }
    
    // Verify the update
    $stmt = $db->query("SELECT * FROM users WHERE username = 'jvidyarthi'");
    $user = $stmt->fetch();
    
    echo "New status for jvidyarthi:\n";
    echo "  - ID: " . $user['id'] . "\n";
    echo "  - Username: " . $user['username'] . "\n";
    echo "  - Full Name: " . $user['full_name'] . "\n";
    echo "  - Role: " . $user['role'] . "\n";
    echo "  - Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
