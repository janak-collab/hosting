<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $userRole ?> Dashboard - GMPM</title>
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <div class="header-content">
                <h1>Greater Maryland Pain Management</h1>
                <div class="user-info">
                    <span class="role-badge"><?= $userRole ?></span>
                    <span class="username"><?= $_SERVER['PHP_AUTH_USER'] ?? 'User' ?></span>
                    <a href="/logout" class="logout-btn">Logout</a>
                </div>
            </div>
        </header>
        
        <nav class="dashboard-nav">
            <a href="/" class="nav-logo">GMPM Portal</a>
            <div class="nav-time">
                <span id="current-time"></span>
            </div>
        </nav>
        
        <main class="dashboard-main">
            <?php $this->yield('content') ?>
        </main>
    </div>
    
    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = 
                now.toLocaleDateString() + ' ' + now.toLocaleTimeString();
        }
        updateTime();
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
