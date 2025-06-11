<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h1>🧪 Test Page</h1>
        </div>
        <div class="form-content">
            <div class="alert alert-success">
                ✓ <?php echo $message; ?>
            </div>
            
            <p>Current time: <?php echo $time; ?></p>
            
            <h3>System Check:</h3>
            <ul>
                <li>PHP Version: <?php echo phpversion(); ?></li>
                <li>Session Active: <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No'; ?></li>
                <li>CSRF Token: <?php echo substr($_SESSION['csrf_token'] ?? 'Not set', 0, 8); ?>...</li>
            </ul>
            
            <div class="form-actions">
                <a href="<?php echo url('/'); ?>" class="btn btn-primary">Back to Portal</a>
                <button onclick="testApi()" class="btn btn-secondary">Test API</button>
            </div>
        </div>
    </div>
</div>

<script>
function testApi() {
    fetch('<?php echo url('/test/json'); ?>')
        .then(response => response.json())
        .then(data => {
            alert('API Response: ' + data.message);
        })
        .catch(error => {
            alert('API Error: ' + error.message);
        });
}
</script>
