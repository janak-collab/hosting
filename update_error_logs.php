<?php
/**
 * Update error_log() calls to use Logger
 */

echo "===================================\n";
echo "Update error_log to Logger Script\n";
echo "===================================\n\n";

// Find all PHP files
$files = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('app/'),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator as $file) {
    if ($file->getExtension() === 'php' && 
        strpos($file->getPathname(), '/vendor/') === false &&
        strpos($file->getPathname(), '.bak') === false) {
        $files[] = $file->getPathname();
    }
}

// Also check public_html
$iterator2 = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator('public_html/'),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($iterator2 as $file) {
    if ($file->getExtension() === 'php' && 
        strpos($file->getPathname(), '/vendor/') === false &&
        strpos($file->getPathname(), '.bak') === false) {
        $files[] = $file->getPathname();
    }
}

// Count error_log occurrences
$totalFiles = 0;
$totalOccurrences = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'error_log(') !== false) {
        $totalFiles++;
        $totalOccurrences += substr_count($content, 'error_log(');
    }
}

echo "Found error_log() in $totalFiles files with $totalOccurrences total occurrences\n\n";

// Create backup directory
$backupDir = 'backups/error_log_update_' . date('Ymd_His');
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}
echo "Created backup directory: $backupDir\n\n";

// Ask for confirmation
echo "Do you want to proceed with updating error_log to Logger? (y/n) ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) !== 'y') {
    echo "Aborted.\n";
    exit(0);
}
fclose($handle);

echo "\nProcessing files...\n\n";

$updatedCount = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    if (strpos($content, 'error_log(') === false) {
        continue;
    }
    
    echo "Processing: $file\n";
    
    // Create backup
    $backupFile = $backupDir . '/' . str_replace('/', '_', $file) . '.bak';
    file_put_contents($backupFile, $content);
    
    // Check if use statement exists
    $hasUseLogger = strpos($content, 'use App\Services\Logger;') !== false;
    $hasNamespace = preg_match('/^namespace\s+[^;]+;/m', $content);
    
    // Replace patterns
    $patterns = [
        // Simple string
        '/error_log\s*\(\s*([\'"])(.*?)\1\s*\);/' => function($matches) {
            return "Logger::error('{$matches[2]}');";
        },
        
        // With variable concatenation
        '/error_log\s*\(\s*([\'"])(.*?)\1\s*\.\s*\$(\w+)\s*\);/' => function($matches) {
            return "Logger::error('{$matches[2]}', ['{$matches[3]}' => \${$matches[3]}]);";
        },
        
        // With getMessage()
        '/error_log\s*\(\s*([\'"])(.*?)\1\s*\.\s*\$(\w+)->getMessage\(\)\s*\);/' => function($matches) {
            return "Logger::error('{$matches[2]}', ['error' => \${$matches[3]}->getMessage()]);";
        },
        
        // Debug patterns
        '/error_log\s*\(\s*([\'"])(.*DEBUG.*)\1\s*\);/i' => function($matches) {
            return "Logger::debug('{$matches[2]}');";
        },
        
        // print_r patterns
        '/error_log\s*\(\s*print_r\s*\(\s*\$(\w+)\s*,\s*true\s*\)\s*\);/' => function($matches) {
            return "Logger::debug('Debug output', ['{$matches[1]}' => \${$matches[1]}]);";
        },
    ];
    
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace_callback($pattern, $replacement, $content);
    }
    
    // Add use statement if needed
    if (!$hasUseLogger && strpos($content, 'Logger::') !== false) {
        if ($hasNamespace) {
            // Add after namespace
            $content = preg_replace(
                '/^(namespace\s+[^;]+;)/m',
                "$1\n\nuse App\Services\Logger;",
                $content,
                1
            );
        } else {
            // Add after <?php
            $content = preg_replace(
                '/^(<\?php)\s*\n/',
                "$1\n\nuse App\Services\Logger;\n",
                $content,
                1
            );
        }
    }
    
    // Only update if changes were made
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        echo "  ✓ Updated successfully\n";
        $updatedCount++;
    } else {
        echo "  ✓ No changes needed\n";
    }
}

echo "\n===================================\n";
echo "Update Complete!\n";
echo "===================================\n\n";
echo "Summary:\n";
echo "- Files processed: $totalFiles\n";
echo "- Files updated: $updatedCount\n";
echo "- Backups saved in: $backupDir\n\n";

// Check for remaining error_log calls
$remaining = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    if (strpos($content, 'error_log(') !== false) {
        $remaining += substr_count($content, 'error_log(');
    }
}

if ($remaining > 0) {
    echo "⚠️  Warning: $remaining error_log() calls still remain\n";
    echo "These may need manual review.\n\n";
} else {
    echo "✓ All error_log() calls have been converted!\n\n";
}

echo "Next steps:\n";
echo "1. Review the changes:\n";
echo "   grep -r 'Logger::' app/ public_html/ --include='*.php' | head -10\n\n";
echo "2. Test the application\n\n";
echo "3. Check the new log files:\n";
echo "   tail -f app/storage/logs/app-" . date('Y-m-d') . ".log\n\n";
