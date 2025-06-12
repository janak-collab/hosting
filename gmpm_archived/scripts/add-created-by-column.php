<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    echo "Adding created_by column to it_support_tickets table...\n";
    
    $sql = "ALTER TABLE it_support_tickets ADD COLUMN created_by VARCHAR(100) AFTER ip_address";
    $db->exec($sql);
    
    echo "✓ Column added successfully!\n";
    
    // Add index for better performance
    echo "Adding index on created_by column...\n";
    $sql = "ALTER TABLE it_support_tickets ADD INDEX idx_created_by (created_by)";
    $db->exec($sql);
    
    echo "✓ Index added successfully!\n";
    
    // Verify the column was added
    $stmt = $db->query("DESCRIBE it_support_tickets");
    $columns = $stmt->fetchAll();
    
    echo "\nUpdated table structure:\n";
    foreach ($columns as $col) {
        echo "  - {$col['Field']} ({$col['Type']})\n";
    }
    
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column 'created_by' already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
