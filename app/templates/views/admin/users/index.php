<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    
    <style>
        .user-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: rgb(21, 128, 61);
        }
        
        .status-inactive {
            background: rgba(156, 163, 175, 0.1);
            color: rgb(75, 85, 99);
        }
        
        .status-locked {
            background: rgba(239, 68, 68, 0.1);
            color: rgb(185, 28, 28);
        }
        
        .user-role {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .role-super_admin {
            background: rgba(139, 92, 246, 0.1);
            color: rgb(91, 33, 182);
        }
        
        .role-admin {
            background: rgba(59, 130, 246, 0.1);
            color: rgb(29, 78, 216);
        }
        
        .role-user {
            background: rgba(156, 163, 175, 0.1);
            color: rgb(75, 85, 99);
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
        
        .btn-edit:hover {
            background: #2563eb;
        }
        
        .btn-delete {
            background: var(--error-color);
            color: white;
        }
        
        .btn-delete:hover {
            background: #dc2626;
        }
        
        .btn-unlock {
            background: var(--warning-color);
            color: white;
        }
        
        .btn-unlock:hover {
            background: #d97706;
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
                <div id="alertContainer"></div>
                
                <!-- Stats Dashboard -->
                <div class="stats-section">
                    <h2 class="section-title">User Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3>Total Users</h3>
                            <p class="stat-value" id="totalUsers">-</p>
                        </div>
                        <div class="stat-card">
                            <h3>Active</h3>
                            <p class="stat-value" id="activeUsers">-</p>
                        </div>
                        <div class="stat-card">
                            <h3>Admins</h3>
                            <p class="stat-value" id="adminUsers">-</p>
                        </div>
                        <div class="stat-card">
                            <h3>Locked</h3>
                            <p class="stat-value" id="lockedUsers">-</p>
                        </div>
                    </div>
                </div>
                
                <!-- Add User Button -->
                <div style="margin-bottom: 2rem; text-align: right;">
                    <a href="/admin/users/create" class="btn btn-primary">
                        + Add New User
                    </a>
                </div>
                
                <!-- Users Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2 class="section-title">System Users</h2>
                        <div class="table-filters">
                            <select id="roleFilter" class="form-select" style="width: auto;">
                                <option value="">All Roles</option>
                                <option value="super_admin">Super Admin</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                            <select id="statusFilter" class="form-select" style="width: auto;">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="locked">Locked</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table id="usersTable">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
                                <tr>
                                    <td colspan="7" style="text-align: center;">Loading users...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div id="pagination" style="margin-top: 2rem; text-align: center;"></div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Dashboard</a></p>
        </div>
    </div>
    
    <!-- User Management JavaScript -->
    <script src="/assets/js/user-management.js"></script>
    <script>
        // Initialize user management
        document.addEventListener('DOMContentLoaded', function() {
            userManagement.init();
        });
    </script>
</body>
</html>
