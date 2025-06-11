<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Tickets - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Support Tickets</h1>
                <p>View your submitted tickets</p>
            </div>
            <div class="form-content">
                <div class="info-box">
                    <p>To view support tickets, please <a href="/admin/login">log in as admin</a>.</p>
                </div>
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary">Back to Portal</a>
                    <a href="/it-support" class="btn btn-primary">Submit New Ticket</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
