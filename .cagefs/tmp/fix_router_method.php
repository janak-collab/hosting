<?php
$file = '/home/gmpmus/app/src/Core/Router.php';
$lines = file($file);

// Find the callHandler method
$startLine = -1;
$endLine = -1;
$braceCount = 0;

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'private function callHandler') !== false) {
        $startLine = $i;
        $braceCount = 0;
        
        // Find the end of the method
        for ($j = $i; $j < count($lines); $j++) {
            $braceCount += substr_count($lines[$j], '{');
            $braceCount -= substr_count($lines[$j], '}');
            
            if ($braceCount == 0 && $j > $i) {
                $endLine = $j;
                break;
            }
        }
        break;
    }
}

if ($startLine == -1) {
    echo "Could not find callHandler method\n";
    exit(1);
}

echo "Found callHandler method from line " . ($startLine + 1) . " to " . ($endLine + 1) . "\n";

// Replace the method
$newMethod = '    private function callHandler($handler, $params = []) {
        error_log(\'GMPM Router: callHandler START - handler: \' . print_r($handler, true));
        error_log(\'GMPM Router: callHandler - params: \' . print_r($params, true));
        
        // String format: Controller@method
        if (is_string($handler)) {
            error_log(\'GMPM Router: Processing string handler: \' . $handler);
            
            if (strpos($handler, \'@\') !== false) {
                list($controllerName, $method) = explode(\'@\', $handler);
                error_log(\'GMPM Router: Split to controller: \' . $controllerName . \', method: \' . $method);
                
                // Add namespace if not present
                if (strpos($controllerName, \'\\\\\') === false) {
                    $controllerClass = \'\\App\\Controllers\\\\\' . $controllerName;
                } else {
                    $controllerClass = $controllerName;
                }
                
                error_log(\'GMPM Router: Full controller class: \' . $controllerClass);
                
                // Check if controller exists
                if (!class_exists($controllerClass)) {
                    error_log(\'GMPM Router: ERROR - Controller not found: \' . $controllerClass);
                    throw new \\Exception("Controller not found: {$controllerClass}");
                }
                
                $controller = new $controllerClass();
                error_log(\'GMPM Router: Controller instantiated: \' . get_class($controller));
                
                // Check if method exists
                if (!method_exists($controller, $method)) {
                    error_log(\'GMPM Router: ERROR - Method not found: \' . $method);
                    throw new \\Exception("Method not found: {$method}");
                }
                
                error_log(\'GMPM Router: Calling ' . get_class($controller) . '->\' . $method . \'()\');
                return call_user_func_array([$controller, $method], $params);
            } else {
                // Direct function call
                error_log(\'GMPM Router: Direct function call: \' . $handler);
                if (function_exists($handler)) {
                    return call_user_func_array($handler, $params);
                }
                throw new \\Exception("Function not found: {$handler}");
            }
        }
        
        // Callable (closure or array)
        if (is_callable($handler)) {
            error_log(\'GMPM Router: Callable handler\');
            return call_user_func_array($handler, $params);
        }
        
        error_log(\'GMPM Router: ERROR - Invalid handler type: \' . gettype($handler));
        throw new \\Exception("Invalid handler type");
    }' . "\n";

// Remove old method lines
for ($i = $startLine; $i <= $endLine; $i++) {
    unset($lines[$i]);
}

// Insert new method
array_splice($lines, $startLine, 0, $newMethod);

// Write back
file_put_contents($file, implode('', $lines));
echo "Method replaced successfully\n";
