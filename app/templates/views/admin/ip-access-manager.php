<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Access Manager - GMPM Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/modules/ip-manager.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/admin">GMPM Admin</a>
            <div class="d-flex">
                <a href="/admin" class="btn btn-sm btn-outline-light me-2">Dashboard</a>
                <a href="/admin/logout" class="btn btn-sm btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>🔒 IP Access Manager</h1>
        <p>Manage secure access for office locations</p>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Add New IP Address</h5>
            </div>
            <div class="card-body">
                <form action="/admin/ip-access-manager/add" method="POST" class="row g-3">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                    
                    <div class="col-md-4">
                        <label for="ip_address" class="form-label">IP Address</label>
                        <input type="text" name="ip_address" id="ip_address" class="form-control" 
                               placeholder="192.168.1.1" pattern="^(\d{1,3}\.){3}\d{1,3}$" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="location" class="form-label">Location/Office Name</label>
                        <input type="text" name="location" id="location" class="form-control" 
                               placeholder="Main Office, Remote Worker, etc." required>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block w-100">Add IP</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Current IP Rules</h5>
                <?php 
                $dbCount = count($rules ?? []);
                $htCount = count($htaccessIPs ?? []);
                $inSync = ($dbCount === $htCount);
                ?>
                <span class="badge bg-<?= $inSync ? 'success' : 'warning' ?>">
                    DB: <?= $dbCount ?> | .htaccess: <?= $htCount ?>
                </span>
            </div>
            <div class="card-body">
                <?php if (empty($rules)): ?>
                    <p>No IP rules configured in database.</p>
                    <?php if (!empty($htaccessIPs)): ?>
                        <div class="alert alert-warning">
                            Found <?= count($htaccessIPs) ?> IPs in .htaccess. 
                            <a href="/admin/ip-access-manager/import" class="btn btn-sm btn-primary">Import from .htaccess</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Location</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rules as $rule): ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars($rule['ip_address']) ?></code></td>
                                        <td><?= htmlspecialchars($rule['location'] ?? $rule['description']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $rule['access_type'] === 'allow' ? 'success' : 'danger' ?>">
                                                <?= ucfirst($rule['access_type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $rule['is_active'] ? 'success' : 'secondary' ?>">
                                                <?= $rule['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td><?= date('Y-m-d', strtotime($rule['created_at'])) ?></td>
                                        <td>
                                            <form action="/admin/ip-access-manager/delete/<?= $rule['id'] ?>" method="POST" style="display:inline">
                                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Delete this IP?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <?php if (!$inSync): ?>
                            <form action="/admin/ip-access-manager/sync" method="POST" style="display:inline">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token ?? '') ?>">
                                <button type="submit" class="btn btn-warning">🔄 Sync to .htaccess</button>
                            </form>
                        <?php endif; ?>
                        <a href="/admin/ip-access-manager/backup" class="btn btn-secondary">📥 Download .htaccess Backup</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="mt-4">
            <p class="text-muted">
                <strong>Server Info:</strong> <?= htmlspecialchars($serverInfo['server_software'] ?? 'Unknown') ?> | 
                <strong>Your IP:</strong> <?= htmlspecialchars($serverInfo['current_ip'] ?? $_SERVER['REMOTE_ADDR']) ?>
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
