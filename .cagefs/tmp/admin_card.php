
            <?php if ($isAdmin): ?>
            <!-- Practice Administration -->
            <div class="section-card">
                <div class="section-header">
                    <span class="section-icon">⚙️</span>
                    <h3>Practice Administration</h3>
                </div>
                <div class="section-links">
                    <a href="/secure-admin/ip-manager.php" class="section-link">
                        <span class="link-icon">→</span>
                        <span>IP Address Manager</span>
                        <span class="badge">Security</span>
                    </a>
                    <a href="/admin/users" class="section-link">
                        <span class="link-icon">→</span>
                        <span>User Management</span>
                    </a>
                    <a href="/admin/reports" class="section-link">
                        <span class="link-icon">→</span>
                        <span>Reports & Analytics</span>
                    </a>
                    <a href="/admin/settings" class="section-link">
                        <span class="link-icon">→</span>
                        <span>System Settings</span>
                    </a>
                    <a href="/admin/phone-notes" class="section-link">
                        <span class="link-icon">→</span>
                        <span>All Phone Notes</span>
                    </a>
                    <a href="/admin/tickets" class="section-link">
                        <span class="link-icon">→</span>
                        <span>All IT Tickets</span>
                    </a>
                </div>
            </div>
            <?php endif; ?>
