<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    echo "Creating IT support tickets table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS it_support_tickets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        location VARCHAR(50) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(20) DEFAULT 'general',
        priority VARCHAR(20) DEFAULT 'normal',
        status VARCHAR(20) DEFAULT 'open',
        ip_address VARCHAR(45) NULL,
        created_by VARCHAR(100) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        resolved_at TIMESTAMP NULL,
        INDEX idx_status (status),
        INDEX idx_priority (priority),
        INDEX idx_created_at (created_at),
        INDEX idx_created_by (created_by)
    )";
    
    $db->exec($sql);
    echo "✓ IT support tickets table created successfully!\n";
    
    // Also create the comments table
    echo "\nCreating IT ticket comments table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS it_ticket_comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        comment TEXT NOT NULL,
        is_internal BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ticket_id (ticket_id)
    )";
    
    $db->exec($sql);
    echo "✓ IT ticket comments table created successfully!\n";
    
    // Check if tables were created
    echo "\nVerifying tables...\n";
    $stmt = $db->query("SHOW TABLES LIKE '%ticket%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        echo "- Found table: $table\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
