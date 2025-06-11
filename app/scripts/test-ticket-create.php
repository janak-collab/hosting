<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Models\ITTicket;

// Start session
session_start();
$_SESSION['csrf_token'] = 'test_token';

// Simulate being logged in
$_SERVER['PHP_AUTH_USER'] = 'testuser';

try {
    echo "Creating test ticket...\n";
    
    $model = new ITTicket();
    $id = $model->create([
        'name' => 'Test User',
        'location' => 'Leonardtown',
        'description' => 'Test ticket from script',
        'category' => 'other',
        'priority' => 'normal'
    ]);
    
    if ($id) {
        echo "✓ Success! Created ticket ID: $id\n";
    } else {
        echo "✗ Failed to create ticket\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
