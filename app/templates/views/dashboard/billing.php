<?php 
$quickActions = $quickActions ?? [];
$sections = $sections ?? [];
$stats = $stats ?? [];
$userRole = $userRole ?? 'Billing Staff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Dashboard - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body data-role="billing">
    <?php include __DIR__ . '/../../partials/header.php'; ?>
    
    <div class="dashboard-container">
        <h2>Billing Dashboard</h2>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value" id="pendingClaims"><?= $stats['pending_claims'] ?? '-' ?></div>
                <div class="stat-label">Pending Claims</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="denialsToday"><?= $stats['denials_today'] ?? '-' ?></div>
                <div class="stat-label">Denials Today</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="pendingForms"><?= $stats['pending_forms'] ?? '-' ?></div>
                <div class="stat-label">Pending Forms</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="newPatients"><?= $stats['new_patients'] ?? '-' ?></div>
                <div class="stat-label">New Patients</div>
            </div>
        </div>
        
        <!-- Quick Actions and Sections similar to clinical -->
        <!-- ... rest of the template ... -->
    </div>
    
    <script src="/assets/js/dashboard.js"></script>
</body>
</html>
