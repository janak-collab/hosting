<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

// Load bootstrap after session start
require_once __DIR__ . '/../../src/bootstrap.php';

use App\Controllers\ITSupportController;

$controller = new ITSupportController();
$controller->handleAdminLogin();
