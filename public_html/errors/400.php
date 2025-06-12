<?php
http_response_code(400);
define('APP_PATH', '/home/gmpmus/app');

$viewFile = APP_PATH . '/resources/views/errors/400.php';
if (file_exists($viewFile)) {
    if (!function_exists('asset')) {
        function asset($path) { return '/assets/' . ltrim($path, '/'); }
    }
    include $viewFile;
} else {
    echo "<h1>400 Bad Request</h1><p>The request could not be understood by the server.</p>";
}
