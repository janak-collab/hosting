-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255),
    full_name VARCHAR(100),
    role ENUM('super_admin', 'admin', 'user') DEFAULT 'user',
    is_active BOOLEAN DEFAULT 1,
    failed_attempts INT DEFAULT 0,
    locked_until TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    notes TEXT,
    INDEX idx_username (username),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
);

-- User audit log table
CREATE TABLE IF NOT EXISTS user_audit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    performed_by INT,
    action ENUM('created', 'updated', 'deleted', 'password_changed', 
                'role_changed', 'locked', 'unlocked', 'login_failed'),
    old_value VARCHAR(255),
    new_value VARCHAR(255),
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Insert jvidyarthi as super_admin if not exists
INSERT INTO users (username, full_name, role, is_active) 
VALUES ('jvidyarthi', 'J Vidyarthi', 'super_admin', 1)
ON DUPLICATE KEY UPDATE role = 'super_admin';
