-- Create roles table
CREATE TABLE IF NOT EXISTS roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create user_roles junction table
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INT,
    role_id INT,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by VARCHAR(100),
    PRIMARY KEY (user_id, role_id),
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

-- Insert default roles
INSERT INTO roles (name, display_name, description) VALUES
('front_desk', 'Front Desk Staff', 'Handles patient check-in, scheduling, and basic administrative tasks'),
('clinical', 'Clinical Staff', 'Medical assistants and nurses who work with patients'),
('billing', 'Billing Staff', 'Handles insurance claims, patient billing, and financial tasks'),
('admin', 'Administrator', 'Full system access and administrative functions'),
('provider', 'Healthcare Provider', 'Doctors and practitioners');

-- Add role column to existing users table if not exists
ALTER TABLE users ADD COLUMN IF NOT EXISTS primary_role VARCHAR(50) DEFAULT 'front_desk';
