<?php
// Simple htpasswd replacement for cPanel hosting
if ($argc != 3) {
    echo "Usage: php make_passwd.php <username> <password>\n";
    exit(1);
}

$username = $argv[1];
$password = $argv[2];
$passwd_file = '/home/gmpmus/.htpasswds/passwd';

// Create BCrypt hash (Apache 2.4+ compatible)
$hash = password_hash($password, PASSWORD_BCRYPT);
$entry = $username . ':' . $hash;

echo "Add this line to your passwd file:\n";
echo $entry . "\n";
?>
