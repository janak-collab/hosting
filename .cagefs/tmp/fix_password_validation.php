    private function validatePassword($password) {
        // Check if password is null or empty first
        if (empty($password)) {
            throw new Exception('Password is required');
        }
        
        // Check minimum length
        if (strlen($password) < 12) {
            throw new Exception('Password must be at least 12 characters long');
        }
        
        // Check for required character types
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception('Password must contain at least one uppercase letter');
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception('Password must contain at least one lowercase letter');
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception('Password must contain at least one number');
        }
        
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            throw new Exception('Password must contain at least one special character');
        }
        
        return true;
    }
