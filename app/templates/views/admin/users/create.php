<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .password-strength {
            margin-top: 0.5rem;
            padding: 0.5rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
        }
        
        .strength-weak {
            background: #fee;
            color: #c53030;
        }
        
        .strength-fair {
            background: #fef3c7;
            color: #d97706;
        }
        
        .strength-good {
            background: #d1fae5;
            color: #065f46;
        }
        
        .strength-strong {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .password-requirements {
            background: #f7fafc;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .requirement.met {
            color: var(--success-color);
        }
        
        .requirement.unmet {
            color: var(--error-color);
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
                <div id="alertContainer"></div>
                
                <form id="createUserForm" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">
                                Username <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input"
                                required
                                maxlength="50"
                                pattern="[a-zA-Z0-9_-]+"
                                title="Username can only contain letters, numbers, underscore and hyphen"
                            >
                            <div class="form-error" id="usernameError"></div>
                            <small class="form-text">Letters, numbers, underscore and hyphen only</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="form-input"
                                maxlength="255"
                            >
                            <div class="form-error" id="emailError"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name" class="form-label">
                            Full Name <span class="required">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="full_name" 
                            name="full_name" 
                            class="form-input"
                            required
                            maxlength="100"
                        >
                        <div class="form-error" id="fullNameError"></div>
                    </div>
                    
                    <div class="form-row">
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
                            <div class="form-error" id="passwordError"></div>
                            <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                        </div>
                        
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
                            >
                            <div class="form-error" id="confirmPasswordError"></div>
                        </div>
                    </div>
                    
                    <div class="password-requirements">
                        <strong>Password Requirements:</strong>
                        <div class="requirement unmet" id="req-length">
                            <span>⚬</span> At least 12 characters
                        </div>
                        <div class="requirement unmet" id="req-uppercase">
                            <span>⚬</span> At least 1 uppercase letter
                        </div>
                        <div class="requirement unmet" id="req-lowercase">
                            <span>⚬</span> At least 1 lowercase letter
                        </div>
                        <div class="requirement unmet" id="req-special">
                            <span>⚬</span> At least 1 special character (!@#$%^&*(),.?":{}|<>)
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="role" class="form-label">
                            Role <span class="required">*</span>
                        </label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="user">User (Default)</option>
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                        <div class="form-error" id="roleError"></div>
                        <small class="form-text">
                            <strong>User:</strong> Access to forms and dashboard<br>
                            <strong>Admin:</strong> Access to tickets and phone notes<br>
                            <strong>Super Admin:</strong> Full system access including user management
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            Notes
                        </label>
                        <textarea 
                            id="notes" 
                            name="notes" 
                            class="form-textarea"
                            rows="3"
                            placeholder="Optional notes about this user..."
                        ></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="btnText">Create User</span>
                            <span id="btnSpinner" class="spinner" style="display: none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/admin/users">Back to User Management</a></p>
        </div>
    </div>
    
    <script src="/assets/js/user-management.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            userManagement.initCreateForm();
        });
    </script>
</body>
</html>
