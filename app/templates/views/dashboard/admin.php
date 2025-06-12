<?php
$stats = $stats ?? [];
$userRole = $userRole ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body data-role="admin">
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>GMPM Administrator Dashboard</h1>
                <p>Welcome, <?php echo $_SERVER['PHP_AUTH_USER'] ?? 'Administrator'; ?>!</p>
            </div>

            <div class="form-content">
                <!-- Statistics -->
                <div class="stats-bar" style="margin-bottom: 2rem;">
                    <div class="stat-item">
                        <div class="stat-value" id="activeUsers"><?= $stats['active_users'] ?? '0' ?></div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="openTickets"><?= $stats['open_tickets'] ?? '0' ?></div>
                        <div class="stat-label">Open IT Tickets</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="todayAppointments"><?= $stats['appointments'] ?? '0' ?></div>
                        <div class="stat-label">Total Appointments</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="pendingForms"><?= $stats['pending_forms'] ?? '0' ?></div>
                        <div class="stat-label">System Forms</div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <h3>Quick Actions</h3>
                <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
                    <a href="/phone-note" class="btn btn-primary">📞 Phone Note Form</a>
                    <a href="/it-support" class="btn btn-primary">💻 IT Support Request</a>
                    <a href="/admin/tickets" class="btn btn-primary">📋 View IT Tickets</a>
                    <a href="/ip-address-manager" class="btn btn-secondary">🔒 IP Manager</a>
                </div>

                <!-- Admin Functions -->
                <h3>Administrative Functions</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <!-- User Management -->
                    <div class="section-card">
                        <h4>👥 User Management</h4>
                        <div class="section-links">
                            <a href="/admin/users" class="section-link">→ Manage Users</a>
                            <a href="/admin/roles" class="section-link">→ User Roles</a>
                            <a href="/admin/permissions" class="section-link">→ Permissions</a>
                        </div>
                    </div>

                    <!-- System Settings -->
                    <div class="section-card">
                        <h4>⚙️ System Settings</h4>
                        <div class="section-links">
                            <a href="/admin/settings" class="section-link">→ General Settings</a>
                            <a href="/admin/locations" class="section-link">→ Office Locations</a>
                            <a href="/admin/providers" class="section-link">→ Provider Management</a>
                        </div>
                    </div>

                    <!-- Reports -->
                    <div class="section-card">
                        <h4>📊 Reports & Analytics</h4>
                        <div class="section-links">
                            <a href="/admin/reports/activity" class="section-link">→ Activity Reports</a>
                            <a href="/admin/reports/forms" class="section-link">→ Form Analytics</a>
                            <a href="/admin/reports/usage" class="section-link">→ System Usage</a>
                        </div>
                    </div>

                    <!-- IT Management -->
                    <div class="section-card">
                        <h4>💻 IT Management</h4>
                        <div class="section-links">
                            <a href="/admin/tickets" class="section-link">→ IT Support Tickets</a>
                            <a href="/ip-address-manager" class="section-link">→ IP Access Control</a>
                            <a href="/admin/logs" class="section-link">→ System Logs</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management - Admin Panel</p>
        </div>
    </div>

    <script src="/assets/js/dashboard.js"></script>
</body>
</html>
