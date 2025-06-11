<?php
namespace App\Services;

class ValidationService {
    private $errors = [];
    
    /**
     * Validate IT support ticket data
     */
    public function validateTicket($data) {
        $this->errors = [];
        
        // Name validation
        if (empty($data['name'])) {
            $this->errors['name'] = 'Name is required';
        } elseif (strlen($data['name']) < 2) {
            $this->errors['name'] = 'Name must be at least 2 characters';
        } elseif (strlen($data['name']) > 100) {
            $this->errors['name'] = 'Name must be less than 100 characters';
        }
        
        // Location validation
        $validLocations = ['Leonardtown', 'Odenton', 'Prince Frederick', 'Catonsville', 
                          'Edgewater', 'Elkridge', 'Glen Burnie'];
        if (empty($data['location'])) {
            $this->errors['location'] = 'Location is required';
        } elseif (!in_array($data['location'], $validLocations)) {
            $this->errors['location'] = 'Please select a valid location';
        }
        
        // Category validation
        $validCategories = ['hardware', 'software', 'network', 'phone', 'printer', 'email', 'other'];
        if (empty($data['category'])) {
            $this->errors['category'] = 'Category is required';
        } elseif (!in_array($data['category'], $validCategories)) {
            $this->errors['category'] = 'Please select a valid category';
        }
        
        // Priority validation
        $validPriorities = ['low', 'normal', 'high', 'critical'];
        if (!empty($data['priority']) && !in_array($data['priority'], $validPriorities)) {
            $this->errors['priority'] = 'Invalid priority level';
        }
        
        // Description validation
        if (empty($data['description'])) {
            $this->errors['description'] = 'Description is required';
        } elseif (strlen($data['description']) < 10) {
            $this->errors['description'] = 'Please provide more details (at least 10 characters)';
        } elseif (strlen($data['description']) > 2000) {
            $this->errors['description'] = 'Description is too long (max 2000 characters)';
        }
        
        return empty($this->errors);
    }
    
    /**
     * Validate phone note data
     */
    public function validatePhoneNote($data) {
        $this->errors = [];
        
        // Patient name validation
        if (empty($data['patient_name'])) {
            $this->errors['patient_name'] = 'Patient name is required';
        } elseif (strlen($data['patient_name']) < 2) {
            $this->errors['patient_name'] = 'Patient name must be at least 2 characters';
        }
        
        // Date of birth validation
        if (empty($data['dob'])) {
            $this->errors['dob'] = 'Date of birth is required';
        } elseif (!strtotime($data['dob'])) {
            $this->errors['dob'] = 'Invalid date format';
        }
        
        // Phone validation
        if (empty($data['phone'])) {
            $this->errors['phone'] = 'Phone number is required';
        } elseif (!preg_match('/^\d{10}$/', preg_replace('/\D/', '', $data['phone']))) {
            $this->errors['phone'] = 'Phone number must be 10 digits';
        }
        
        // Message validation
        if (empty($data['message'])) {
            $this->errors['message'] = 'Message is required';
        } elseif (strlen($data['message']) < 10) {
            $this->errors['message'] = 'Message must be at least 10 characters';
        }
        
        return empty($this->errors);
    }
    
    /**
     * Get validation errors
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Sanitize input data
     */
    public function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        
        // Remove any null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Strip tags and encode special characters
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email address
     */
    public function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate date
     */
    public function validateDate($date, $format = 'Y-m-d') {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Validate phone number (US format)
     */
    public function validatePhone($phone) {
        // Remove all non-digits
        $phone = preg_replace('/\D/', '', $phone);
        
        // Check if it's 10 digits
        return strlen($phone) === 10;
    }
    
    /**
     * Check if string is alphanumeric with spaces
     */
    public function isAlphanumericSpace($string) {
        return preg_match('/^[a-zA-Z0-9\s]+$/', $string);
    }
    
    /**
     * Set custom error
     */
    public function setError($field, $message) {
        $this->errors[$field] = $message;
    }
    
    /**
     * Clear all errors
     */
    public function clearErrors() {
        $this->errors = [];
    }
}
