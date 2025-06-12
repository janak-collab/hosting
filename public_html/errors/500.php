<?php
http_response_code(500);
define('APP_PATH', '/home/gmpmus/app');

$viewFile = APP_PATH . '/resources/views/errors/500.php';
if (file_exists($viewFile)) {
    if (!function_exists('asset')) {
        function asset($path) { return '/assets/' . ltrim($path, '/'); }
    }
    include $viewFile;
} else {
    echo "<h1>500 Internal Server Error</h1><p>An error occurred. Please contact support.</p>";
}
