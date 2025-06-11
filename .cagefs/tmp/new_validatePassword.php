    public function validatePassword($password) {
        // Check if password is null or empty first
        if (empty($password)) {
            return false;
        }
        
        // At least 12 characters
        if (strlen($password) < 12) {
            return false;
        }
        
        // At least one uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // At least one lowercase
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // At least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // At least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }
