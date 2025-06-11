<?php
$file = '/home/gmpmus/app/src/Services/UserService.php';
$content = file_get_contents($file);

// Add empty check at the beginning of validatePassword
$content = str_replace(
    'public function validatePassword($password) {
        // At least 12 characters',
    'public function validatePassword($password) {
        // Check if password is null or empty first
        if (empty($password)) {
            return false;
        }
        
        // At least 12 characters',
    $content
);

file_put_contents($file, $content);
echo "Fixed empty password check\n";
?>
