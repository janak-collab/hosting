<?php
require_once 'app/vendor/autoload.php';

$servers = [
    ['host' => 'localhost', 'port' => 25, 'auth' => false],
    ['host' => 'localhost', 'port' => 587, 'auth' => true],
    ['host' => 'mail.gmpm.us', 'port' => 587, 'auth' => true],
    ['host' => 'mail.gmpm.us', 'port' => 465, 'auth' => true],
    ['host' => 'gmpm.us', 'port' => 587, 'auth' => true],
    ['host' => 's1023.use1.mysecurecloudhost.com', 'port' => 587, 'auth' => true],
    ['host' => 's1023.use1.mysecurecloudhost.com', 'port' => 25, 'auth' => false],
];

echo "Testing SMTP servers...\n";
echo "=====================\n\n";

foreach ($servers as $config) {
    echo "Testing {$config['host']}:{$config['port']} (auth: " . ($config['auth'] ? 'yes' : 'no') . ")... ";
    
    $sock = @fsockopen($config['host'], $config['port'], $errno, $errstr, 5);
    if ($sock) {
        $response = fgets($sock, 512);
        echo "✓ Connected - " . trim($response) . "\n";
        fclose($sock);
    } else {
        echo "✗ Failed ($errstr)\n";
    }
}

// Check if sendmail is available
echo "\nChecking sendmail: ";
$sendmail = trim(shell_exec('which sendmail'));
echo $sendmail ? "✓ Found at $sendmail\n" : "✗ Not found\n";

// Check PHP mail configuration
echo "\nPHP mail configuration:\n";
echo "sendmail_path: " . ini_get('sendmail_path') . "\n";
echo "SMTP: " . ini_get('SMTP') . "\n";
echo "smtp_port: " . ini_get('smtp_port') . "\n";

// Test PHP mail() function
echo "\nTesting PHP mail() function: ";
$test = mail('test@example.com', 'Test', 'Test message', 'From: noreply@gmpm.us');
echo $test ? "✓ Returned true\n" : "✗ Returned false\n";
