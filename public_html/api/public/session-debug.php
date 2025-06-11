<?php
session_start();
header('Content-Type: application/json');

// Set a test value if not exists
if (!isset($_SESSION['test_time'])) {
    $_SESSION['test_time'] = time();
}

// Generate CSRF if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

echo json_encode([
    'session_id' => session_id(),
    'session_name' => session_name(),
    'cookie_params' => session_get_cookie_params(),
    'session_data' => $_SESSION,
    'cookies' => $_COOKIE,
    'auth_user' => $_SERVER['PHP_AUTH_USER'] ?? 'none'
]);
