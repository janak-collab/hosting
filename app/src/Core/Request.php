<?php

namespace App\Core;

class Request
{
    public function post($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }
    
    public function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    public function all()
    {
        return array_merge($_GET, $_POST);
    }
}
