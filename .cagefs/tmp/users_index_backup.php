<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/panel-styles.css">
    <style>
        .user-filters {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .user-table {
            width: 100%;
            background: var(--card-background);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .user-table th {
            background: var(--secondary-color);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .user-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .user-table tr:last-child td {
            border-bottom: none;
        }
        
        .user-table tr:hover {
            background: var(--background-color);
        }
        
        .role-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-super_admin {
            background: #fee;
            color: #c53030;
        }
        
        .role-admin {
            background: #fef3c7;
            color: #92400e;
        }
        
        .role-user {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .role-staff {
            background: #e0e7ff;
            color: #3730a3;
        }
        
        .role-provider {
            background: #d1fae5;
            color: #065f46;
        }
        
        .status-active {
            color: var(--success-color);
        }
        
        .status-inactive {
            color: var(--error-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn-edit {
            background: var(--info-color);
            color: white;
        }
        
        .btn-delete {
            background: var(--error-color);
            color: white;
        }
        
        .search-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }
        
        .search-input {
            width: 100%;
            padding-left: 2.5rem;
        }
        
        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>👥 User Management</h1>
            </div>
            
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>


            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($_SESSION['success']); ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($_SESSION['error']); ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="form-content">
                <p>Manage system users and permissions</p>
            </div>
            
            <div class="form-content">
                <div class="user-filters">
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" id="userSearch" class="form-input search-input" placeholder="Search users...">
                    </div>
                    
                    <select id="roleFilter" class="form-select">
                        <option value="all">All Roles</option>
                        <option value="super_admin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="staff">Staff</option>
                        <option value="provider">Provider</option>
                    </select>
                    
                    <a href="/admin/users/create" class="btn btn-primary">
                        + Add New User
                    </a>
                </div>
                
                <div class="table-container">
                    <table class="user-table" id="userTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 2rem;">
                                        No users found. <a href="/admin/users/create">Create the first user</a>.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr data-role="<?php echo htmlspecialchars($user['role'] ?? ''); ?>">
                                        <td><?php echo $user['id']; ?></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($user['username']); ?></strong>
                                            <?php if ($user['id'] == $currentUser['id']): ?>
                                                <span style="color: var(--primary-color); font-size: 0.75rem;">(You)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['full_name'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($user['email'] ?? '-'); ?></td>
                                        <td>
                                            <span class="role-badge role-<?php echo $user['role'] ?? 'user'; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $user['role'] ?? 'user')); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['is_active']): ?>
                                                <span class="status-active">✓ Active</span>
                                            <?php else: ?>
                                                <span class="status-inactive">✗ Inactive</span>
                                            <?php endif; ?>
                                            
                                            <?php if ($user['locked_until'] && strtotime($user['locked_until']) > time()): ?>
                                                <br><small style="color: var(--error-color);">🔒 Locked</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($user['last_login']) {
                                                echo date('m/d/y g:i A', strtotime($user['last_login']));
                                            } else {
                                                echo '<span style="color: var(--text-secondary);">Never</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="/admin/users/edit/<?php echo $user['id']; ?>" class="btn btn-sm btn-edit">Edit</a>
                                                
                                                <?php if ($user['id'] != $currentUser['id'] && $user['username'] != 'jvidyarthi'): ?>
                                                    <button class="btn btn-sm btn-delete" onclick="deleteUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Delete</button>
                                                <?php endif; ?>
                                                
                                                <?php if ($user['locked_until'] && strtotime($user['locked_until']) > time()): ?>
                                                    <button class="btn btn-sm btn-primary" onclick="unlockUser(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['username']); ?>')">Unlock</button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="form-actions" style="margin-top: 2rem;">
                    <a href="/" class="btn btn-secondary">← Back to Dashboard</a>
                    <div style="text-align: right; flex: 1;">
                        <small style="color: var(--text-secondary);">
                            Total users: <?php echo count($users); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Search functionality
        document.getElementById('userSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#userTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
        
        // Role filter
        document.getElementById('roleFilter').addEventListener('change', function(e) {
            const role = e.target.value;
            const rows = document.querySelectorAll('#userTable tbody tr');
            
            rows.forEach(row => {
                if (role === 'all' || row.dataset.role === role) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Delete user
        function deleteUser(id, username) {
            if (!confirm(`Are you sure you want to delete user "${username}"?`)) {
                return;
            }
            
            fetch(`/api/users/delete/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User deleted successfully');
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to delete user'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
        
        // Unlock user
        function unlockUser(id, username) {
            if (!confirm(`Unlock user "${username}"?`)) {
                return;
            }
            
            fetch(`/api/users/unlock/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('User unlocked successfully');
                    location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to unlock user'));
                }
            })
            .catch(error => {
                alert('Error: ' + error.message);
            });
        }
    </script>
    <script src="/assets/js/user-management.js"></script>
</body>
</html>
