<?php
$file = '/home/gmpmus/app/src/Router.php';
$lines = file('/home/gmpmus/app/src/Router.php');

echo "Showing Router.php constructor area:\n";
echo "=====================================\n";

$inConstructor = false;
$braceCount = 0;

foreach ($lines as $num => $line) {
    $lineNum = $num + 1;
    
    if (strpos($line, 'public function __construct()') !== false) {
        $inConstructor = true;
    }
    
    if ($inConstructor) {
        printf("%3d: %s", $lineNum, $line);
        
        // Count braces
        $braceCount += substr_count($line, '{');
        $braceCount -= substr_count($line, '}');
        
        // Check for status routes
        if (strpos($line, 'status') !== false && strpos($line, 'addRoute') !== false) {
            echo "     ^^^ STATUS ROUTE FOUND ^^^\n";
        }
        
        if ($braceCount == 0 && strpos($line, '}') !== false) {
            break;
        }
    }
}
