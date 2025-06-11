<?php
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\IpManagerService;

$service = new IpManagerService();
$ips = $service->getCurrentIPs();

echo "Found " . count($ips) . " IPs:\n";
foreach ($ips as $index => $ip) {
    echo ($index + 1) . ". IP: " . $ip['ip'] . ", Location: " . $ip['location'] . "\n";
}
