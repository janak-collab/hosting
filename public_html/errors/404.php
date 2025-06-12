<?php
http_response_code(404);
define('APP_PATH', '/home/gmpmus/app');

$viewFile = APP_PATH . '/resources/views/errors/404.php';
if (file_exists($viewFile)) {
    if (!function_exists('asset')) {
        function asset($path) { return '/assets/' . ltrim($path, '/'); }
    }
    include $viewFile;
} else {
    echo "<h1>404 Not Found</h1><p>The page you're looking for doesn't exist.</p>";
}
