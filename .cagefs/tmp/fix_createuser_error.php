<?php
$file = '/home/gmpmus/app/src/Services/UserService.php';
$content = file_get_contents($file);

// Find and update the password validation error in createUser
$content = preg_replace(
    '/throw new Exception\([\'"]Password does not meet requirements[\'"]/',
    'throw new Exception(\'Password does not meet requirements: minimum 12 characters, must contain uppercase, lowercase, number, and special character\'',
    $content
);

// Also check if we're properly checking the validation
$content = str_replace(
    'if (!$this->validatePassword($data[\'password\'])) {
            throw new Exception(\'Password does not meet requirements\');
        }',
    'if (!$this->validatePassword($data[\'password\'])) {
            throw new Exception(\'Password does not meet requirements: minimum 12 characters, must contain uppercase, lowercase, number, and special character\');
        }',
    $content
);

file_put_contents($file, $content);
echo "Updated createUser error messages\n";
?>
