<!DOCTYPE html>
<html>
<head>
    <title>System Status - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>System Status</h1>
            </div>
            <div class="form-content">
                <table style="width: 100%; border-collapse: collapse;">
                    <?php foreach($checks as $check => $status): ?>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 0.75rem; font-weight: 600;"><?php echo htmlspecialchars($check); ?></td>
                        <td style="padding: 0.75rem; text-align: right; color: <?php echo strpos($status, 'Missing') === false ? 'var(--success-color)' : 'var(--error-color)'; ?>">
                            <?php echo htmlspecialchars($status); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <div class="form-actions" style="margin-top: 2rem;">
                    <a href="/" class="btn btn-primary">Back to Portal</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
