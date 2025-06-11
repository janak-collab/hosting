<?php
$file = '/home/gmpmus/app/src/Core/Router.php';
$content = file_get_contents($file);

// Find the callHandler method and add better logging
$pattern = '/private function callHandler\(\$handler, \$params = \[\]\) \{/';
$replacement = 'private function callHandler($handler, $params = []) {
        error_log(\'GMPM Router: callHandler called with handler: \' . print_r($handler, true));
        error_log(\'GMPM Router: handler type: \' . gettype($handler));';

$content = preg_replace($pattern, $replacement, $content);

file_put_contents($file, $content);
echo "Router logging updated\n";
