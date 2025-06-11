#!/bin/bash

# Create index.php (User List View)
cat > /home/gmpmus/app/templates/views/admin/users/index.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/panel-styles.css">
    <style>
        .user-actions {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .btn-small {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn-danger {
            background: var(--error-color);
            color: white;
            border: none;
        }
        
        .btn-danger:hover {
            background: #c53030;
        }
        
        .btn-warning {
            background: var(--warning-color);
            color: white;
            border: none;
        }
        
        .btn-warning:hover {
            background: #b7791f;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        
        .locked-badge {
            background: var(--error-color);
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        
        .role-badge {
            background: var(--primary-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-badge.user {
            background: #718096;
        }
        
        .role-badge.admin {
            background: #4299e1;
        }
        
        .role-badge.super_admin {
            background: #9f7aea;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        
        .status-indicator.active {
            background: var(--success-color);
        }
        
        .status-indicator.inactive {
            background: var(--error-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>👥 User Management</h1>
                <p>Manage system users and permissions</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Action Bar -->
                <div class="table-header">
                    <h2 class="section-title">System Users</h2>
                    <div class="table-filters">
                        <a href="?role=all" class="filter-link <?php echo (!isset($_GET['role']) || $_GET['role'] === 'all') ? 'active' : ''; ?>">All Users</a>
                        <a href="?role=user" class="filter-link <?php echo ($_GET['role'] ?? '') === 'user' ? 'active' : ''; ?>">Users</a>
                        <a href="?role=admin" class="filter-link <?php echo ($_GET['role'] ?? '') === 'admin' ? 'active' : ''; ?>">Admins</a>
                        <a href="?role=super_admin" class="filter-link <?php echo ($_GET['role'] ?? '') === 'super_admin' ? 'active' : ''; ?>">Super Admins</a>
                        <a href="/admin/users/create" class="btn btn-primary btn-small">+ Add User</a>
                    </div>
                </div>
                
                <!-- Users Table -->
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span class="user-avatar"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></span>
                                        <div>
                                            <strong><?php echo htmlspecialchars($user['full_name'] ?: $user['username']); ?></strong>
                                            <?php if ($user['is_locked']): ?>
                                                <span class="locked-badge">LOCKED</span>
                                            <?php endif; ?>
                                            <br>
                                            <small style="color: var(--text-secondary);">@<?php echo htmlspecialchars($user['username']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($user['email'] ?: 'Not set'); ?></td>
                                <td>
                                    <span class="role-badge <?php echo $user['role']; ?>">
                                        <?php echo str_replace('_', ' ', $user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-indicator <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>"></span>
                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                    <?php if ($user['recent_failures'] > 0): ?>
                                        <br><small style="color: var(--error-color);"><?php echo $user['recent_failures']; ?> failed login(s)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $user['updated_at'] ? date('m/d/Y g:i A', strtotime($user['updated_at'])) : 'Never'; ?>
                                </td>
                                <td>
                                    <div class="user-actions">
                                        <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-secondary btn-small">Edit</a>
                                        <a href="/admin/users/activity/<?php echo $user['id']; ?>" class="btn btn-secondary btn-small">Activity</a>
                                        
                                        <?php if ($user['is_locked']): ?>
                                            <button onclick="unlockUser(<?php echo $user['id']; ?>)" class="btn btn-warning btn-small">Unlock</button>
                                        <?php endif; ?>
                                        
                                        <?php if ($user['id'] != $currentUser['id'] && $user['is_active']): ?>
                                            <button onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')" class="btn btn-danger btn-small">Deactivate</button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="no-data">
                                    No users found.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer Actions -->
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary">← Back to Dashboard</a>
                    <button onclick="syncFromHtpasswd()" class="btn btn-secondary">Sync from htpasswd</button>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
    
    <script>
    function deleteUser(userId, username) {
        if (!confirm(\`Are you sure you want to deactivate user '\${username}'? They will no longer be able to log in.\`)) {
            return;
        }
        
        fetch(\`/api/users/delete/\${userId}\`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'csrf_token=<?php echo $_SESSION['csrf_token']; ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
    
    function unlockUser(userId) {
        if (!confirm('Unlock this user account?')) {
            return;
        }
        
        fetch(\`/api/users/unlock/\${userId}\`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'csrf_token=<?php echo $_SESSION['csrf_token']; ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
    
    function syncFromHtpasswd() {
        if (!confirm('Sync users from htpasswd file? This will import any missing users.')) {
            return;
        }
        
        alert('This feature will be implemented in the next phase.');
    }
    </script>
</body>
</html>
EOF

echo "Created index.php"
