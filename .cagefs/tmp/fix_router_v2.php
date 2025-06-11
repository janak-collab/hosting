<?php
$file = '/home/gmpmus/app/src/Core/Router.php';
$content = file_get_contents($file);

// Look for the specific old callHandler pattern
$oldPattern = '/private function callHandler\(\$handler, \$params = \[\]\) \{[^}]+error_log\(\'GMPM Router: callHandler called with handler: \'[^}]+\}/s';

// Check if it exists
if (preg_match($oldPattern, $content, $matches)) {
    echo "Found old callHandler method:\n";
    echo substr($matches[0], 0, 200) . "...\n\n";
} else {
    echo "Old pattern not found, trying different approach\n";
}

// Replace with simpler pattern
$content = preg_replace(
    '/private function callHandler\([^{]+\{[^}]+\}/s',
    'private function callHandler($handler, $params = []) {
        // String format: Controller@method
        if (is_string($handler)) {
            if (strpos($handler, \'@\') !== false) {
                list($controllerName, $method) = explode(\'@\', $handler);
                
                // Add namespace if not present
                if (strpos($controllerName, \'\\\\\') === false) {
                    $controllerClass = \'\\App\\Controllers\\\\\' . $controllerName;
                } else {
                    $controllerClass = $controllerName;
                }
                
                if (!class_exists($controllerClass)) {
                    throw new \\Exception("Controller not found: {$controllerClass}");
                }
                
                $controller = new $controllerClass();
                
                if (!method_exists($controller, $method)) {
                    throw new \\Exception("Method not found: {$method}");
                }
                
                return call_user_func_array([$controller, $method], $params);
            }
        }
        
        // Callable
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        
        throw new \\Exception("Invalid handler type");
    }',
    $content,
    1,
    $count
);

if ($count > 0) {
    file_put_contents($file, $content);
    echo "Successfully replaced callHandler method\n";
} else {
    echo "Failed to replace method, trying line-by-line approach\n";
    
    // Try line by line
    $lines = file($file);
    $inMethod = false;
    $methodStart = -1;
    $braceCount = 0;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'private function callHandler') !== false) {
            $methodStart = $i;
            $inMethod = true;
            $braceCount = 0;
        }
        
        if ($inMethod) {
            $braceCount += substr_count($lines[$i], '{');
            $braceCount -= substr_count($lines[$i], '}');
            
            if ($braceCount == 0 && $i > $methodStart) {
                // Found end of method
                echo "Found method from line " . ($methodStart + 1) . " to " . ($i + 1) . "\n";
                
                // Replace these lines
                $newMethod = [
                    "    private function callHandler(\$handler, \$params = []) {\n",
                    "        // String format: Controller@method\n",
                    "        if (is_string(\$handler)) {\n",
                    "            if (strpos(\$handler, '@') !== false) {\n",
                    "                list(\$controllerName, \$method) = explode('@', \$handler);\n",
                    "                \n",
                    "                // Add namespace if not present\n",
                    "                if (strpos(\$controllerName, '\\\\') === false) {\n",
                    "                    \$controllerClass = '\\App\\Controllers\\\\' . \$controllerName;\n",
                    "                } else {\n",
                    "                    \$controllerClass = \$controllerName;\n",
                    "                }\n",
                    "                \n",
                    "                if (!class_exists(\$controllerClass)) {\n",
                    "                    throw new \\Exception(\"Controller not found: {\$controllerClass}\");\n",
                    "                }\n",
                    "                \n",
                    "                \$controller = new \$controllerClass();\n",
                    "                \n",
                    "                if (!method_exists(\$controller, \$method)) {\n",
                    "                    throw new \\Exception(\"Method not found: {\$method}\");\n",
                    "                }\n",
                    "                \n",
                    "                return call_user_func_array([\$controller, \$method], \$params);\n",
                    "            }\n",
                    "        }\n",
                    "        \n",
                    "        // Callable\n",
                    "        if (is_callable(\$handler)) {\n",
                    "            return call_user_func_array(\$handler, \$params);\n",
                    "        }\n",
                    "        \n",
                    "        throw new \\Exception(\"Invalid handler type\");\n",
                    "    }\n"
                ];
                
                // Remove old lines
                array_splice($lines, $methodStart, $i - $methodStart + 1, $newMethod);
                
                // Write back
                file_put_contents($file, implode('', $lines));
                echo "Method replaced successfully\n";
                break;
            }
        }
    }
}
