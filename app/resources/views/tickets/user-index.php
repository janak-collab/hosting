<!DOCTYPE html>
<html>
<head>
    <title>My Support Tickets - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>My Support Tickets</h1>
                <p>View your submitted IT support requests</p>
            </div>
            <div class="form-content">
                <div class="info-box">
                    <p>👋 Hello, <?php echo htmlspecialchars($user); ?></p>
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
                
                <?php if (!empty($tickets)): ?>
                    <div class="table-section">
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Ticket #</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets as $ticket): ?>
                                    <tr>
                                        <td>#<?php echo $ticket['id']; ?></td>
                                        <td><?php echo date('m/d/y', strtotime($ticket['created_at'])); ?></td>
                                        <td><?php echo ucfirst($ticket['category'] ?? 'general'); ?></td>
                                        <td>
                                            <span class="priority priority-<?php echo $ticket['priority'] ?? 'normal'; ?>">
                                                <?php echo ucfirst($ticket['priority'] ?? 'normal'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="status status-<?php echo $ticket['status']; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $ticket['status'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo htmlspecialchars(substr($ticket['description'], 0, 50)); ?>...</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary">← Back to Portal</a>
                    <?php if ($canSubmitTicket): ?>
                        <a href="/it-support" class="btn btn-primary">Submit New Ticket</a>
                    <?php endif; ?>
                </div>
                
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">
                        Are you an administrator? <a href="/admin/tickets">Go to Admin Panel</a>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p>Logged in as: <?php echo htmlspecialchars($user); ?></p>
        </div>
    </div>
</body>
</html>
