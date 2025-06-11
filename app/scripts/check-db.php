<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    echo "Connected successfully!\n";
    
    // Check users table
    $stmt = $db->query("DESCRIBE users");
    echo "\nUsers table structure:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
    
    // Check existing users
    $stmt = $db->query("SELECT id, username, active, role FROM users");
    echo "\nExisting users:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "ID: {$row['id']}, Username: {$row['username']}, Active: {$row['active']}, Role: {$row['role']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
