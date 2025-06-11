<?php
// Check if user is admin based on username
$adminUsers = ['admin', 'jvidyarthi']; // Add more admin usernames as needed
$currentUser = $_SERVER['PHP_AUTH_USER'] ?? '';
$isAdmin = in_array($currentUser, $adminUsers);
?>
