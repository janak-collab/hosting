<?php
namespace App\Controllers;

class TestController extends BaseController {
    public function index() {
        return $this->view('test/index', [
            'title' => 'Test Page',
            'message' => 'Router is working!',
            'time' => date('Y-m-d H:i:s')
        ]);
    }
    
    public function json() {
        return $this->json([
            'status' => 'success',
            'message' => 'API is working!',
            'timestamp' => time()
        ]);
    }
}
