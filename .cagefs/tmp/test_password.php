<?php
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\UserService;

$userService = new UserService();

$passwords = [
    'short' => 'Short1!',
    'no_upper' => 'validpass123!@#',
    'no_lower' => 'VALIDPASS123!@#',
    'no_number' => 'ValidPass!@#',
    'no_special' => 'ValidPass123',
    'valid' => 'ValidPass123!@#'
];

foreach ($passwords as $type => $password) {
    $result = $userService->validatePassword($password);
    echo "$type: '$password' = " . ($result ? 'VALID' : 'INVALID') . "\n";
}
