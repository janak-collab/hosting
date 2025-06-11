<?php
session_start();
header('Content-Type: application/json');

// Check if session works
$_SESSION['test'] = 'value';

echo json_encode([
    'session_id' => session_id(),
    'session_status' => session_status(),
    'session_data' => $_SESSION,
    'auth_user' => $_SERVER['PHP_AUTH_USER'] ?? 'none'
]);
