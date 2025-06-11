<?php
// API endpoint for audit logging
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Log to database (simplified version - in production, use the model)
try {
    require_once __DIR__ . '/../../../app/vendor/autoload.php';
    require_once __DIR__ . '/../../../app/src/bootstrap.php';
    
    $dictationModel = new \App\Models\Dictation();
    $dictationModel->logAccess(
        $input['action'],
        $input['procedureId'] ?? null,
        $input['procedureName'] ?? null
    );
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Logging failed']);
}
