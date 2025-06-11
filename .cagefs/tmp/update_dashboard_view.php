<?php
$file = '/home/gmpmus/app/templates/views/dashboard/index.php';
$content = file_get_contents($file);

// Add admin badge after username in header
$search = 'Welcome, <?php echo htmlspecialchars($_SERVER[\'PHP_AUTH_USER\'] ?? \'User\'); ?>';
$replace = 'Welcome, <?php echo htmlspecialchars($_SERVER[\'PHP_AUTH_USER\'] ?? \'User\'); ?><?php if ($isAdmin): ?> <span class="admin-badge">Admin</span><?php endif; ?>';
$content = str_replace($search, $replace, $content);

// Add admin logout button in user profile section
$search = '</div>
                </div>
            </div>
        </div>
    </header>';
$replace = '</div>
                    <?php if ($isAdmin): ?>
                    <a href="/admin/logout" class="logout-btn">Logout Admin</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>';
$content = str_replace($search, $replace, $content);

// Add admin stats section after regular stats
$search = '</div>

        <!-- Quick Actions -->';
$replace = '</div>

        <?php if ($isAdmin && !empty($adminData[\'todayActivity\'])): ?>
        <!-- Admin Statistics -->
        <div class="admin-section">
            <h2>📊 Admin Dashboard - Today\'s Activity</h2>
            <div class="admin-stats">
                <div class="admin-stat-item">
                    <div class="admin-stat-value"><?php echo $adminData[\'todayActivity\'][\'tickets\']; ?></div>
                    <div class="admin-stat-label">New Tickets</div>
                </div>
                <div class="admin-stat-item">
                    <div class="admin-stat-value"><?php echo $adminData[\'todayActivity\'][\'phoneNotes\']; ?></div>
                    <div class="admin-stat-label">Phone Notes</div>
                </div>
                <div class="admin-stat-item">
                    <div class="admin-stat-value"><?php echo $adminData[\'todayActivity\'][\'openTickets\']; ?></div>
                    <div class="admin-stat-label">Open Tickets</div>
                </div>
                <div class="admin-stat-item">
                    <div class="admin-stat-value"><?php echo $adminData[\'todayActivity\'][\'criticalIssues\']; ?></div>
                    <div class="admin-stat-label">Critical/High</div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Quick Actions -->';
$content = str_replace($search, $replace, $content);

// Add admin-only quick actions
$search = '<a href="/dictation" class="quick-btn">
                    <span>📝</span>
                    <span>Dictation</span>
               </a>';
$replace = '<a href="/dictation" class="quick-btn">
                    <span>📝</span>
                    <span>Dictation</span>
               </a>
               <?php if ($isAdmin): ?>
               <a href="/admin/phone-notes" class="quick-btn admin-only">
                    <span>📋</span>
                    <span>All Phone Notes</span>
               </a>
               <a href="/secure-admin/ip-manager.php" class="quick-btn admin-only">
                    <span>🔒</span>
                    <span>IP Manager</span>
               </a>
               <?php endif; ?>';
$content = str_replace($search, $replace, $content);

// Save the updated file
file_put_contents($file, $content);
echo "Dashboard view updated successfully!\n";
