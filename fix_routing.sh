#!/bin/bash

echo "=== Fixing index.php routing issues ==="
echo

cd ~/public_html

echo "1. Creating backup..."
cp index.php index.php.broken.$(date +%Y%m%d_%H%M%S)

echo
echo "2. Creating fixed index.php..."
cat > index.php << 'EOF'
<?php
// GMPM Application Entry Point
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Security check - ensure critical files aren't accessible
if (file_exists(__DIR__ . '/.env')) {
    die('Security Error: .env file should not be in public directory!');
}

// Check if vendor exists in new location
if (!file_exists(APP_PATH . '/vendor/autoload.php')) {
    die('Error: Vendor directory not found. Please check installation.');
}

// Autoload
require_once APP_PATH . '/vendor/autoload.php';

// Load environment variables
if (file_exists(APP_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(APP_PATH);
    $dotenv->load();
}

// Start session for all requests
session_start();

// Basic routing
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = rtrim($requestUri, '/');

// Log access for security monitoring
error_log("Access: " . $_SERVER['REMOTE_ADDR'] . " - " . $requestUri);

// Route the request
switch (true) {
    case $requestUri === '' || $requestUri === '/index.php':
        // Show portal page instead of redirecting
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>GMPM Portal</title>
            <link rel="stylesheet" href="/assets/css/form-styles.css">
        </head>
        <body>
            <div class="container">
                <div class="form-card">
                    <h1>Greater Maryland Pain Management Portal</h1>
                    <div class="form-content">
                        <p>Welcome, <?php echo $_SERVER['PHP_AUTH_USER'] ?? 'User'; ?>!</p>
                        <div style="display: grid; gap: 1rem; margin-top: 2rem;">
                            <a href="/phone-note" class="btn btn-primary">📞 Phone Note Form</a>
                            <a href="/it-support" class="btn btn-secondary">💻 IT Support Request</a>
                            <a href="/dictation" class="btn btn-secondary">📝 Medical Dictation</a>
                            <a href="/view-tickets" class="btn btn-secondary">📋 View Tickets</a>
                            <a href="/admin" class="btn btn-secondary">🔧 Admin Area</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
        break;
    
    case $requestUri === '/phone-note':
        require APP_PATH . '/public-endpoints/phone-note.php';
        break;
        
    case $requestUri === '/it-support':
        require APP_PATH . '/public-endpoints/it-support.php';
        break;
        
    case $requestUri === '/dictation':
        require APP_PATH . '/public-endpoints/dictation.php';
        break;
        
    case $requestUri === '/view-tickets':
        require APP_PATH . '/public-endpoints/view-tickets.php';
        break;
    
    case $requestUri === '/test123':
        require APP_PATH . '/public-endpoints/test123.php';
        break;
    
    case strpos($requestUri, '/admin') === 0:
        // Add authentication check here
        $adminController = APP_PATH . '/src/Controllers/AdminController.php';
        if (file_exists($adminController)) {
            require $adminController;
        } else {
            http_response_code(404);
            echo "Admin area not implemented yet";
        }
        break;
    
    case $requestUri === '/api/dashboard/stats':
        require APP_PATH . '/src/Controllers/DashboardController.php';
        $controller = new \App\Controllers\DashboardController();
        $controller->getStats();
        break;
    
    case strpos($requestUri, '/api') === 0:
        // API routes stay in public for now
        $apiPath = str_replace('/api', '', $requestUri);
        $apiFile = PUBLIC_PATH . '/api' . $apiPath . '.php';
        if (file_exists($apiFile)) {
            require $apiFile;
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
        }
        break;
        
    case strpos($requestUri, '/assets') === 0:
        // Let server handle static files
        return false;
        break;
    
    case $requestUri === '/forms/work-note':
        require APP_PATH . '/templates/views/forms/work-note.php';
        break;

    case $requestUri === '/forms/new-patient':
        require APP_PATH . '/templates/views/forms/patient/new-patient.php';
        break;
    
    // Provider management routes (admin only)
    case strpos($requestUri, '/admin/providers') === 0:
        require_once APP_PATH . '/src/Controllers/ProviderManagementController.php';
        $controller = new \App\Controllers\ProviderManagementController();

        $parts = explode('/', trim($requestUri, '/'));

        switch($parts[2] ?? '') {
            case '':
                $controller->index();
                break;
            case 'create':
                $controller->create();
                break;
            case 'deactivate':
                $controller->deactivate($parts[3] ?? 0);
                break;
            default:
                http_response_code(404);
        }
        break;
    
    default:
        http_response_code(404);
        if (file_exists(__DIR__ . '/errors/404.html')) {
            include __DIR__ . '/errors/404.html';
        } else {
            echo "404 - Not Found";
        }
}
EOF

echo
echo "3. Setting permissions..."
chmod 644 index.php

echo
echo "4. Verifying dictation endpoint exists..."
if [ -f ~/app/public-endpoints/dictation.php ]; then
    echo "✅ Endpoint file exists"
    # Check if it has MRN
    if grep -q "MRN\|mrn" ~/app/public-endpoints/dictation.php; then
        echo "⚠️  MRN found in endpoint - updating..."
        cp ~/public_html/dictation.php ~/app/public-endpoints/dictation.php
    fi
else
    echo "❌ Endpoint missing - creating..."
    cp ~/public_html/dictation.php ~/app/public-endpoints/dictation.php
fi

echo
echo "5. Testing the routes..."
echo "Simple routes now defined:"
grep -E "case.*'/[^/]+':$" index.php

echo
echo "=== Fix Complete ==="
echo
echo "Fixed:"
echo "- Added missing portal page code"
echo "- Made /dictation use simple endpoint (like phone-note)"
echo "- Removed complex controller routing"
echo "- Added dictation button to portal"
echo
echo "Test these URLs:"
echo "1. https://gmpm.us/ (should show portal)"
echo "2. https://gmpm.us/test123 (should show 'Endpoint routing works!')"
echo "3. https://gmpm.us/dictation (should show form without MRN)"
