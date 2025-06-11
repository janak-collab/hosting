<?php
namespace App\Controllers;

use App\Models\Provider;
use App\Models\Procedure;

class ProviderManagementController {
    private $providerModel;
    private $procedureModel;
    
    public function __construct() {
        $this->providerModel = new Provider();
        $this->procedureModel = new Procedure();
    }
    
    public function index() {
        // Check admin access
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        
        $providers = $this->providerModel->getActive();
        
        require_once __DIR__ . '/../../templates/views/admin/providers/index.php';
    }
    
    public function create() {
        if ($_SESSION['user_role'] !== 'admin') {
            header('Location: /');
            exit;
        }
        
        $error = '';
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate input
                if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['full_name'])) {
                    throw new \Exception('All fields are required');
                }
                
                // Check if username already exists
                $db = \App\Database\Connection::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
                $stmt->execute(['username' => $_POST['username']]);
                if ($stmt->fetch()) {
                    throw new \Exception('Username already exists');
                }
                
                // Create provider
                $providerId = $this->providerModel->create([
                    'username' => $_POST['username'],
                    'password' => $_POST['password'],
                    'full_name' => $_POST['full_name'],
                    'title' => $_POST['title'],
                    'npi_number' => $_POST['npi_number'] ?? null,
                    'license_number' => $_POST['license_number'] ?? null,
                    'locations' => $_POST['locations'] ?? []
                ]);
                
                $success = 'Provider created successfully!';
                
                // Clear form
                $_POST = [];
                
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }
        
        $locations = ['LSC', 'CSC', 'Leonardtown', 'Odenton', 'Prince Frederick', 
                     'Catonsville', 'Edgewater', 'Elkridge', 'Glen Burnie'];
        
        require_once __DIR__ . '/../../templates/views/admin/providers/create.php';
    }
    
    public function deactivate($id) {
        if ($_SESSION['user_role'] !== 'admin') {
            die('Access denied');
        }
        
        if ($this->providerModel->deactivate($id)) {
            $_SESSION['flash_message'] = 'Provider deactivated';
        } else {
            $_SESSION['flash_error'] = 'Failed to deactivate provider';
        }
        
        header('Location: /admin/providers');
    }
}
