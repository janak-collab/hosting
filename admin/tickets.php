<?php
require_once __DIR__ . '/../app/vendor/autoload.php';
require_once __DIR__ . '/../app/src/bootstrap.php';

use App\Controllers\ITSupportController;

$controller = new ITSupportController();
$controller->showAdminPanel();
