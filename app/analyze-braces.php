<?php
$file = '/home/gmpmus/app/src/Router.php';
$lines = file($file);
$braceLevel = 0;
$inClass = false;
$classLevel = 0;

foreach ($lines as $num => $line) {
    $lineNum = $num + 1;
    $openBraces = substr_count($line, '{');
    $closeBraces = substr_count($line, '}');
    
    if (strpos($line, 'class Router') !== false) {
        $inClass = true;
        $classLevel = $braceLevel + 1;
        echo "Line $lineNum: Found class Router (level will be $classLevel)\n";
    }
    
    $braceLevel += $openBraces;
    $braceLevel -= $closeBraces;
    
    if ($inClass && $braceLevel < $classLevel) {
        echo "Line $lineNum: Class ends here (brace level: $braceLevel)\n";
        $inClass = false;
    }
    
    if ($braceLevel == 0 && preg_match('/^\s*public\s+function/', $line)) {
        echo "Line $lineNum: ERROR - Public function outside class: " . trim($line) . "\n";
    }
}

echo "\nFinal brace level: $braceLevel\n";
