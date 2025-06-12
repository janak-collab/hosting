<?php
http_response_code(503);
define('APP_PATH', '/home/gmpmus/app');

$viewFile = APP_PATH . '/resources/views/errors/503.php';
if (file_exists($viewFile)) {
    if (!function_exists('asset')) {
        function asset($path) { return '/assets/' . ltrim($path, '/'); }
    }
    include $viewFile;
} else {
    echo "<h1>503 Service Unavailable</h1><p>The service is temporarily unavailable. Please try again later.</p>";
}
