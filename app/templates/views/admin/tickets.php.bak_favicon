<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Admin Panel</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/panel-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>IT Support Admin Panel</h1>
                <p>Manage and track support tickets</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                        Ticket status updated successfully!
                    </div>
                <?php endif; ?>
                
                <!-- Stats Dashboard -->
                <div class="stats-section">
                    <h2 class="section-title">Ticket Overview</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3>Total Tickets</h3>
                            <p class="stat-value"><?php echo $stats['total']; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Open</h3>
                            <p class="stat-value"><?php echo $stats['open']; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>In Progress</h3>
                            <p class="stat-value"><?php echo $stats['in_progress']; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Critical/High</h3>
                            <p class="stat-value"><?php echo $stats['critical'] + $stats['high']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Tickets Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2 class="section-title">Support Tickets</h2>
                        <div class="table-filters">
                            <a href="?status=all" class="filter-link <?php echo ($_GET['status'] ?? 'all') === 'all' ? 'active' : ''; ?>">All Tickets</a>
                            <a href="?status=open" class="filter-link <?php echo ($_GET['status'] ?? '') === 'open' ? 'active' : ''; ?>">Open</a>
                            <a href="?status=in_progress" class="filter-link <?php echo ($_GET['status'] ?? '') === 'in_progress' ? 'active' : ''; ?>">In Progress</a>
                            <a href="?status=resolved" class="filter-link <?php echo ($_GET['status'] ?? '') === 'resolved' ? 'active' : ''; ?>">Resolved</a>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Issue</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td>#<?php echo $ticket['id']; ?></td>
                                    <td><?php echo date('m/d/y g:i A', strtotime($ticket['created_at'])); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['name']); ?></td>
                                    <td><?php echo htmlspecialchars($ticket['location']); ?></td>
                                    <td><?php echo ucfirst($ticket['category'] ?? 'general'); ?></td>
                                    <td>
                                        <span class="priority priority-<?php echo $ticket['priority'] ?? 'normal'; ?>">
                                            <?php echo ucfirst($ticket['priority'] ?? 'normal'); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($ticket['description'], 0, 50)); ?>...</td>
                                    <td>
                                        <span class="status status-<?php echo $ticket['status']; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                                            <select name="status" onchange="this.form.submit()" class="status-select">
                                                <option value="">Change Status</option>
                                                <option value="open">Open</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="resolved">Resolved</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                
                                <?php if (empty($tickets)): ?>
                                <tr>
                                    <td colspan="9" class="no-data">
                                        No tickets found. They will appear here when submitted.
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Logout button -->
                <div class="form-actions">
                    <a href="index.php" class="btn btn-secondary">← Back to Support Form</a>
                    <a href="logout.php" class="btn btn-primary">Logout</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; 2025 Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>