<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../src/bootstrap.php';

use App\Controllers\PhoneNoteController;

$controller = new PhoneNoteController();
$controller->listNotes();
