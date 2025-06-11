# Verify services were created
#1749556446
echo "Checking created services:"
#1749556446
ls -la /home/gmpmus/app/src/Services/HtpasswdService.php
#1749556447
ls -la /home/gmpmus/app/src/Services/UserService.php
#1749556452
clear
#1749556535
# First, let's backup the current AuthService
#1749556535
cp /home/gmpmus/app/src/Services/AuthService.php /home/gmpmus/app/src/Services/AuthService.php.backup
#1749556535
# Now update the AuthService
#1749556535
cat > /home/gmpmus/app/src/Services/AuthService.php << 'EOF'
<?php
namespace App\Services;

use App\Database\Connection;
use App\Models\User;
use App\Models\UserAuditLog;
use PDO;

class AuthService {
    private $db;
    private $userModel;
    private $auditLog;
    private $htpasswdService;
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
        $this->userModel = new User();
        $this->auditLog = new UserAuditLog();
        $this->htpasswdService = new HtpasswdService();
    }
    
    /**
     * Authenticate user against htpasswd and load from database
     */
    public function authenticate($username, $password) {
        try {
            // First check if user is locked
            if ($this->userModel->isLocked($username)) {
                $this->auditLog->log(
                    $this->userModel->getByUsername($username)['id'] ?? null,
                    'login_failed',
                    'Account locked',
                    null
                );
                return false;
            }
            
            // Verify password against htpasswd
            if (!$this->htpasswdService->verifyPassword($username, $password)) {
                // Record failed login
                $this->userModel->recordFailedLogin($username, $_SERVER['REMOTE_ADDR'] ?? null);
                $user = $this->userModel->getByUsername($username);
                if ($user) {
                    $this->auditLog->log($user['id'], 'login_failed', null, null);
                }
                return false;
            }
            
            // Get user from database
            $user = $this->userModel->getByUsername($username);
            
            // If user doesn't exist in DB but exists in htpasswd, create them
            if (!$user && $this->htpasswdService->userExists($username)) {
                $userId = $this->userModel->create([
                    'username' => $username,
                    'full_name' => ucfirst($username),
                    'role' => 'user',
                    'is_active' => 1,
                    'notes' => 'Auto-created on first login',
                    'created_by' => null
                ]);
                
                $user = $this->userModel->getById($userId);
                $this->auditLog->log($userId, 'created', null, 'Auto-created on first login');
            }
            
            // Check if user is active
            if (!$user || !$user['is_active']) {
                return false;
            }
            
            // Reset failed attempts on successful login
            $this->userModel->resetFailedAttempts($user['id']);
            
            // Create session
            $this->createSession($user);
            
            return true;
            
        } catch (\Exception $e) {
            error_log("Authentication error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create user session
     */
    private function createSession($user) {
        session_regenerate_id(true);
        
        $_SESSION['authenticated'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['full_name'] = $user['full_name'] ?: $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        // Set role-specific flags for easy checking
        $_SESSION['is_user'] = true;
        $_SESSION['is_admin'] = in_array($user['role'], ['admin', 'super_admin']);
        $_SESSION['is_super_admin'] = $user['role'] === 'super_admin';
        
        // For backward compatibility
        if ($_SESSION['is_admin']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];
        }
    }
    
    /**
     * Check if user has a specific role or higher
     */
    public function requireRole($minRole) {
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: /login');
            exit;
        }
        
        $roleHierarchy = [
            'user' => 1,
            'admin' => 2,
            'super_admin' => 3
        ];
        
        $userRole = $_SESSION['user_role'] ?? 'user';
        $userLevel = $roleHierarchy[$userRole] ?? 1;
        $requiredLevel = $roleHierarchy[$minRole] ?? 1;
        
        if ($userLevel < $requiredLevel) {
            http_response_code(403);
            die('Access denied. You need at least ' . $minRole . ' role to access this resource.');
        }
    }
    
    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission) {
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            return false;
        }
        
        $permissions = [
            'user' => [
                'view_dashboard',
                'submit_forms',
                'view_own_submissions'
            ],
            'admin' => [
                'view_dashboard',
                'submit_forms',
                'view_own_submissions',
                'manage_tickets',
                'manage_phone_notes',
                'view_reports'
            ],
            'super_admin' => [
                'view_dashboard',
                'submit_forms',
                'view_own_submissions',
                'manage_tickets',
                'manage_phone_notes',
                'view_reports',
                'manage_users',
                'manage_providers',
                'system_settings'
            ]
        ];
        
        $userRole = $_SESSION['user_role'] ?? 'user';
        return in_array($permission, $permissions[$userRole] ?? []);
    }
    
    /**
     * Get current user
     */
    public function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        return $this->userModel->getById($_SESSION['user_id']);
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn() {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'];
    }
    
    /**
     * Get user role
     */
    public function getUserRole() {
        return $_SESSION['user_role'] ?? null;
    }
    
    /**
     * Legacy method for backward compatibility
     */
    public function authenticate_old($username, $password) {
        $stmt = $this->db->prepare("
            SELECT id, username, password_hash, role 
            FROM users 
            WHERE username = :username AND is_active = 1
        ");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        $this->createSession($user);
        return true;
    }
}
EOF

#1749556535
# Now create the UserManagementController
#1749556535
cat > /home/gmpmus/app/src/Controllers/UserManagementController.php << 'EOF'
<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\UserAuditLog;
use App\Services\UserService;
use App\Services\AuthService;
use App\Services\HtpasswdService;

class UserManagementController {
    private $userModel;
    private $auditLog;
    private $userService;
    private $authService;
    
    public function __construct() {
        $this->userModel = new User();
        $this->auditLog = new UserAuditLog();
        $this->userService = new UserService();
        $this->authService = new AuthService();
        
        // Require super_admin role for all methods
        $this->authService->requireRole('super_admin');
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    /**
     * List all users
     */
    public function index() {
        $role = $_GET['role'] ?? 'all';
        $users = $this->userModel->getAllUsers($role === 'all' ? null : $role);
        $currentUser = $this->authService->getCurrentUser();
        
        require_once __DIR__ . '/../../templates/views/admin/users/index.php';
    }
    
    /**
     * Show create user form
     */
    public function create() {
        require_once __DIR__ . '/../../templates/views/admin/users/create.php';
    }
    
    /**
     * Store new user
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/users/create');
            exit;
        }
        
        try {
            // Validate input
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? 'user',
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'notes' => trim($_POST['notes'] ?? '')
            ];
            
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            // Validation
            if (empty($data['username']) || empty($data['full_name'])) {
                throw new \Exception('Username and full name are required');
            }
            
            if ($password !== $confirmPassword) {
                throw new \Exception('Passwords do not match');
            }
            
            // Create user
            $userId = $this->userService->createUser($data, $password, $_SESSION['user_id']);
            
            $_SESSION['success'] = 'User created successfully';
            header('Location: /admin/users');
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['form_data'] = $_POST;
            header('Location: /admin/users/create');
        }
        exit;
    }
    
    /**
     * Show edit user form
     */
    public function edit($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: /admin/users');
            exit;
        }
        
        $currentUser = $this->authService->getCurrentUser();
        
        require_once __DIR__ . '/../../templates/views/admin/users/edit.php';
    }
    
    /**
     * Update user
     */
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /admin/users');
            exit;
        }
        
        // Verify CSRF
        if (!$this->verifyCsrf()) {
            $_SESSION['error'] = 'Invalid security token';
            header('Location: /admin/users/edit/' . $id);
            exit;
        }
        
        try {
            $currentUser = $this->authService->getCurrentUser();
            
            // Get user
            $user = $this->userModel->getById($id);
            if (!$user) {
                throw new \Exception('User not found');
            }
            
            // Prepare update data
            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'role' => $_POST['role'] ?? $user['role'],
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'notes' => trim($_POST['notes'] ?? '')
            ];
            
            // Prevent self role/status change
            if ($id == $currentUser['id']) {
                $data['role'] = $user['role'];
                $data['is_active'] = $user['is_active'];
            }
            
            // Handle password change
            $newPassword = null;
            if (isset($_POST['change_password']) && $_POST['change_password'] == '1') {
                $newPassword = $_POST['password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';
                
                if ($newPassword !== $confirmPassword) {
                    throw new \Exception('Passwords do not match');
                }
            }
            
            // Update user
            $this->userService->updateUser($id, $data, $newPassword, $_SESSION['user_id']);
            
            $_SESSION['success'] = 'User updated successfully';
            header('Location: /admin/users/edit/' . $id);
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /admin/users/edit/' . $id);
        }
        exit;
    }
    
    /**
     * Delete user (soft delete)
     */
    public function delete($id) {
        header('Content-Type: application/json');
        
        try {
            // Verify CSRF
            if (!$this->verifyCsrf()) {
                throw new \Exception('Invalid security token');
            }
            
            // Prevent self-deletion
            if ($id == $_SESSION['user_id']) {
                throw new \Exception('You cannot delete your own account');
            }
            
            $this->userService->deleteUser($id, $_SESSION['user_id']);
            
            echo json_encode(['success' => true]);
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Unlock user account
     */
    public function unlock($id) {
        header('Content-Type: application/json');
        
        try {
            // Verify CSRF
            if (!$this->verifyCsrf()) {
                throw new \Exception('Invalid security token');
            }
            
            $this->userService->unlockUser($id, $_SESSION['user_id']);
            
            echo json_encode(['success' => true]);
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Check username availability
     */
    public function checkUsername() {
        header('Content-Type: application/json');
        
        $username = $_GET['username'] ?? '';
        $excludeId = $_GET['exclude'] ?? null;
        
        $exists = $this->userModel->usernameExists($username, $excludeId);
        
        echo json_encode(['available' => !$exists]);
    }
    
    /**
     * Sync users from htpasswd
     */
    public function syncHtpasswd() {
        header('Content-Type: application/json');
        
        try {
            // Verify CSRF
            if (!$this->verifyCsrf()) {
                throw new \Exception('Invalid security token');
            }
            
            $result = $this->userService->syncFromHtpasswd();
            
            echo json_encode([
                'success' => true,
                'imported' => $result['imported'],
                'skipped' => $result['skipped']
            ]);
            
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * View user activity log
     */
    public function activity($id) {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $_SESSION['error'] = 'User not found';
            header('Location: /admin/users');
            exit;
        }
        
        $activities = $this->auditLog->getLogsForUser($id);
        
        // This view would need to be created
        // require_once __DIR__ . '/../../templates/views/admin/users/activity.php';
        
        // For now, redirect back
        $_SESSION['info'] = 'Activity log view not yet implemented';
        header('Location: /admin/users');
        exit;
    }
    
    private function verifyCsrf() {
        return isset($_POST['csrf_token']) && 
               isset($_SESSION['csrf_token']) &&
               hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
    }
}
EOF

#1749556535
# Verify the controller was created
#1749556536
ls -la /home/gmpmus/app/src/Controllers/UserManagementController.php
#1749556543
clear
#1749556587
# First, let's check the current Router.php structure
#1749556587
head -50 /home/gmpmus/app/src/Router.php
#1749556587
# Backup the Router
#1749556587
cp /home/gmpmus/app/src/Router.php /home/gmpmus/app/src/Router.php.backup
#1749556587
# Now let's add the user management routes
#1749556587
# We'll insert them after the existing routes
#1749556587
sed -i '/addRoute.*DictationController@downloadAudio/a\
\
        // User Management Routes (Super Admin only)\
        $r->addRoute('"'"'GET'"'"', '"'"'/admin/users'"'"', '"'"'UserManagementController@index'"'"');\
        $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/create'"'"', '"'"'UserManagementController@create'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/store'"'"', '"'"'UserManagementController@store'"'"');\
        $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/edit/{id:\d+}'"'"', '"'"'UserManagementController@edit'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/update/{id:\d+}'"'"', '"'"'UserManagementController@update'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/delete/{id:\d+}'"'"', '"'"'UserManagementController@delete'"'"');\
        $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/activity/{id:\d+}'"'"', '"'"'UserManagementController@activity'"'"');\
        \
        // User Management API Routes\
        $r->addRoute('"'"'GET'"'"', '"'"'/api/users/check-username'"'"', '"'"'UserManagementController@checkUsername'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/api/users/unlock/{id:\d+}'"'"', '"'"'UserManagementController@unlock'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/api/users/sync-htpasswd'"'"', '"'"'UserManagementController@syncHtpasswd'"'"');\
        $r->addRoute('"'"'POST'"'"', '"'"'/api/users/delete/{id:\d+}'"'"', '"'"'UserManagementController@delete'"'"');' /home/gmpmus/app/src/Router.php
#1749556587
# Verify the routes were added
#1749556587
grep -A 15 "User Management Routes" /home/gmpmus/app/src/Router.php
#1749556595
clear
#1749556653
# Let's look for a better insertion point
#1749556653
grep -n "Dictation Routes" /home/gmpmus/app/src/Router.php
#1749556653
# Let's insert after the last dictation route
#1749556653
sed -i '/addRoute.*DictationController@listDictations/a\
\
            // User Management Routes (Super Admin only)\
            $r->addRoute('"'"'GET'"'"', '"'"'/admin/users'"'"', '"'"'UserManagementController@index'"'"');\
            $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/create'"'"', '"'"'UserManagementController@create'"'"');\
            $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/store'"'"', '"'"'UserManagementController@store'"'"');\
            $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/edit/{id:\d+}'"'"', '"'"'UserManagementController@edit'"'"');\
            $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/update/{id:\d+}'"'"', '"'"'UserManagementController@update'"'"');\
            $r->addRoute('"'"'POST'"'"', '"'"'/admin/users/delete/{id:\d+}'"'"', '"'"'UserManagementController@delete'"'"');\
            $r->addRoute('"'"'GET'"'"', '"'"'/admin/users/activity/{id:\d+}'"'"', '"'"'UserManagementController@activity'"'"');\
            \
            // User Management API Routes\
            $r->addRoute('"'"'GET'"'"', '"'"'/api/users/check-username'"'"', '"'"'UserManagementController@checkUsername'"'"');\
            $r->addRoute('"'"'POST'"'"', '"'"'/api/users/unlock/{id:\d+}'"'"', '"'"'UserManagementController@unlock'"'"');\
            $r->addRoute('"'"'POST'"'"', '"'"'/api/users/sync-htpasswd'"'"', '"'"'UserManagementController@syncHtpasswd'"'"');' /home/gmpmus/app/src/Router.php
#1749556653
# Verify the routes were added
#1749556654
grep -A 15 "User Management Routes" /home/gmpmus/app/src/Router.php
#1749556662
clear
#1749556699
# Fix the regex patterns in the routes
#1749556699
sed -i 's/{id:d+}/{id:\\d+}/g' /home/gmpmus/app/src/Router.php
#1749556699
# Verify the fix
#1749556700
grep -A 15 "User Management Routes" /home/gmpmus/app/src/Router.php
#1749556705
# Create the admin users endpoint
#1749556705
mkdir -p /home/gmpmus/app/public-endpoints/admin
#1749556705
cat > /home/gmpmus/app/public-endpoints/admin/users.php << 'EOF'
<?php
// Adjust path since we're now in /app/public-endpoints/admin/
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

require_once APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/src/bootstrap.php';

use App\Controllers\UserManagementController;

$controller = new UserManagementController();

// Simple routing based on URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\/admin\/users\/edit\/(\d+)/', $uri, $matches)) {
    $controller->edit($matches[1]);
} elseif ($uri === '/admin/users/create') {
    $controller->create();
} else {
    $controller->index();
}
EOF

#1749556705
# Set permissions
#1749556705
chmod 644 /home/gmpmus/app/public-endpoints/admin/users.php
#1749556705
# Verify it was created
#1749556706
ls -la /home/gmpmus/app/public-endpoints/admin/users.php
#1749556710
# First, let's check the dashboard structure
#1749556710
grep -n "Practice Administration" /home/gmpmus/app/templates/views/dashboard/index.php
#1749556710
# Look for Provider Management to insert after it
#1749556711
grep -B2 -A2 "Provider Management" /home/gmpmus/app/templates/views/dashboard/index.php
#1749556717
clear
#1749556733
# Let's see more context around Provider Management
#1749556733
grep -A10 "Provider Management" /home/gmpmus/app/templates/views/dashboard/index.php
#1749556733
# Let's also check if there's any session check for super admin
#1749556734
grep -B5 -A5 "is_super_admin" /home/gmpmus/app/templates/views/dashboard/index.php
#1749556737
# First, let's check if the controller file exists
#1749556737
ls -la /home/gmpmus/app/src/Controllers/UserManagementController.php
#1749556737
# Test the route with curl
#1749556737
curl -s -I https://gmpm.us/admin/users -u "$USER:$PASS" | head -10
#1749556737
# Check for any PHP errors
#1749556738
tail -20 /home/gmpmus/public_html/error_log | grep -E "(UserManagement|admin/users)"
#1749556757
# Create the user management JavaScript
#1749556757
cat > /home/gmpmus/public_html/assets/js/user-management.js << 'EOF'
// User Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable if on user list page
    if (document.getElementById('usersTable')) {
        $('#usersTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: -1 } // Disable sorting on actions column
            ]
        });
    }

    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            updateStrengthIndicator(strength);
        });
    }

    // Confirm password validation
    const confirmPasswordInput = document.getElementById('confirm_password');
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (password !== confirmPassword) {
                this.setCustomValidity('Passwords do not match');
                this.classList.add('error');
            } else {
                this.setCustomValidity('');
                this.classList.remove('error');
            }
        });
    }

    // Username availability check
    const usernameInput = document.getElementById('username');
    if (usernameInput && !usernameInput.readOnly) {
        let checkTimer;
        usernameInput.addEventListener('input', function() {
            clearTimeout(checkTimer);
            const username = this.value.trim();
            
            if (username.length >= 3) {
                checkTimer = setTimeout(() => {
                    checkUsernameAvailability(username);
                }, 500);
            }
        });
    }

    // Delete confirmation
    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Unlock user
    document.querySelectorAll('.unlock-user').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const userId = this.dataset.userId;
            unlockUser(userId);
        });
    });
});

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 12) strength++;
    if (password.length >= 16) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    return strength;
}

function updateStrengthIndicator(strength) {
    const indicator = document.getElementById('passwordStrength');
    const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const strengthClass = ['very-weak', 'weak', 'fair', 'good', 'strong', 'very-strong'];
    
    indicator.textContent = strengthText[strength] || 'Very Weak';
    indicator.className = 'password-strength ' + (strengthClass[strength] || 'very-weak');
}

async function checkUsernameAvailability(username) {
    try {
        const response = await fetch(`/api/users/check-username?username=${encodeURIComponent(username)}`);
        const data = await response.json();
        
        const usernameInput = document.getElementById('username');
        const feedback = document.getElementById('usernameFeedback');
        
        if (data.available) {
            usernameInput.classList.remove('error');
            usernameInput.classList.add('success');
            feedback.textContent = 'Username is available';
            feedback.className = 'form-feedback success';
        } else {
            usernameInput.classList.add('error');
            usernameInput.classList.remove('success');
            feedback.textContent = 'Username is already taken';
            feedback.className = 'form-feedback error';
        }
    } catch (error) {
        console.error('Error checking username:', error);
    }
}

async function unlockUser(userId) {
    try {
        const response = await fetch(`/api/users/unlock/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            alert('Failed to unlock user');
        }
    } catch (error) {
        console.error('Error unlocking user:', error);
        alert('An error occurred while unlocking the user');
    }
}

// Sync with htpasswd
document.getElementById('syncHtpasswd')?.addEventListener('click', async function() {
    if (!confirm('This will sync all users from the htpasswd file. Continue?')) {
        return;
    }
    
    try {
        const response = await fetch('/api/users/sync-htpasswd', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        if (data.success) {
            alert(`Sync completed. ${data.imported} users imported, ${data.updated} users updated.`);
            location.reload();
        } else {
            alert('Sync failed: ' + data.message);
        }
    } catch (error) {
        console.error('Error syncing:', error);
        alert('An error occurred during sync');
    }
});
EOF

#1749556757
# Set permissions
#1749556758
chmod 644 /home/gmpmus/public_html/assets/js/user-management.js
#1749556765
clear
#1749556803
# Let's check the beginning of the UserManagementController to see the auth check
#1749556803
head -30 /home/gmpmus/app/src/Controllers/UserManagementController.php
#1749556803
# Let's also check if there's a login route/controller
#1749556803
grep -n "login" /home/gmpmus/app/src/Router.php
#1749556807
# Check the AuthService for requireRole method
#1749556807
grep -B5 -A10 "requireRole" /home/gmpmus/app/src/Services/AuthService.php
#1749556807
# Also check for is_super_admin in AuthService
#1749556808
grep -B5 -A5 "is_super_admin" /home/gmpmus/app/src/Services/AuthService.php
#1749556813
# Create a simple PHP script to check session
#1749556813
cat > /home/gmpmus/public_html/check-session.php << 'EOF'
<?php
session_start();
header('Content-Type: text/plain');

echo "Session contents:\n";
print_r($_SESSION);

echo "\n\nServer auth:\n";
echo "PHP_AUTH_USER: " . ($_SERVER['PHP_AUTH_USER'] ?? 'not set') . "\n";
echo "REMOTE_USER: " . ($_SERVER['REMOTE_USER'] ?? 'not set') . "\n";

// Check database for user
require_once '../app/vendor/autoload.php';
require_once '../app/src/bootstrap.php';

use App\Models\User;

$userModel = new User();
$username = $_SERVER['PHP_AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? null;

if ($username) {
    $user = $userModel->getByUsername($username);
    echo "\n\nDatabase user:\n";
    if ($user) {
        echo "ID: " . $user['id'] . "\n";
        echo "Username: " . $user['username'] . "\n";
        echo "Role: " . $user['role'] . "\n";
        echo "Active: " . $user['is_active'] . "\n";
    } else {
        echo "User not found in database\n";
    }
}
EOF

#1749556813
# Test it
#1749556813
curl -s https://gmpm.us/check-session.php -u "$USER:$PASS"
#1749556813
# Remove the test file
#1749556813
rm /home/gmpmus/public_html/check-session.php
#1749556849
clearclear
#1749556850
clear
#1749556855
# First, let's check if the users table exists and see what users are there
#1749556855
echo "SELECT username, role, is_active FROM users;" | mysql -u gmpmus_gmpmus -p'Hh%p/7}~CI_b' gmpmus_gmpm
#1749556855
# Let's add the gmpmus user to the database as super_admin
#1749556855
echo "INSERT INTO users (username, email, full_name, role, is_active, created_by, notes) 
VALUES ('gmpmus', 'admin@gmpm.us', 'GMPM Administrator', 'super_admin', 1, 1, 'Initial super admin')
ON DUPLICATE KEY UPDATE role = 'super_admin', is_active = 1;" | mysql -u gmpmus_gmpmus -p'Hh%p/7}~CI_b' gmpmus_gmpm
#1749556855
# Verify it was added
#1749556856
echo "SELECT id, username, role, is_active FROM users WHERE username = 'gmpmus';" | mysql -u gmpmus_gmpmus -p'Hh%p/7}~CI_b' gmpmus_gmpm
#1749556901
clear
#1749557813
# Let's update the requireRole method in AuthService
#1749557813
# First, backup the file
#1749557813
cp /home/gmpmus/app/src/Services/AuthService.php /home/gmpmus/app/src/Services/AuthService.php.backup
#1749557813
# Find the line number where requireRole starts
#1749557813
grep -n "public function requireRole" /home/gmpmus/app/src/Services/AuthService.php
#1749557820
clear
#1749558084
# Let's see the current requireRole method
#1749558084
sed -n '114,140p' /home/gmpmus/app/src/Services/AuthService.php
#1749558084
# Now let's create a new version that handles HTTP Basic Auth
#1749558084
cat > /tmp/new_requireRole.txt << 'EOF'
    public function requireRole($minRole) {
        // First try to authenticate via HTTP Basic Auth if not already authenticated
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            $username = $_SERVER['PHP_AUTH_USER'] ?? null;
            if ($username) {
                // Auto-authenticate HTTP Basic Auth users
                $db = Connection::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT * FROM users WHERE username = :username AND is_active = 1");
                $stmt->execute(['username' => $username]);
                $userData = $stmt->fetch(\PDO::FETCH_ASSOC);
                
                if ($userData) {
                    // Set up session
                    $_SESSION['authenticated'] = true;
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['user_role'] = $userData['role'];
                    $_SESSION['is_user'] = true;
                    $_SESSION['is_admin'] = in_array($userData['role'], ['admin', 'super_admin']);
                    $_SESSION['is_super_admin'] = $userData['role'] === 'super_admin';
                    
                    // For backward compatibility
                    if ($_SESSION['is_admin']) {
                        $_SESSION['admin_logged_in'] = true;
                        $_SESSION['admin_username'] = $userData['username'];
                    }
                }
            }
        }
        
        // Now check if authenticated
        if (!isset($_SESSION['authenticated']) || !$_SESSION['authenticated']) {
            header('Location: /login');
            exit;
        }

        $roleHierarchy = [
            'user' => 1,
            'admin' => 2,
            'super_admin' => 3
        ];

        $userRole = $_SESSION['user_role'] ?? 'user';
        $userLevel = $roleHierarchy[$userRole] ?? 1;
        $requiredLevel = $roleHierarchy[$minRole] ?? 1;

        if ($userLevel < $requiredLevel) {
            header('HTTP/1.1 403 Forbidden');
            die('Access denied. Insufficient permissions.');
        }
    }
EOF

#1749558084
# Let's replace the requireRole method
#1749558084
# First, let's find where it ends
#1749558084
grep -n -A20 "public function requireRole" /home/gmpmus/app/src/Services/AuthService.php | grep -n "^[0-9]*-    }" | head -1
#1749558084
# Let's see more context
#1749558085
sed -n '114,145p' /home/gmpmus/app/src/Services/AuthService.php
#1749558096
clear
#1749558112
# First, let's add the necessary use statement at the top of the file if not already there
#1749558112
grep -n "use App\\\\Database\\\\Connection" /home/gmpmus/app/src/Services/AuthService.php
#1749558112
# If not found, let's add it after the namespace
#1749558112
sed -i '3i\use App\\Database\\Connection;' /home/gmpmus/app/src/Services/AuthService.php
#1749558112
# Now let's replace the requireRole method (lines 114-134)
#1749558112
sed -i '114,134d' /home/gmpmus/app/src/Services/AuthService.php
#1749558112
# Insert the new method at line 114
#1749558112
sed -i '113r /tmp/new_requireRole.txt' /home/gmpmus/app/src/Services/AuthService.php
#1749558112
# Verify the changes
#1749558113
sed -n '110,160p' /home/gmpmus/app/src/Services/AuthService.php
#1749558123
# Create a test script to check authentication
#1749558123
cat > /home/gmpmus/public_html/test-auth.php << 'EOF'
<?php
session_start();
require_once '../app/vendor/autoload.php';
require_once '../app/src/bootstrap.php';

use App\Services\AuthService;

$auth = new AuthService();

header('Content-Type: text/plain');

echo "HTTP Auth User: " . ($_SERVER['PHP_AUTH_USER'] ?? 'not set') . "\n";
echo "Session authenticated: " . (isset($_SESSION['authenticated']) ? 'yes' : 'no') . "\n";
echo "Session user_id: " . ($_SESSION['user_id'] ?? 'not set') . "\n";
echo "Session username: " . ($_SESSION['username'] ?? 'not set') . "\n";
echo "Session user_role: " . ($_SESSION['user_role'] ?? 'not set') . "\n";
echo "Session is_super_admin: " . (isset($_SESSION['is_super_admin']) ? ($_SESSION['is_super_admin'] ? 'yes' : 'no') : 'not set') . "\n";

echo "\nTrying requireRole('user'):\n";
try {
    $auth->requireRole('user');
    echo "SUCCESS - User has 'user' role\n";
} catch (Exception $e) {
    echo "FAILED - " . $e->getMessage() . "\n";
}

echo "\nTrying requireRole('super_admin'):\n";
try {
    $auth->requireRole('super_admin');
    echo "SUCCESS - User has 'super_admin' role\n";
} catch (Exception $e) {
    echo "FAILED - " . $e->getMessage() . "\n";
}
EOF

#1749558123
# Test it
#1749558123
curl -s https://gmpm.us/test-auth.php -u "$USER:$PASS"
#1749558123
# Remove test file
#1749558123
rm /home/gmpmus/public_html/test-auth.php
#1749649651
# This backs up everything and shows progress
#1749649651
tar -czvf ~/backups/manual/gmpm_complete_$(date +%Y%m%d_%H%M%S).tar.gz --exclude=backups --exclude='*.log' --exclude='error_log' --exclude='storage/cache/*' --exclude='storage/logs/*' public_html app storage .htpasswds 2>/dev/null
