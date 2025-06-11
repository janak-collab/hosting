<?php
session_start();

echo "Current session data:\n";
print_r($_SESSION);

echo "\n\nChecking if user is logged in:\n";
echo "admin_logged_in: " . (isset($_SESSION['admin_logged_in']) ? $_SESSION['admin_logged_in'] : 'NOT SET') . "\n";
echo "user: " . (isset($_SESSION['user']) ? 'SET' : 'NOT SET') . "\n";

if (isset($_SESSION['user'])) {
    echo "User data:\n";
    print_r($_SESSION['user']);
}
