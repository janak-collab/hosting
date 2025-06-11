<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Controllers\ITSupportController;
use App\Models\ITTicket;

try {
    echo "Testing IT ticket creation...\n";
    
    $model = new ITTicket();
    $testData = [
        'name' => 'Test User',
        'location' => 'Test Location',
        'description' => 'Test ticket from script',
        'category' => 'hardware',
        'priority' => 'normal'
    ];
    
    $id = $model->create($testData);
    echo "Success! Created ticket ID: $id\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
