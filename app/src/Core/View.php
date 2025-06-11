<?php

namespace App\Core;

class View
{
    public static function render($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Build view path
        $viewPath = APP_PATH . '/templates/views/' . $view . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View file not found: $viewPath");
        }
        
        // Include the view
        include $viewPath;
    }
}
