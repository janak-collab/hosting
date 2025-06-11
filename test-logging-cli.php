<?php
// Command-line logging test
require_once __DIR__ . '/app/src/bootstrap.php';

use App\Services\Logger;

echo "Testing logging from CLI...\n";

Logger::info('CLI test message', ['source' => 'command-line']);
Logger::error('CLI error test', ['test' => true]);

echo "Logs written. Check app/storage/logs/\n";
