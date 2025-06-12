<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IP Address Manager - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
<link rel="stylesheet" href="/assets/css/modules/ip-manager.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>🔒 IP Address Manager</h1>
                <p>Manage secure access for office locations</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer">
                    <?php if ($message): ?>
                        <div class="alert alert-success">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-error">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="info-box">
                    💡 <strong>Tip:</strong> Always test access after making changes. If locked out, use cPanel to restore from backup.
                </div>
                
                <div class="server-notice">
                    ⚡ This server runs LiteSpeed. IP blocking uses RewriteRule directives for compatibility.
                </div>
                
                <form id="ipForm" method="POST" action="/ip-address-manager">
                    <input type="hidden" name="csrf_token" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-group">
                        <label class="form-label">
                            Allowed IP Addresses <span class="required">*</span>
                            <small style="font-weight: normal; color: var(--text-secondary);">(sorted alphabetically by location)</small>
                        </label>
                        <div class="ip-grid" id="ipList">
                            <?php foreach ($currentIPs as $index => $ipData): ?>
                                <div class="ip-row">
                                    <span class="row-number">#<?php echo $index + 1; ?></span>
                                    <input 
                                        type="text" 
                                        name="ips[]" 
                                        class="form-input ip-input"
                                        value="<?php echo htmlspecialchars($ipData['ip']); ?>" 
                                        placeholder="IP Address (e.g., 192.168.1.1)"
                                        pattern="^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                                        title="Enter a valid IP address (e.g., 192.168.1.1)"
                                        required
                                        maxlength="15"
                                    >
                                    <input 
                                        type="text" 
                                        name="locations[]" 
                                        class="form-input"
                                        value="<?php echo htmlspecialchars($ipData['location']); ?>" 
                                        placeholder="Location/Office Name"
                                        required
                                        maxlength="100"
                                    >
                                    <button type="button" class="remove-btn" >Remove</button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-error" id="ipError"></div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="addIPBtn" >
                            + Add IP Address
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="btnText">Save Changes</span>
                            <span id="btnSpinner" class="spinner" style="display: none;"></span>
                        </button>
                    </div>
                </form>
                
                <div class="debug-box">
                    <h3>🔍 Current Configuration</h3>
                    <p><strong>Your current IP:</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
                    <p><strong>Logged in as:</strong> <?php echo $_SERVER['PHP_AUTH_USER']; ?></p>
                    <p><strong>Total IPs configured:</strong> <?php echo count($currentIPs); ?></p>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <!-- External JavaScript file for CSP compliance -->
    <script src="/assets/js/app.js"></script>
</body>
</html>
