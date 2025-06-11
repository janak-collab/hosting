<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provider Management - GMPM Admin</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/panel-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>👥 Provider Management</h1>
                <p>Manage provider accounts and access</p>
            </div>
            
            <div class="form-content">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['flash_error'])): ?>
                    <div class="alert alert-error">
                        <?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
                    </div>
                <?php endif; ?>
                
                <div class="form-actions" style="margin-bottom: 2rem;">
                    <a href="/admin/providers/create" class="btn btn-primary">+ Add Provider</a>
                    <a href="/admin" class="btn btn-secondary">← Back to Admin</a>
                </div>
                
                <?php if (empty($providers)): ?>
                    <div class="no-data">
                        No providers found. <a href="/admin/providers/create">Add the first provider</a>.
                    </div>
                <?php else: ?>
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Username</th>
                                    <th>NPI</th>
                                    <th>Locations</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($providers as $provider): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($provider['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($provider['title']); ?></td>
                                        <td><?php echo htmlspecialchars($provider['username']); ?></td>
                                        <td><?php echo htmlspecialchars($provider['npi_number'] ?: 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($provider['locations'] ?: 'None assigned'); ?></td>
                                        <td>
                                            <a href="/admin/providers/deactivate/<?php echo $provider['id']; ?>" 
                                               class="btn btn-secondary btn-sm"
                                               onclick="return confirm('Are you sure you want to deactivate this provider?')">
                                                Deactivate
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
