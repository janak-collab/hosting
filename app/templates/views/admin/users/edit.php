<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .password-section {
            background: #f7fafc;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
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
        
        .audit-log {
            background: #f7fafc;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 2rem;
            font-size: 0.875rem;
        }
        
        .audit-entry {
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-light);
        }
        
        .audit-entry:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>✏️ Edit User</h1>
                <p>Update user information and permissions</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <form id="editUserForm" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <input type="hidden" name="user_id" id="userId" value="<?php echo $user['id'] ?? ''; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="username" class="form-label">
                                Username
                            </label>
                            <input 
                                type="text" 
                                id="username" 
                                name="username" 
                                class="form-input"
                                value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                readonly
                                style="background: #f7fafc; cursor: not-allowed;"
                            >
                            <small class="form-text">Username cannot be changed</small>
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
                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
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
                            value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>"
                            required
                            maxlength="100"
                        >
                        <div class="form-error" id="fullNameError"></div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="role" class="form-label">
                                Role <span class="required">*</span>
                            </label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="user" <?php echo ($user['role'] ?? '') === 'user' ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                <option value="super_admin" <?php echo ($user['role'] ?? '') === 'super_admin' ? 'selected' : ''; ?>>Super Admin</option>
                            </select>
                            <div class="form-error" id="roleError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">
                                Status
                            </label>
                            <div style="margin-top: 0.5rem;">
                                <label class="checkbox-label">
                                    <input 
                                        type="checkbox" 
                                        id="is_active" 
                                        name="is_active" 
                                        value="1"
                                        <?php echo ($user['is_active'] ?? 1) ? 'checked' : ''; ?>
                                    >
                                    <span>Active</span>
                                </label>
                            </div>
                            <small class="form-text">Inactive users cannot log in</small>
                        </div>
                    </div>
                    
                    <div class="password-section">
                        <h3 style="margin-bottom: 1rem;">Change Password</h3>
                        <p style="margin-bottom: 1rem; color: var(--text-secondary);">
                            Leave blank to keep current password
                        </p>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="password" class="form-label">
                                    New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="form-input"
                                    minlength="12"
                                >
                                <div class="form-error" id="passwordError"></div>
                                <div id="passwordStrength" class="password-strength" style="display: none;"></div>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    Confirm New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="confirm_password" 
                                    name="confirm_password" 
                                    class="form-input"
                                >
                                <div class="form-error" id="confirmPasswordError"></div>
                            </div>
                        </div>
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
                        ><?php echo htmlspecialchars($user['notes'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="btnText">Update User</span>
                            <span id="btnSpinner" class="spinner" style="display: none;"></span>
                        </button>
                    </div>
                </form>
                
                <?php if (!empty($user['audit_log'])): ?>
                <div class="audit-log">
                    <h3>Recent Activity</h3>
                    <?php foreach ($user['audit_log'] as $entry): ?>
                    <div class="audit-entry">
                        <strong><?php echo htmlspecialchars($entry['action']); ?></strong> - 
                        <?php echo date('m/d/Y g:i A', strtotime($entry['created_at'])); ?>
                        <?php if ($entry['performed_by_name']): ?>
                            by <?php echo htmlspecialchars($entry['performed_by_name']); ?>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
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
            userManagement.initEditForm();
        });
    </script>
</body>
</html>
