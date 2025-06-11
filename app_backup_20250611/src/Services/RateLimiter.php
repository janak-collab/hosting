<?php
namespace App\Services;

class RateLimiter {
    private $maxAttempts = 5;
    private $windowMinutes = 5;
    
    public function check($identifier) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $key = 'rate_limit_' . md5($identifier);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => time()
            ];
            return true;
        }
        
        $data = $_SESSION[$key];
        
        // Reset if window has passed
        if (time() - $data['first_attempt'] > ($this->windowMinutes * 60)) {
            $_SESSION[$key] = [
                'attempts' => 1,
                'first_attempt' => time()
            ];
            return true;
        }
        
        // Increment attempts
        $_SESSION[$key]['attempts']++;
        
        return $_SESSION[$key]['attempts'] <= $this->maxAttempts;
    }
    
    public function getTimeUntilReset($identifier) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $key = 'rate_limit_' . md5($identifier);
        if (!isset($_SESSION[$key])) {
            return 0;
        }
        
        $timeElapsed = time() - $_SESSION[$key]['first_attempt'];
        $timeRemaining = ($this->windowMinutes * 60) - $timeElapsed;
        
        return max(0, $timeRemaining);
    }
}
