<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/panel-styles.css">
    <style>
        .ticket-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            border-bottom: 2px solid var(--border-color);
        }
        
        .tab-button {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            border-bottom: 3px solid transparent;
            cursor: pointer;
            font-weight: 600;
            color: var(--text-secondary);
            transition: var(--transition);
        }
        
        .tab-button.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📋 My Tickets</h1>
                <p>View your submitted requests and phone notes</p>
            </div>
            
            <div class="form-content">
                <div class="ticket-tabs">
                    <button class="tab-button active" onclick="showTab('it-tickets')">
                        IT Support (<?php echo count($itTickets ?? []); ?>)
                    </button>
                    <button class="tab-button" onclick="showTab('phone-notes')">
                        Phone Notes (<?php echo count($phoneNotes ?? []); ?>)
                    </button>
                </div>
                
                <!-- IT Tickets Tab -->
                <div id="it-tickets" class="tab-content active">
                    <?php if (empty($itTickets)): ?>
                        <div class="info-box">
                            You haven't submitted any IT support tickets yet.
                            <br><br>
                            <a href="/it-support" class="btn btn-primary">Submit New Ticket</a>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Issue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($itTickets as $ticket): ?>
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
                    <?php endif; ?>
                </div>
                
                <!-- Phone Notes Tab -->
                <div id="phone-notes" class="tab-content">
                    <?php if (empty($phoneNotes)): ?>
                        <div class="info-box">
                            You haven't created any phone notes yet.
                            <br><br>
                            <a href="/phone-note" class="btn btn-primary">Create Phone Note</a>
                        </div>
                    <?php else: ?>
                        <div class="table-container">
                            <table>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Patient</th>
                                        <th>Provider</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($phoneNotes as $note): ?>
                                    <tr>
                                        <td>#<?php echo $note['id']; ?></td>
                                        <td><?php echo date('m/d/y', strtotime($note['created_at'])); ?></td>
                                        <td><?php echo htmlspecialchars($note['patient_name']); ?></td>
                                        <td><?php echo htmlspecialchars($note['provider']); ?></td>
                                        <td><?php echo htmlspecialchars($note['location']); ?></td>
                                        <td>
                                            <span class="status status-<?php echo $note['status']; ?>">
                                                <?php echo ucfirst($note['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="/admin/phone-notes/print/<?php echo $note['id']; ?>" 
                                               target="_blank" class="btn btn-secondary btn-sm">Print</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-actions" style="margin-top: 2rem;">
                    <a href="/" class="btn btn-secondary">← Back to Portal</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
        </div>
    </div>
    
    <script>
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Remove active from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(tabName).classList.add('active');
            
            // Set active button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
