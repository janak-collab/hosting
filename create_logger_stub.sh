#!/bin/bash
# Create temporary Logger stub to prevent errors

echo "Creating temporary Logger stub..."

mkdir -p app/src/Services

cat > app/src/Services/Logger.php << 'EOF'
<?php
namespace App\Services;

/**
 * Temporary Logger stub - will be replaced in Step 4
 */
class Logger {
    private static $instance;
    private $channel = 'app';
    
    public static function channel($channel) {
        $instance = new self();
        $instance->channel = $channel;
        return $instance;
    }
    
    public function info($message, $context = []) {
        $this->log('INFO', $message, $context);
    }
    
    public function error($message, $context = []) {
        $this->log('ERROR', $message, $context);
    }
    
    public function warning($message, $context = []) {
        $this->log('WARNING', $message, $context);
    }
    
    public function debug($message, $context = []) {
        $this->log('DEBUG', $message, $context);
    }
    
    private function log($level, $message, $context = []) {
        // Temporary: just use error_log
        $contextStr = empty($context) ? '' : ' ' . json_encode($context);
        error_log("[{$this->channel}] {$level}: {$message}{$contextStr}");
    }
}
EOF

echo "Logger stub created!"

# Also check if PhoneNote model has getStats method
echo ""
echo "Checking PhoneNote model for getStats method..."
if ! grep -q "getStats" app/src/Models/PhoneNote.php 2>/dev/null; then
    echo "Adding getStats method to PhoneNote model..."
    cat >> app/src/Models/PhoneNote.php << 'EOF'

    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new,
                SUM(CASE WHEN status = 'reviewed' THEN 1 ELSE 0 END) as reviewed,
                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) as closed
                FROM {$this->table}";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [
            'total' => 0,
            'new' => 0,
            'reviewed' => 0,
            'closed' => 0
        ];
    }
EOF
fi

# Create missing admin views directory
echo ""
echo "Creating admin views directory..."
mkdir -p app/resources/views/admin

# Create basic admin login view
cat > app/resources/views/admin/login.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - IT Support</title>
    <link rel="stylesheet" href="<?php echo asset('css/app.min.css'); ?>">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>IT Support Admin</h1>
                <p>Sign in to manage support tickets</p>
            </div>
            
            <div class="form-content">
                <?php if ($error = flash('error')): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="<?php echo url('/admin/login'); ?>">
                    <?php echo csrf_field(); ?>
                    
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" id="username" name="username" class="form-input" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                    </div>
                    
                    <div class="form-actions">
                        <a href="<?php echo url('/'); ?>" class="btn btn-secondary">
                            ← Back to Portal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
EOF

echo ""
echo "All stubs and missing files created!"
echo ""
echo "Now you can test the router properly."
