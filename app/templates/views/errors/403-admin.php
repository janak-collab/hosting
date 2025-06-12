<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Access Required - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>🔒 Administrator Access Required</h1>
            </div>
            <div class="form-content">
                <div class="alert alert-error">
                    <strong>Access Denied</strong><br>
                    You need administrator privileges to access this area.
                </div>
                
                <p>Current user: <strong><?php echo htmlspecialchars($_SERVER['PHP_AUTH_USER'] ?? 'Unknown'); ?></strong></p>
                
                <p>If you believe you should have access, please contact IT support.</p>
                
                <div class="form-actions">
                    <a href="/" class="btn btn-primary">← Back to Dashboard</a>
                    <a href="/it-support" class="btn btn-secondary">Contact IT Support</a>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>
