-- User Management Schema for GMPM
-- Run this in your MySQL database

-- First, let's check and modify the existing users table
-- Add new columns if they don't exist
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS email VARCHAR(255) AFTER username,
ADD COLUMN IF NOT EXISTS full_name VARCHAR(100) AFTER email,
ADD COLUMN IF NOT EXISTS role ENUM('super_admin', 'admin', 'user') DEFAULT 'user' AFTER full_name,
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT 1 AFTER role,
ADD COLUMN IF NOT EXISTS failed_attempts INT DEFAULT 0 AFTER is_active,
ADD COLUMN IF NOT EXISTS locked_until TIMESTAMP NULL AFTER failed_attempts,
ADD COLUMN IF NOT EXISTS created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER locked_until,
ADD COLUMN IF NOT EXISTS created_by INT AFTER created_at,
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_by,
ADD COLUMN IF NOT EXISTS notes TEXT AFTER updated_at;

-- Create audit log table
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
    INDEX idx_performed_by (performed_by),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
);

-- Update existing admin user to super_admin
UPDATE users SET role = 'super_admin' WHERE username = 'admin';

-- Set jvidyarthi as super_admin if exists
UPDATE users SET role = 'super_admin' WHERE username = 'jvidyarthi';

-- Insert jvidyarthi if doesn't exist
INSERT IGNORE INTO users (username, full_name, role, is_active, created_by)
VALUES ('jvidyarthi', 'System Administrator', 'super_admin', 1, 1);

-- Create initial audit log entry
INSERT INTO user_audit_log (user_id, performed_by, action, new_value, ip_address)
SELECT id, id, 'created', 'Initial setup', '127.0.0.1'
FROM users 
WHERE username IN ('admin', 'jvidyarthi')
AND NOT EXISTS (
    SELECT 1 FROM user_audit_log 
    WHERE user_id = users.id AND action = 'created'
);
