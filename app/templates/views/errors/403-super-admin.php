<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
    <title>Super Admin Access Required - GMPM</title>
    <link rel=\"stylesheet\" href=\"/assets/css/app.css\">
</head>
<body>
    <div class=\"container\">
        <div class=\"form-card\">
            <div class=\"form-header\">
                <h1>🔐 Super Administrator Access Required</h1>
            </div>
            <div class=\"form-content\">
                <div class=\"alert alert-error\">
                    <strong>Access Denied</strong><br>
                    This feature requires super administrator privileges.
                </div>
                
                <p>The IP Address Manager is a critical security feature that controls access to the entire system.</p>
                
                <p>Current user: <strong><?php echo htmlspecialchars(\$_SERVER['PHP_AUTH_USER'] ?? 'Unknown'); ?></strong></p>
                
                <div class=\"info-box\">
                    <strong>Note:</strong> Only designated super administrators can manage IP access controls. 
                    This is to prevent accidental lockouts and maintain system security.
                </div>
                
                <div class=\"form-actions\">
                    <a href=\"/\" class=\"btn btn-primary\">← Back to Dashboard</a>
                    <a href=\"/it-support\" class=\"btn btn-secondary\">Request Access</a>
                </div>
            </div>
        </div>
        
        <div class=\"footer\">
            <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>
