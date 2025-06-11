<?php
$file = '/home/gmpmus/app/src/Controllers/ITSupportController.php';
$content = file_get_contents($file);

// Map of specific error_log replacements for debug logs
$replacements = [
    'error_log("=== LOGIN DEBUG START ===");' => 'Logger::debug("Login debug start");',
    'error_log("Request method: " . $_SERVER[\'REQUEST_METHOD\']);' => 'Logger::debug("Login attempt", ["method" => $_SERVER[\'REQUEST_METHOD\']]);',
    'error_log("Username provided: " . $username);' => 'Logger::debug("Login attempt", ["username" => $username]);',
    'error_log("Password length: " . strlen($password));' => 'Logger::debug("Login attempt", ["password_length" => strlen($password)]);',
    'error_log("Auth result: " . ($result ? "SUCCESS" : "FAILED"));' => 'Logger::debug("Auth result", ["success" => $result]);',
    'error_log("Session after auth: " . print_r($_SESSION, true));' => 'Logger::debug("Session after auth", ["session" => $_SESSION]);',
    'error_log("Auth exception: " . $e->getMessage());' => 'Logger::error("Auth exception", ["message" => $e->getMessage(), "trace" => $e->getTraceAsString()]);'
];

foreach ($replacements as $old => $new) {
    $content = str_replace($old, $new, $content);
}

// Make sure Logger is imported
if (!preg_match('/use\s+App\\\\Services\\\\Logger;/', $content)) {
    // Add after the namespace line
    $content = preg_replace(
        '/(namespace\s+App\\\\Controllers;)\s*\n/',
        "$1\n\nuse App\\Services\\Logger;",
        $content
    );
}

file_put_contents($file, $content);
echo "Updated ITSupportController.php\n";
