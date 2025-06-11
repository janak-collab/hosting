<?php
require_once __DIR__ . '/vendor/autoload.php';

echo "\nGMPM Application Routes\n";
echo "======================\n\n";
echo sprintf("%-8s %-40s %-40s\n", "METHOD", "URI", "ACTION");
echo str_repeat("-", 90) . "\n";

// Manually list the routes since we can't access the dispatcher data directly
$routes = [
    ['GET', '/', 'PortalController@index'],
    ['GET', '/status', 'StatusController@index'],
    ['GET', '/health', 'StatusController@health'],
    ['GET', '/phone-note', 'PhoneNoteController@showForm'],
    ['POST', '/phone-note', 'PhoneNoteController@submit'],
    ['GET', '/it-support', 'ITSupportController@showForm'],
    ['POST', '/it-support/submit', 'ITSupportController@handleSubmission'],
    ['GET', '/admin/login', 'AdminController@showLogin'],
    ['POST', '/admin/login', 'AdminController@handleLogin'],
    ['GET', '/admin/dashboard', 'AdminController@dashboard'],
    ['GET', '/admin/logout', 'AdminController@logout'],
    ['GET', '/admin/tickets', 'ITSupportController@showAdminPanel'],
    ['GET', '/admin/phone-notes', 'PhoneNoteController@listNotes'],
    ['GET', '/admin/phone-notes/view/{id}', 'PhoneNoteController@viewNote'],
    ['GET', '/admin/phone-notes/print/{id}', 'PhoneNoteController@printNote'],
    ['POST', '/admin/phone-notes/status/{id}', 'PhoneNoteController@updateStatus'],
];

foreach ($routes as $route) {
    echo sprintf("%-8s %-40s %-40s\n", $route[0], $route[1], $route[2]);
}

echo "\n✓ Total routes: " . count($routes) . "\n\n";
