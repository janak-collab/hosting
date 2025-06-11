#!/bin/bash

# Create create.php
cat > /home/gmpmus/app/templates/views/admin/users/create.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <style>
        .password-strength {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: calc(var(--radius) * 0.5);
            font-size: 0.875rem;
        }
        
        .password-strength.weak {
            background: #fee;
            color: var(--error-color);
        }
        
        .password-strength.medium {
            background: #fef3c7;
            color: #92400e;
        }
        
        .password-strength.strong {
            background: #d1fae5;
            color: #065f46;
        }
        
        .password-requirements {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        
        .requirement.met {
            color: var(--success-color);
        }
        
        .requirement-icon {
            width: 16px;
            height: 16px;
        }
        
        .username-check {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .username-check.available {
            color: var(--success-color);
        }
        
        .username-check.unavailable {
            color: var(--error-color);
        }
        
        .role-option {
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .role-option:hover {
            border-color: var(--primary-color);
        }
        
        .role-option input[type="radio"] {
            margin-right: 0.5rem;
        }
        
        .role-option.selected {
            border-color: var(--primary-color);
            background: rgba(242, 101, 34, 0.05);
        }
        
        .role-description {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
            margin-left: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>➕ Create New User</h1>
                <p>Add a new user to the system</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/admin/users/store" id="createUserForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            Username <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input"
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['username'] ?? ''); ?>"
                            required
                            maxlength="50"
                            pattern="[a-zA-Z0-9_-]+"
                            title="Username can only contain letters, numbers, underscores, and hyphens"
                        >
                        <div id="usernameCheck" class="username-check"></div>
                        <div class="form-error" id="usernameError"></div>
                    </div>
                    
                    <!-- Full Name -->
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            class="form-input"
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['full_name'] ?? ''); ?>"
                            required
                            maxlength="100"
                        >
                    </div>
                    
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input"
                            value="<?php echo htmlspecialchars($_SESSION['form_data']['email'] ?? ''); ?>"
                            maxlength="255"
                        >
                        <small class="form-help">Optional - used for password recovery and notifications</small>
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input"
                            required
                            minlength="12"
                        >
                        <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                        <div class="password-requirements">
                            <div class="requirement" id="req-length">
                                <span class="requirement-icon">❌</span>
                                At least 12 characters
                            </div>
                            <div class="requirement" id="req-uppercase">
                                <span class="requirement-icon">❌</span>
                                One uppercase letter
                            </div>
                            <div class="requirement" id="req-lowercase">
                                <span class="requirement-icon">❌</span>
                                One lowercase letter
                            </div>
                            <div class="requirement" id="req-number">
                                <span class="requirement-icon">❌</span>
                                One number
                            </div>
                            <div class="requirement" id="req-special">
                                <span class="requirement-icon">❌</span>
                                One special character
                            </div>
                        </div>
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            Confirm Password <span class="required">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="form-input"
                            required
                            minlength="12"
                        >
                        <div class="form-error" id="confirmError"></div>
                    </div>
                    
                    <!-- Role -->
                    <div class="form-group">
                        <label class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <div class="role-options">
                            <div class="role-option" onclick="selectRole(this, 'user')">
                                <label>
                                    <input type="radio" name="role" value="user" checked>
                                    <strong>User</strong>
                                </label>
                                <div class="role-description">
                                    Basic access to forms and dashboard. Can submit forms and view own submissions.
                                </div>
                            </div>
                            
                            <div class="role-option" onclick="selectRole(this, 'admin')">
                                <label>
                                    <input type="radio" name="role" value="admin">
                                    <strong>Admin</strong>
                                </label>
                                <div class="role-description">
                                    Manage tickets, phone notes, and view reports. Cannot manage users or system settings.
                                </div>
                            </div>
                            
                            <div class="role-option" onclick="selectRole(this, 'super_admin')">
                                <label>
                                    <input type="radio" name="role" value="super_admin">
                                    <strong>Super Admin</strong>
                                </label>
                                <div class="role-description">
                                    Full system access including user management, provider management, and system configuration.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Status -->
                    <div class="form-group">
                        <label class="form-label">
                            <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                            Active Account
                        </label>
                        <small class="form-help">Inactive users cannot log in</small>
                    </div>
                    
                    <!-- Notes -->
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            Notes
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            class="form-textarea"
                            rows="3"
                            maxlength="500"
                        ><?php echo htmlspecialchars($_SESSION['form_data']['notes'] ?? ''); ?></textarea>
                        <small class="form-help">Optional - internal notes about this user</small>
                    </div>
                    
                    <!-- Actions -->
                    <div class="form-actions">
                        <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            Create User
                        </button>
                    </div>
                </form>
                
                <?php unset($_SESSION['form_data']); ?>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
    
    <script>
    // Password strength checker
    const passwordInput = document.getElementById('password');
    const passwordStrength = document.getElementById('passwordStrength');
    const confirmInput = document.getElementById('confirm_password');
    const confirmError = document.getElementById('confirmError');
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        let feedback = '';
        
        // Check requirements
        const hasLength = password.length >= 12;
        const hasUpper = /[A-Z]/.test(password);
        const hasLower = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        // Update requirement indicators
        updateRequirement('req-length', hasLength);
        updateRequirement('req-uppercase', hasUpper);
        updateRequirement('req-lowercase', hasLower);
        updateRequirement('req-number', hasNumber);
        updateRequirement('req-special', hasSpecial);
        
        // Calculate strength
        if (hasLength) strength++;
        if (hasUpper) strength++;
        if (hasLower) strength++;
        if (hasNumber) strength++;
        if (hasSpecial) strength++;
        
        // Show strength indicator
        if (password.length > 0) {
            passwordStrength.style.display = 'block';
            if (strength < 3) {
                passwordStrength.className = 'password-strength weak';
                passwordStrength.textContent = 'Weak password';
            } else if (strength < 5) {
                passwordStrength.className = 'password-strength medium';
                passwordStrength.textContent = 'Medium strength';
            } else {
                passwordStrength.className = 'password-strength strong';
                passwordStrength.textContent = 'Strong password';
            }
        } else {
            passwordStrength.style.display = 'none';
        }
        
        // Check confirm password if it has value
        if (confirmInput.value) {
            checkPasswordMatch();
        }
    });
    
    function updateRequirement(id, met) {
        const req = document.getElementById(id);
        const icon = req.querySelector('.requirement-icon');
        if (met) {
            req.classList.add('met');
            icon.textContent = '✅';
        } else {
            req.classList.remove('met');
            icon.textContent = '❌';
        }
    }
    
    // Password match checker
    confirmInput.addEventListener('input', checkPasswordMatch);
    
    function checkPasswordMatch() {
        if (confirmInput.value !== passwordInput.value) {
            confirmError.textContent = 'Passwords do not match';
            confirmError.style.display = 'block';
            confirmInput.classList.add('error');
        } else {
            confirmError.textContent = '';
            confirmError.style.display = 'none';
            confirmInput.classList.remove('error');
        }
    }
    
    // Username availability checker
    const usernameInput = document.getElementById('username');
    const usernameCheck = document.getElementById('usernameCheck');
    let checkTimer;
    
    usernameInput.addEventListener('input', function() {
        clearTimeout(checkTimer);
        const username = this.value.trim();
        
        if (username.length < 3) {
            usernameCheck.textContent = '';
            return;
        }
        
        usernameCheck.textContent = 'Checking availability...';
        usernameCheck.className = 'username-check';
        
        checkTimer = setTimeout(() => {
            fetch(\`/api/users/check-username?username=\${encodeURIComponent(username)}\`)
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        usernameCheck.textContent = '✓ Username available';
                        usernameCheck.className = 'username-check available';
                        usernameInput.classList.remove('error');
                    } else {
                        usernameCheck.textContent = '✗ Username already taken';
                        usernameCheck.className = 'username-check unavailable';
                        usernameInput.classList.add('error');
                    }
                })
                .catch(error => {
                    usernameCheck.textContent = '';
                });
        }, 500);
    });
    
    // Role selection
    function selectRole(element, role) {
        document.querySelectorAll('.role-option').forEach(opt => {
            opt.classList.remove('selected');
        });
        element.classList.add('selected');
        element.querySelector('input[type="radio"]').checked = true;
    }
    
    // Initialize selected role
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRole = document.querySelector('input[name="role"]:checked');
        if (checkedRole) {
            checkedRole.closest('.role-option').classList.add('selected');
        }
    });
    
    // Form validation
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
        // Check password match
        if (passwordInput.value !== confirmInput.value) {
            e.preventDefault();
            alert('Passwords do not match');
            confirmInput.focus();
            return false;
        }
        
        // Check username availability
        if (usernameInput.classList.contains('error')) {
            e.preventDefault();
            alert('Username is not available');
            usernameInput.focus();
            return false;
        }
    });
    </script>
</body>
</html>
EOF

echo "Created create.php"
