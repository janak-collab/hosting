<?php
// Get templates API endpoint
require_once __DIR__ . '/../../../app/vendor/autoload.php';
require_once __DIR__ . '/../../../app/src/bootstrap.php';

use App\Controllers\DictationController;

$controller = new DictationController();
$controller->getTemplates();
