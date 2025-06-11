<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../src/bootstrap.php';

use App\Controllers\ITSupportController;

$controller = new ITSupportController();
$controller->showAdminPanel();
