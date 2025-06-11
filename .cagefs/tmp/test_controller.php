<?php
define('APP_PATH', '/home/gmpmus/app');
define('ROOT_PATH', dirname(APP_PATH));
define('CONFIG_PATH', APP_PATH . '/config');
define('RESOURCE_PATH', APP_PATH . '/resources');

// Simple autoloader
spl_autoload_register(function ($class) {
    $class = str_replace('App\\', '', $class);
    $file = APP_PATH . '/src/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$class = '\App\Controllers\Admin\IpAccessController';
if (class_exists($class)) {
    echo "Controller class found!\n";
} else {
    echo "Controller class NOT found\n";
}
