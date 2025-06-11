<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Admin Dashboard</h1>
                <p>Welcome, <?php echo htmlspecialchars($user['username'] ?? 'Admin'); ?>!</p>
            </div>
            
            <div class="form-content">
                <div class="stats-section">
                    <h2>Quick Stats</h2>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <h3>Total Tickets</h3>
                            <p class="stat-value"><?php echo $stats['total_tickets']; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Open Tickets</h3>
                            <p class="stat-value"><?php echo $stats['open_tickets']; ?></p>
                        </div>
                        <div class="stat-card">
                            <h3>Phone Notes</h3>
                            <p class="stat-value"><?php echo $stats['phone_notes']; ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <a href="/admin/tickets" class="btn btn-primary">View Tickets</a>
                    <a href="/admin/phone-notes" class="btn btn-secondary">Phone Notes</a>
                    <a href="/admin/logout" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
