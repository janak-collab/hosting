<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Notes - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <style>
        .notes-table {
            width: 100%;
            background: var(--card-background);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .notes-table th {
            background: var(--secondary-color);
            color: white;
            padding: 1rem;
            text-align: left;
        }
        
        .notes-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .status-new {
            background: #e3f2fd;
            color: #1565c0;
        }
        
        .status-reviewed {
            background: #f3e5f5;
            color: #6a1b9a;
        }
        
        .status-closed {
            background: #e8f5e9;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📞 Phone Notes</h1>
                <p>View and manage phone messages</p>
            </div>
            
            <div class="form-content">
                <div style="margin-bottom: 2rem; text-align: right;">
                    <a href="/phone-note.php" class="btn btn-primary">+ New Phone Note</a>
                </div>
                
                <?php if (empty($notes)): ?>
                    <div class="info-box">
                        No phone notes found. <a href="/phone-note.php">Create your first 
phone note</a>.
                    </div>
                <?php else: ?>
                    <table class="notes-table">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Patient</th>
                                <th>Provider</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notes as $note): ?>
                            <tr>
                                <td><?php echo date('m/d/Y g:i A', strtotime($note['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($note['patient_name']); ?></strong><br>
                                    <small>DOB: <?php echo date('m/d/Y', strtotime($note['dob'])); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($note['provider']); ?></td>
                                <td><?php echo htmlspecialchars($note['location']); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $note['status']; ?>">
                                        <?php echo ucfirst($note['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/admin/phone-notes/view.php?id=<?php echo $note['id']; ?>" class="btn btn-secondary btn-sm">View</a>
                                    <a href="/admin/phone-notes/print.php?id=<?php echo $note['id']; ?>" target="_blank" class="btn btn-secondary btn-sm">Print</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
</body>
</html>
