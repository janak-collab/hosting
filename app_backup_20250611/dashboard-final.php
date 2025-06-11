<?php
require_once __DIR__ . '/vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║           GMPM Portal v2.0 - System Dashboard                ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

// System Information
echo "📊 System Information\n";
echo "────────────────────\n";
$info = [
    'PHP Version' => phpversion(),
    'Server Time' => date('Y-m-d H:i:s T'),
    'Server IP' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
    'Document Root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'
];

foreach ($info as $label => $value) {
    printf("%-20s %s\n", $label . ":", $value);
}

// Component Status
echo "\n🔧 Component Status\n";
echo "───────────────────\n";
$components = [
    'Composer Autoload' => file_exists(__DIR__ . '/vendor/autoload.php'),
    'FastRoute Router' => file_exists(__DIR__ . '/vendor/nikic/fast-route'),
    'PHPMailer' => class_exists('\PHPMailer\PHPMailer\PHPMailer'),
    'Dotenv' => class_exists('\Dotenv\Dotenv'),
    'Environment File' => file_exists(__DIR__ . '/.env'),
    'Database Config' => !empty($_ENV['DB_HOST']),
    'Email Config' => !empty($_ENV['MAIL_HOST'])
];

foreach ($components as $name => $status) {
    printf("%-20s %s\n", $name . ":", $status ? '✓ Available' : '✗ Missing');
}

// Route Summary
echo "\n🛣️  Route Summary\n";
echo "────────────────\n";
$routeCounts = [
    'Public Routes' => 6,
    'Admin Routes' => 8,
    'API Routes' => 3,
    'Total Routes' => 17
];

foreach ($routeCounts as $type => $count) {
    printf("%-20s %d\n", $type . ":", $count);
}

// Directory Structure
echo "\n📁 Directory Check\n";
echo "─────────────────\n";
$dirs = [
    'App' => realpath(__DIR__),
    'Public' => realpath(__DIR__ . '/../public_html'),
    'Storage' => realpath(__DIR__ . '/../storage'),
    'Logs' => realpath(__DIR__ . '/../logs'),
    'Controllers' => realpath(__DIR__ . '/src/Controllers'),
    'Models' => realpath(__DIR__ . '/src/Models'),
    'Templates' => realpath(__DIR__ . '/templates')
];

foreach ($dirs as $name => $path) {
    $exists = $path && is_dir($path);
    printf("%-15s %s\n", $name . ":", $exists ? '✓ ' . basename($path) : '✗ Not found');
}

// Recent Activity
echo "\n📈 Recent Activity\n";
echo "─────────────────\n";
$accessLog = $_SERVER['DOCUMENT_ROOT'] . '/access.log';
if (file_exists($accessLog)) {
    $recentHits = intval(`tail -100 $accessLog | wc -l`);
    echo "Recent Requests: $recentHits (last 100 lines)\n";
} else {
    echo "Access Log: Not available\n";
}

// Status Summary
echo "\n✅ Deployment Summary\n";
echo "───────────────────\n";
$ready = 0;
$total = count($components);
foreach ($components as $status) {
    if ($status) $ready++;
}

$percentage = round(($ready / $total) * 100);
echo "Components Ready: $ready/$total ($percentage%)\n";
echo "System Status: " . ($percentage >= 80 ? "OPERATIONAL" : "NEEDS ATTENTION") . "\n";
echo "\n🌐 Portal URL: https://gmpm.us/\n";
echo "📊 Status API: https://gmpm.us/status\n";
echo "🏥 Health Check: https://gmpm.us/health\n\n";
