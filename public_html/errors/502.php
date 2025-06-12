<?php
http_response_code(502);
define('APP_PATH', '/home/gmpmus/app');

$viewFile = APP_PATH . '/resources/views/errors/502.php';
if (file_exists($viewFile)) {
    if (!function_exists('asset')) {
        function asset($path) { return '/assets/' . ltrim($path, '/'); }
    }
    include $viewFile;
} else {
    echo "<h1>502 Bad Gateway</h1><p>The server received an invalid response from the upstream server.</p>";
}
