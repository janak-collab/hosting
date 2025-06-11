<?php
session_start();
$_SESSION['username'] = $_SERVER['PHP_AUTH_USER'] ?? 'TestUser';
$_SESSION['user_role'] = 'admin';
define('APP_PATH', '/home/gmpmus/app');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Header Test</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/header-styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php require_once APP_PATH . '/templates/components/header.php'; ?>
    
    <div class="container" style="padding: 2rem;">
        <div class="form-card">
            <div class="form-content">
                <h2>Responsive Header Test Page</h2>
                <p>Resize your browser window to test the responsive behavior:</p>
                <ul>
                    <li><strong>Desktop (>1024px):</strong> Full header with search</li>
                    <li><strong>Tablet (768-1024px):</strong> Condensed user info</li>
                    <li><strong>Mobile (<768px):</strong> Hamburger menu</li>
                </ul>
                <p>Current viewport width: <span id="viewport-width"></span>px</p>
            </div>
        </div>
    </div>
    
    <script src="/assets/js/header.js"></script>
    <script>
        function updateViewportWidth() {
            document.getElementById('viewport-width').textContent = window.innerWidth;
        }
        updateViewportWidth();
        window.addEventListener('resize', updateViewportWidth);
    </script>
</body>
</html>
