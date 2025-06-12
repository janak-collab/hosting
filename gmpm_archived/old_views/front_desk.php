<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMPM Dashboard</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Greater Maryland Pain Management</h1>
                <p>Staff Portal Dashboard</p>
            </div>
            
            <div class="form-content">
                <p>Welcome, <?php echo $_SERVER['PHP_AUTH_USER'] ?? 'User'; ?>!</p>
                
                <div style="display: grid; gap: 1rem; margin-top: 2rem;">
                    <a href="/phone-note" class="btn btn-primary">📞 Phone Note Form</a>
                    <a href="/it-support" class="btn btn-secondary">💻 IT Support Request</a>
                    <a href="/view-tickets" class="btn btn-secondary">📋 View Tickets</a>
                    <a href="/admin" class="btn btn-secondary">🔧 Admin Area</a>
                </div>
                
                <?php if (isset($stats)): ?>
                <div class="stats-grid" style="margin-top: 2rem;">
                    <div class="stat-card">
                        <h3>Today's Phone Notes</h3>
                        <p class="stat-value"><?php echo $stats['todayPhoneNotes'] ?? 0; ?></p>
                    </div>
                    <div class="stat-card">
                        <h3>Open IT Tickets</h3>
                        <p class="stat-value"><?php echo $stats['openTickets'] ?? 0; ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>
