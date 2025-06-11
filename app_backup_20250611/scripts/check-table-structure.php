<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    // Check table structure
    echo "=== IT Support Tickets Table Structure ===\n";
    $stmt = $db->query("DESCRIBE it_support_tickets");
    $columns = $stmt->fetchAll();
    
    foreach ($columns as $col) {
        echo sprintf("%-20s %-20s %s\n", $col['Field'], $col['Type'], $col['Null']);
    }
    
    // Check if created_by exists
    $hasCreatedBy = false;
    foreach ($columns as $col) {
        if ($col['Field'] === 'created_by') {
            $hasCreatedBy = true;
            echo "\n✓ created_by column EXISTS\n";
            break;
        }
    }
    
    if (!$hasCreatedBy) {
        echo "\n✗ created_by column NOT FOUND\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
