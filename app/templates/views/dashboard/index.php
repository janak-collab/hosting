<?php
// Role-based access is now handled by the controller
// Variables available from controller:
// $currentUser, $userRole, $isAdmin, $isSuperAdmin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMPM Portal - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/header-styles.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <?php require_once APP_PATH . '/templates/components/header.php'; ?>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Statistics Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value" id="todayAppointments"><?php echo $stats['appointments'] ?? '0'; ?></div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="pendingForms"><?php echo $stats['pending_forms'] ?? '0'; ?></div>
                <div class="stat-label">Pending Forms</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="newPatients"><?php echo $stats['new_patients'] ?? '0'; ?></div>
                <div class="stat-label">New Patients</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="scheduledProcedures"><?php echo $stats['procedures'] ?? '0'; ?></div>
                <div class="stat-label">Procedures Scheduled</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="/phone-note" class="quick-btn">
                    <span>📞</span>
                    <span>Phone Note</span>
                </a>
                <a href="/it-support" class="quick-btn">
                    <span>💻</span>
                    <span>IT Support</span>
                </a>
                <a href="/secure-forms" class="quick-btn">
                    <span>📋</span>
                    <span>Secure Forms</span>
                </a>
                <a href="/dictation" class="quick-btn">
                    <span>🎤</span>
                    <span>Dictation</span>
                </a>
                <?php if ($isAdmin): ?>
                <a href="/admin/tickets" class="quick-btn secondary">
                    <span>🎫</span>
                    <span>IT Tickets</span>
                </a>
                <a href="/admin/phone-notes" class="quick-btn secondary">
                    <span>📝</span>
                    <span>Phone Notes</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($isAdmin && !empty($adminData)): ?>
        <!-- Admin Dashboard -->
        <div class="admin-dashboard">
            <h2>Admin Overview</h2>
            
            <!-- Today's Activity -->
            <div class="activity-summary">
                <h3>Today's Activity</h3>
                <div class="activity-stats">
                    <div class="activity-stat">
                        <span class="activity-value"><?php echo $adminData['todayActivity']['tickets'] ?? 0; ?></span>
                        <span class="activity-label">New Tickets</span>
                    </div>
                    <div class="activity-stat">
                        <span class="activity-value"><?php echo $adminData['todayActivity']['phoneNotes'] ?? 0; ?></span>
                        <span class="activity-label">Phone Notes</span>
                    </div>
                    <div class="activity-stat <?php echo ($adminData['todayActivity']['openTickets'] ?? 0) > 0 ? 'highlight' : ''; ?>">
                        <span class="activity-value"><?php echo $adminData['todayActivity']['openTickets'] ?? 0; ?></span>
                        <span class="activity-label">Open Tickets</span>
                    </div>
                    <div class="activity-stat <?php echo ($adminData['todayActivity']['criticalIssues'] ?? 0) > 0 ? 'urgent' : ''; ?>">
                        <span class="activity-value"><?php echo $adminData['todayActivity']['criticalIssues'] ?? 0; ?></span>
                        <span class="activity-label">Critical Issues</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="recent-section">
                    <h3>Recent IT Tickets</h3>
                    <?php if (!empty($adminData['recentTickets'])): ?>
                        <ul class="recent-list">
                            <?php foreach ($adminData['recentTickets'] as $ticket): ?>
                            <li>
                                <span class="priority-badge priority-<?php echo $ticket['priority'] ?? 'normal'; ?>">
                                    <?php echo ucfirst($ticket['priority'] ?? 'normal'); ?>
                                </span>
                                <span class="item-title"><?php echo htmlspecialchars($ticket['name']); ?></span>
                                <span class="item-meta"><?php echo htmlspecialchars($ticket['location']); ?></span>
                                <span class="item-time"><?php echo date('g:i A', strtotime($ticket['created_at'])); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-data">No recent tickets</p>
                    <?php endif; ?>
                </div>

                <div class="recent-section">
                    <h3>Recent Phone Notes</h3>
                    <?php if (!empty($adminData['recentPhoneNotes'])): ?>
                        <ul class="recent-list">
                            <?php foreach ($adminData['recentPhoneNotes'] as $note): ?>
                            <li>
                                <span class="item-title"><?php echo htmlspecialchars($note['patient_name']); ?></span>
                                <span class="item-meta">For <?php echo htmlspecialchars($note['provider']); ?></span>
                                <span class="item-time"><?php echo date('g:i A', strtotime($note['created_at'])); ?></span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-data">No recent phone notes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Dashboard Grid -->
        <div class="dashboard-grid">
            <!-- Clinical Tools -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">🏥</span>
                    <h3>Clinical Tools</h3>
                </div>
                <div class="section-links">
                    <a href="/secure-forms" class="section-link">
                        <span class="link-icon">→</span>
                        <span>New Patient Forms</span>
                    </a>
                    <a href="/ketamine-assessments" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Ketamine Assessments</span>
                    </a>
                    <a href="/procedure-scheduling" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Procedure Scheduling</span>
                    </a>
                    <a href="/prescription-refill" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Prescription Refills</span>
                    </a>
                    <a href="/telemedicine" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Telemedicine Portal</span>
                    </a>
                    <a href="/pain-diary" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Digital Pain Diary</span>
                    </a>
                </div>
            </div>

            <!-- Documentation -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">📄</span>
                    <h3>Documentation</h3>
                </div>
                <div class="section-links">
                    <a href="/dictation" class="section-link">
                        <span class="link-icon">→</span>
                        <span>AI Dictation System</span>
                        <span class="badge">New</span>
                    </a>
                    <a href="/mri-upload" class="section-link">
                        <span class="link-icon">→</span>
                        <span>MRI Upload Portal</span>
                    </a>
                    <a href="/document-center" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Document Center</span>
                    </a>
                    <a href="/phone-note" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Phone Notes</span>
                    </a>
                    <a href="/templates" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Clinical Templates</span>
                    </a>
                </div>
            </div>

            <!-- Analytics & Reports -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">📊</span>
                    <h3>Analytics & Reports</h3>
                </div>
                <div class="section-links">
                    <a href="/outcome-analytics" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Outcome Analytics</span>
                    </a>
                    <a href="/provider-dashboard" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Provider Dashboard</span>
                    </a>
                    <a href="/referral-tracking" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Referral Tracking</span>
                    </a>
                    <a href="/quality-metrics" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Quality Metrics</span>
                    </a>
                    <a href="/financial-reports" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Financial Reports</span>
                    </a>
                </div>
            </div>

            <!-- Staff Resources -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">👥</span>
                    <h3>Staff Resources</h3>
                </div>
                <div class="section-links">
                    <a href="/staff-directory" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Staff Directory</span>
                    </a>
                    <a href="/education-portal" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Education Portal</span>
                    </a>
                    <a href="/admin/employee" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Employee Resources</span>
                    </a>
                    <a href="/admin/compliance" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Compliance Training</span>
                    </a>
                    <a href="/meeting-scheduler" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Meeting Scheduler</span>
                    </a>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">🛠️</span>
                    <h3>Technical Support</h3>
                </div>
                <div class="section-links">
                    <a href="/it-support" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Submit IT Request</span>
                    </a>
                    <a href="/ehr-bridge" class="section-link">
                        <span class="link-icon">→</span>
                        <span>EHR Integration</span>
                    </a>
                    <a href="/system-status" class="section-link">
                        <span class="link-icon">→</span>
                        <span>System Status</span>
                    </a>
                    <a href="/backup-portal" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Backup Portal</span>
                    </a>
                    <a href="/help-docs" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Help Documentation</span>
                    </a>
                </div>
            </div>

            <!-- Practice Administration -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">⚙️</span>
                    <h3>Practice Administration</h3>
                </div>
                <div class="section-links">
                    <?php if ($isAdmin): ?>
                    <a href="/admin/tickets" class="section-link">
                        <span class="link-icon">→</span>
                        <span>IT Support Tickets</span>
                    </a>
                    <a href="/admin/phone-notes" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Phone Notes Review</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($isSuperAdmin): ?>
                    <a href="/ip-address-manager" class="section-link">
                        <span class="link-icon">→</span>
                        <span>IP Address Manager</span>
                        <span class="badge">DONE!</span>
                    </a>
                    <a href="/admin/providers" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Provider Management</span>
                    </a>
                    <a href="/admin/users" class="section-link">
                        <span class="link-icon">→</span>
                        <span>User Management</span>
                        <span class="badge">New</span>
                    </a>
                    <a href="/admin/logs" class="section-link">
                        <span class="link-icon">→</span>
                        <span>System Logs</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (!$isAdmin): ?>
                    <p style="padding: 1rem; color: var(--text-secondary); font-size: 0.9rem;">
                        Administrative features require elevated permissions.
                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="dashboard-footer">
            <p>Greater Maryland Pain Management Portal • <?php echo date('Y'); ?></p>
            <p>Logged in as: <?php echo htmlspecialchars($currentUser); ?> (<?php echo ucfirst($userRole); ?>)</p>
        </div>
    </div>

    <!-- JavaScript for live updates -->
    <script src="/assets/js/dashboard.js"></script>
</body>
</html>
