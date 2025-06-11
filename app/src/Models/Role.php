<?php
namespace App\Models;

class Role {
    public function getUserRole($username) {
        // Simple role assignment based on username
        // In production, this would query a database
        $roleMap = [
            'admin' => 'admin',
            'billing' => 'billing',
            'clinical' => 'clinical',
            'jvidyarthi' => 'admin'  // Your test user
        ];
        
        return $roleMap[$username] ?? 'front_desk';
    }
}
