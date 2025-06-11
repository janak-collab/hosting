<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    echo "=== All IT Support Tickets ===\n\n";
    
    $stmt = $db->query("SELECT * FROM it_support_tickets ORDER BY created_at 
DESC");
    $tickets = $stmt->fetchAll();
    
    echo "Total tickets: " . count($tickets) . "\n\n";
    
    foreach ($tickets as $ticket) {
        echo "ID: {$ticket['id']}\n";
        echo "Name: {$ticket['name']}\n";
        echo "Status: {$ticket['status']}\n";
        echo "Created: {$ticket['created_at']}\n";
        echo "---\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
