<?php 
// Ensure variables are set
$quickActions = $quickActions ?? [];
$sections = $sections ?? [];
$stats = $stats ?? [];
$userRole = $userRole ?? 'Clinical Staff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Dashboard - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>
<body data-role="clinical">
    <?php include __DIR__ . '/../../partials/header.php'; ?>
    
    <div class="dashboard-container">
        <h2>Clinical Dashboard</h2>
        
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value" id="pendingVitals"><?= $stats['pending_vitals'] ?? '-' ?></div>
                <div class="stat-label">Pending Vitals</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="pendingProcedures"><?= $stats['pending_procedures'] ?? '-' ?></div>
                <div class="stat-label">Pending Procedures</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="todayAppointments"><?= $stats['appointments'] ?? '-' ?></div>
                <div class="stat-label">Today's Appointments</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="scheduledProcedures"><?= $stats['procedures'] ?? '-' ?></div>
                <div class="stat-label">Scheduled Procedures</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <div class="action-grid">
                <?php foreach ($quickActions as $action): ?>
                <a href="<?= htmlspecialchars($action['url']) ?>" class="quick-btn" style="background-color: <?= $action['color'] ?>">
                    <span class="icon"><?= $action['icon'] ?></span>
                    <span><?= htmlspecialchars($action['title']) ?></span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Sections -->
        <?php foreach ($sections as $key => $section): ?>
        <div class="dashboard-section">
            <h3><?= htmlspecialchars($section['title']) ?></h3>
            <div class="section-links">
                <?php foreach ($section['items'] as $item): ?>
                <a href="<?= htmlspecialchars($item['url']) ?>" class="section-link">
                    → <?= htmlspecialchars($item['name']) ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <script src="/assets/js/dashboard.js"></script>
</body>
</html>
