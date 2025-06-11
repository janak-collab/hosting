<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\UserService;
use App\Services\AuthService;

class UserManagementController {
    private $userModel;
    private $userService;
    private $authService;
    
    public function __construct() {
        $this->userModel = new User();
        $this->userService = new UserService();
        $this->authService = new AuthService();
    }
    
    public function index() {
        $this->authService->requireRole('super_admin');
        
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        // Generate CSRF token for delete operations
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $users = $this->userModel->getAllUsers();
        $message = $_SESSION['flash_message'] ?? null;
        $messageType = $_SESSION['flash_message_type'] ?? 'success';
        
        // Get current user info
        $currentUser = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['user_username'] ?? null,
            'role' => $_SESSION['user_role'] ?? null
        ];
        
        unset($_SESSION['flash_message']);
        unset($_SESSION['flash_message_type']);
        
        require_once __DIR__ . '/../../templates/views/admin/users/index.php';
    }
    
    public function create() {
        $this->authService->requireRole('super_admin');
        
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Get current user info
        $currentUser = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['user_username'] ?? null,
            'role' => $_SESSION['user_role'] ?? null
        ];
        
        $errors = $_SESSION['errors'] ?? [];
        $oldInput = $_SESSION['old_input'] ?? [];
        unset($_SESSION['errors'], $_SESSION['old_input']);
        
        require_once __DIR__ . '/../../templates/views/admin/users/create.php';
    }
    
    public function store() {
        $this->authService->requireRole('super_admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['flash_message'] = 'Invalid security token. Please try again.';
            $_SESSION['flash_message_type'] = 'error';
            header('Location: /admin/users/create');
            exit;
        }
        
        // Validate input
        $data = [
            'username' => trim($_POST['username'] ?? ''),
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'notes' => trim($_POST['notes'] ?? ''),
            'created_by' => $_SESSION['user_id'] ?? null
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['username'])) {
            $errors['username'] = 'Username is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $data['username'])) {
            $errors['username'] = 'Username can only contain letters, numbers, dashes and underscores.';
        } elseif ($this->userModel->getByUsername($data['username'])) {
            $errors['username'] = 'Username already exists.';
        }
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required.';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }
        
        if (!in_array($data['role'], ['user', 'admin', 'super_admin'])) {
            $errors['role'] = 'Invalid role selected.';
        }
        
        if (empty($data['password'])) {
            $errors['password'] = 'Password is required.';
        } elseif (!$this->userService->validatePassword($data['password'])) {
            $errors['password'] = 'Password must be at least 12 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
        }
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $data;
            header('Location: /admin/users/create');
            exit;
        }
        
        // Create user
        try {
            // Extract password from data before passing to createUser
            $password = $data['password'];
            $createdBy = $_SESSION['user_id'] ?? null;
            
            // Remove password fields from data array as they're passed separately
            $userData = $data;
            unset($userData['password'], $userData['confirm_password']);
            
            $userId = $this->userService->createUser($userData, $password, $createdBy);
            
            if ($userId) {
                $_SESSION['flash_message'] = 'User created successfully!';
                $_SESSION['flash_message_type'] = 'success';
                header('Location: /admin/users');
            } else {
                $_SESSION['flash_message'] = 'Failed to create user. Please try again.';
                $_SESSION['flash_message_type'] = 'error';
                header('Location: /admin/users/create');
            }
        } catch (\Exception $e) {
            error_log("User creation error: " . $e->getMessage());
            $_SESSION['flash_message'] = 'An error occurred while creating the user.';
            $_SESSION['flash_message_type'] = 'error';
            $_SESSION['old_input'] = $data;
            header('Location: /admin/users/create');
        }
        exit;
    }
    
    public function edit($id) {
        $this->authService->requireRole('super_admin');
        
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found.';
            $_SESSION['flash_message_type'] = 'error';
            header('Location: /admin/users');
            exit;
        }
        
        // Get current user info
        $currentUser = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['user_username'] ?? null,
            'role' => $_SESSION['user_role'] ?? null
        ];
        
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['errors']);
        
        require_once __DIR__ . '/../../templates/views/admin/users/edit.php';
    }
    
    public function update($id) {
        $this->authService->requireRole('super_admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['flash_message'] = 'Invalid security token. Please try again.';
            $_SESSION['flash_message_type'] = 'error';
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['flash_message'] = 'User not found.';
            $_SESSION['flash_message_type'] = 'error';
            header('Location: /admin/users');
            exit;
        }
        
        // Validate input
        $data = [
            'full_name' => trim($_POST['full_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'role' => $_POST['role'] ?? 'user',
            'is_active' => isset($_POST['is_active']) ? 1 : 0,
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'notes' => trim($_POST['notes'] ?? '')
        ];
        
        // Validation
        $errors = [];
        
        if (empty($data['full_name'])) {
            $errors['full_name'] = 'Full name is required.';
        }
        
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }
        
        if (!in_array($data['role'], ['user', 'admin', 'super_admin'])) {
            $errors['role'] = 'Invalid role selected.';
        }
        
        // Prevent removing super_admin role from jvidyarthi
        if ($user['username'] === 'jvidyarthi' && $data['role'] !== 'super_admin') {
            $errors['role'] = 'Cannot change role for this user.';
        }
        
        // Validate password if provided
        if (!empty($data['password'])) {
            if (!$this->userService->validatePassword($data['password'])) {
                $errors['password'] = 'Password must be at least 12 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special character.';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        // Update user
        try {
            $updateData = [
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'role' => $data['role'],
                'is_active' => $data['is_active'],
                'notes' => $data['notes']
            ];
            
            // Handle password update separately if provided
            $password = !empty($data['password']) ? $data['password'] : null;
            
            $success = $this->userService->updateUser($id, $updateData, $_SESSION['user_id'] ?? null, $password);
            
            if ($success) {
                $_SESSION['flash_message'] = 'User updated successfully!';
                $_SESSION['flash_message_type'] = 'success';
                header('Location: /admin/users');
            } else {
                $_SESSION['flash_message'] = 'Failed to update user. Please try again.';
                $_SESSION['flash_message_type'] = 'error';
                header('Location: /admin/users/edit/' . $id);
            }
        } catch (\Exception $e) {
            error_log("User update error: " . $e->getMessage());
            $_SESSION['flash_message'] = 'An error occurred while updating the user.';
            $_SESSION['flash_message_type'] = 'error';
            header('Location: /admin/users/edit/' . $id);
        }
        exit;
    }
    
    public function delete($id) {
        $this->authService->requireRole('super_admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'User not found.']);
            return;
        }
        
        // Prevent deleting yourself
        if ($user['id'] == ($_SESSION['user_id'] ?? 0)) {
            $this->jsonResponse(['success' => false, 'message' => 'You cannot delete your own account.']);
            return;
        }
        
        // Prevent deleting jvidyarthi
        if ($user['username'] === 'jvidyarthi') {
            $this->jsonResponse(['success' => false, 'message' => 'This user cannot be deleted.']);
            return;
        }
        
        try {
            $success = $this->userService->deleteUser($id, $_SESSION['user_id'] ?? null);
            
            if ($success) {
                $this->jsonResponse(['success' => true, 'message' => 'User deleted successfully.']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to delete user.']);
            }
        } catch (\Exception $e) {
            error_log("User deletion error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'An error occurred while deleting the user.']);
        }
    }
    
    public function unlock($id) {
        $this->authService->requireRole('super_admin');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
            $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid security token.']);
            return;
        }
        
        try {
            $success = $this->userModel->unlockUser($id);
            
            if ($success) {
                // Log the unlock action
                $this->userModel->logAction($id, 'unlocked', null, null, $_SESSION['user_id'] ?? null);
                $this->jsonResponse(['success' => true, 'message' => 'User unlocked successfully.']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to unlock user.']);
            }
        } catch (\Exception $e) {
            error_log("User unlock error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'An error occurred while unlocking the user.']);
        }
    }
    
    // API Methods
    
    public function apiList() {
        $this->authService->requireRole('super_admin');
        
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        
        try {
            $users = $this->userModel->getAllUsers();
            $this->jsonResponse(['success' => true, 'users' => $users]);
        } catch (\Exception $e) {
            error_log("API list users error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'Failed to retrieve users.']);
        }
    }
    
    public function checkUsername() {
        $this->authService->requireRole('super_admin');
        
        header('Content-Type: application/json');
        
        $username = $_GET['username'] ?? '';
        $excludeId = $_GET['exclude_id'] ?? null;
        
        if (empty($username)) {
            $this->jsonResponse(['available' => false, 'message' => 'Username is required.']);
            return;
        }
        
        try {
            $user = $this->userModel->getByUsername($username);
            
            if ($user && (!$excludeId || $user['id'] != $excludeId)) {
                $this->jsonResponse(['available' => false, 'message' => 'Username already taken.']);
            } else {
                $this->jsonResponse(['available' => true, 'message' => 'Username is available.']);
            }
        } catch (\Exception $e) {
            error_log("Check username error: " . $e->getMessage());
            $this->jsonResponse(['available' => false, 'message' => 'Error checking username.']);
        }
    }
    
    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
