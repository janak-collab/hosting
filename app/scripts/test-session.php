<?php
session_start();

echo "Session ID: " . session_id() . "\n";
echo "Session save path: " . session_save_path() . "\n";
echo "Session save path writable: " . (is_writable(session_save_path()) ? "YES" : "NO") . "\n";

// Try to set a test value
$_SESSION['test'] = 'Hello World';
echo "Set test value in session\n";

// Start a new session to see if it persists
session_write_close();
session_start();

echo "\nAfter restart:\n";
echo "Session ID: " . session_id() . "\n";
echo "Test value: " . ($_SESSION['test'] ?? 'NOT FOUND') . "\n";
