<?php

namespace App\Core;

class Response
{
    public static function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    
    public static function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
