<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║          GMPM Portal System Dashboard                      ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n";
echo "\n";

// System Info
echo "📊 System Information\n";
echo "─────────────────────\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "Environment: " . ($_ENV['APP_ENV'] ?? 'production') . "\n";
echo "\n";

// Component Status
echo "✅ Component Status\n";
echo "──────────────────\n";
$components = [
    'Router' => file_exists(__DIR__ . '/src/Router.php'),
    'Controllers' => is_dir(__DIR__ . '/src/Controllers'),
    'Middleware' => is_dir(__DIR__ . '/src/Middleware'),
    'Views' => is_dir(__DIR__ . '/resources/views'),
    'FastRoute' => file_exists(__DIR__ . '/vendor/nikic/fast-route')
];

foreach ($components as $name => $exists) {
    printf("%-15s %s\n", $name . ":", $exists ? "✓ Installed" : "✗ Missing");
}
echo "\n";

// Route Statistics
echo "🛣️  Route Statistics\n";
echo "───────────────────\n";
try {
    $router = new \App\Router();
    $data = $router->getDispatcher()->getData();
    
    $staticCount = 0;
    $dynamicCount = 0;
    
    if (isset($data[0])) {
        foreach ($data[0] as $method => $routes) {
            $staticCount += count($routes);
        }
    }
    
    if (isset($data[1])) {
        foreach ($data[1] as $method => $routes) {
            $dynamicCount += count($routes);
        }
    }
    
    echo "Static Routes:  $staticCount\n";
    echo "Dynamic Routes: $dynamicCount\n";
    echo "Total Routes:   " . ($staticCount + $dynamicCount) . "\n";
} catch (Exception $e) {
    echo "Error loading routes: " . $e->getMessage() . "\n";
}

echo "\n";
echo "🔗 Quick Links\n";
echo "─────────────\n";
echo "Portal:      https://gmpm.us/\n";
echo "Status:      https://gmpm.us/status\n";
echo "Phone Note:  https://gmpm.us/phone-note\n";
echo "IT Support:  https://gmpm.us/it-support\n";
echo "Admin:       https://gmpm.us/admin/login\n";
echo "\n";
