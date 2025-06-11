<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - IT Support</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>IT Support Admin</h1>
                <p>Sign in to manage support tickets</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($loginError)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($loginError) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-input" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/it-support.php" class="btn btn-secondary">
                            ← Back to Support Form
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>© <?= date('Y') ?> Greater Maryland Pain Management</p>
        </div>
    </div>
</body>
</html>