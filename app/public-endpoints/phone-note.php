<?php
// Adjust path since we're now in /app/public-endpoints/
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

require_once APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/src/bootstrap.php';

use App\Controllers\PhoneNoteController;

$controller = new PhoneNoteController();
$controller->showForm();
