#!/bin/bash

echo "==================================="
echo "Finding and Updating error_log Calls"
echo "==================================="

# Create a more comprehensive search and replace script
cat > find_error_logs.php << 'EOF'
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
EOF

# Run the PHP script
php find_error_logs.php

# Create a manual update helper
cat > manual_log_updates.md << 'EOF'
# Manual Log Updates Guide

## Common Replacements

### Error Logging
```php
// Old
error_log("Error: " . $e->getMessage());

// New
Logger::error($e->getMessage(), [
    'exception' => get_class($e),
    'trace' => $e->getTraceAsString()
]);
```

### Info Logging
```php
// Old
error_log("User $username logged in");

// New
Logger::info('User logged in', ['username' => $username]);
```

### Access Logging
```php
// Old
error_log("Access: " . $_SERVER['REMOTE_ADDR'] . " - " . $requestUri);

// New
Logger::access('Page accessed', [
    'uri' => $requestUri,
    'ip' => $_SERVER['REMOTE_ADDR']
]);
```

### Debug Logging
```php
// Old
error_log("Debug: " . print_r($data, true));

// New
Logger::debug('Debug data', ['data' => $data]);
```

## Don't Forget

1. Add `use App\Services\Logger;` at the top of each file
2. Use structured data in the context array instead of string concatenation
3. Choose appropriate log levels (error, warning, info, debug)
4. Use specific channels (access, security, sql) when appropriate
EOF

echo "✓ Created manual_log_updates.md with examples"

# Summary
echo ""
echo "==================================="
echo "Log Update Summary"
echo "==================================="
echo ""
echo "Tools created:"
echo "1. find_error_logs.php - Shows all error_log calls with context"
echo "2. update_logs_sed.sh - Auto-generated sed commands (after running find_error_logs.php)"
echo "3. manual_log_updates.md - Guide for manual updates"
echo ""
echo "Recommended process:"
echo "1. Review the output above"
echo "2. Make backups: cp -r app/src app/src.backup"
echo "3. Either:"
echo "   a) Run: chmod +x update_logs_sed.sh && ./update_logs_sed.sh (to comment out error_logs)"
echo "   b) Manually update each file using the suggestions"
echo "4. Test your application thoroughly"
