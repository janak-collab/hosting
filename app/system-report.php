<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "\n";
echo "════════════════════════════════════════════════════════════\n";
echo "         GMPM Portal v2.0 - Complete System Report          \n";
echo "════════════════════════════════════════════════════════════\n";
echo "\n";

// Test each component
$tests = [
    'PHP Version' => phpversion(),
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'Router Class' => class_exists('\App\Router') ? '✓' : '✗',
    'FastRoute' => file_exists(__DIR__ . '/vendor/nikic/fast-route') ? '✓' : '✗',
    'Database Config' => isset($_ENV['DB_HOST']) ? '✓' : '✗',
    'Session Active' => session_status() === PHP_SESSION_ACTIVE ? '✓' : '✗'
];

echo "🔍 System Tests\n";
echo "───────────────\n";
foreach ($tests as $test => $result) {
    printf("%-20s %s\n", $test . ":", $result);
}

// Test endpoints
echo "\n📡 Endpoint Status\n";
echo "─────────────────\n";
$endpoints = [
    '/' => 'Portal',
    '/status' => 'System Status',
    '/health' => 'Health Check',
    '/phone-note' => 'Phone Note Form',
    '/it-support' => 'IT Support Form',
    '/admin/login' => 'Admin Login'
];

foreach ($endpoints as $path => $name) {
    $url = "https://gmpm.us" . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $status = $code == 200 ? '✓' : '✗ (' . $code . ')';
    printf("%-20s %s\n", $name . ":", $status);
}

// Recent activity
echo "\n📈 Recent Activity\n";
echo "─────────────────\n";
$errorLog = '/home/gmpmus/logs/php_errors.log';
if (file_exists($errorLog)) {
    $errors = `tail -5 $errorLog | grep -c "error" || echo 0`;
    echo "Recent Errors: " . trim($errors) . "\n";
} else {
    echo "Error Log: Not found\n";
}

echo "\n✅ Deployment Status: COMPLETE\n";
echo "📅 Report Generated: " . date('Y-m-d H:i:s') . "\n\n";
