<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../src/bootstrap.php';

use App\Controllers\PhoneNoteController;

// Get ID from URL
$id = $_GET['id'] ?? null;
if (!$id) {
    die('No phone note ID provided');
}

$controller = new PhoneNoteController();
$controller->printNote($id);
