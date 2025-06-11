<?php
echo "PHP_AUTH_USER: " . ($_SERVER['PHP_AUTH_USER'] ?? 'not set') . "\n";
echo "REMOTE_USER: " . ($_SERVER['REMOTE_USER'] ?? 'not set') . "\n";
echo "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
