<?php
// Find all error_log calls and show context

$directories = ['app/src', 'app/public-endpoints', 'public_html'];
$foundCount = 0;
$files = [];

foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file);
            if (strpos($content, 'error_log') !== false) {
                $files[] = $file->getPathname();
                $foundCount++;
            }
        }
    }
}

echo "Found error_log in $foundCount files:\n\n";

foreach ($files as $filepath) {
    echo "=== $filepath ===\n";
    $content = file_get_contents($filepath);
    $lines = explode("\n", $content);
    
    foreach ($lines as $lineNum => $line) {
        if (strpos($line, 'error_log') !== false) {
            $start = max(0, $lineNum - 2);
            $end = min(count($lines) - 1, $lineNum + 2);
            
            echo "Line " . ($lineNum + 1) . ":\n";
            for ($i = $start; $i <= $end; $i++) {
                $prefix = ($i == $lineNum) ? '>>> ' : '    ';
                echo $prefix . $lines[$i] . "\n";
            }
            
            // Suggest replacement
            echo "\nSuggested replacement:\n";
            if (preg_match('/error_log\s*\(\s*["\']([^"\']*Error[^"\']*)["\']/', $line, $matches) ||
                preg_match('/error_log\s*\(\s*["\']([^"\']*failed[^"\']*)["\']/', $line, $matches) ||
                preg_match('/error_log\s*\(\s*["\']([^"\']*Exception[^"\']*)["\']/', $line, $matches)) {
                echo "    Logger::error('" . $matches[1] . "');\n";
            } elseif (preg_match('/error_log\s*\(\s*["\']([^"\']*Access[^"\']*)["\']/', $line, $matches)) {
                echo "    Logger::access('" . $matches[1] . "');\n";
            } else {
                echo "    Logger::info('...');\n";
            }
            echo "\n";
        }
    }
    echo "\n";
}

// Create sed commands file
$sedCommands = "#!/bin/bash\n";
$sedCommands .= "# Auto-generated sed commands to update error_log calls\n\n";

foreach ($files as $filepath) {
    $sedCommands .= "# Update $filepath\n";
    $sedCommands .= "sed -i.bak 's/error_log(/\\/\\/error_log(/g' $filepath\n";
    $sedCommands .= "# Add Logger use statement if not present\n";
    $sedCommands .= "grep -q 'use App\\\\Services\\\\Logger;' $filepath || sed -i '/^namespace/a\\use App\\\\Services\\\\Logger;' $filepath\n\n";
}

file_put_contents('update_logs_sed.sh', $sedCommands);
echo "\nCreated update_logs_sed.sh with sed commands to comment out error_log calls\n";
