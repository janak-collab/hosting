#!/bin/bash
# Fix 401 error page for GMPM

echo "=== Fixing 401 Error Page ==="
echo ""

# 1. Create errors directory if it doesn't exist
echo "1. Setting up errors directory..."
mkdir -p ~/public_html/errors
chmod 755 ~/public_html/errors

# 2. Create a PHP handler for 401 that includes the proper view
echo "2. Creating 401.php handler..."
cat > ~/public_html/errors/401.php << 'EOF'
<?php
// Set 401 status (in case it's not already set)
http_response_code(401);

// Define paths to match the application
define('APP_PATH', '/home/gmpmus/app');
define('PUBLIC_PATH', '/home/gmpmus/public_html');

// Function to safely include the view
function show401Page() {
    $viewFile = APP_PATH . '/resources/views/errors/401.php';
    
    if (file_exists($viewFile)) {
        // Include the custom 401 page
        include $viewFile;
    } else {
        // Fallback if custom page doesn't exist
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>401 - Unauthorized | Greater Maryland Pain Management</title>
            <link rel="stylesheet" href="/assets/css/app.css">
        </head>
        <body>
            <div class="container">
                <div class="form-card">
                    <div class="form-header">
                        <h1>🔒 Unauthorized Access</h1>
                    </div>
                    <div class="form-content">
                        <div class="alert alert-error">
                            You need to be authenticated to access this resource.
                        </div>
                        <p>Please log in with your credentials to continue.</p>
                        <div class="form-actions">
                            <a href="/" class="btn btn-primary">Go to Portal</a>
                        </div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}

// Helper function for assets
if (!function_exists('asset')) {
    function asset($path) {
        return '/assets/' . ltrim($path, '/');
    }
}

// Show the 401 page
show401Page();
