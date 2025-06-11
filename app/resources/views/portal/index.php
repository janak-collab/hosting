<!DOCTYPE html>
<html>
<head>
    <title>GMPM Portal</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Greater Maryland Pain Management Portal</h1>
                <p>Internal Staff Portal</p>
            </div>
            <div class="form-content">
                <p>Welcome, <?php echo htmlspecialchars($user); ?>!</p>
                <div style="display: grid; gap: 1rem; margin-top: 2rem;">
                    <a href="/phone-note" class="btn btn-primary">📞 Phone Note Form</a>
                    <a href="/it-support" class="btn btn-secondary">💻 IT Support Request</a>
                    <a href="/view-tickets" class="btn btn-secondary">📋 View Tickets</a>
                    <a href="/admin" class="btn btn-secondary">🔧 Admin Area</a>
                </div>
            </div>
        </div>
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>
