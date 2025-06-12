<?php
/**
 * Admin User Roster
 * 
 * List of usernames (from htpasswd) that have admin privileges
 * These users will automatically see admin features on the dashboard
 */

return [
    'admin_users' => [
        'jvidyarthi',    // Admin user
        'admin',         // Default admin
        'gmpmus',        // System user
        // Add more admin usernames here as needed
    ],
    
    // Role-based permissions (future expansion)
    'user_roles' => [
        'jvidyarthi' => ['admin', 'clinical', 'billing'],
        'admin' => ['admin'],
        // Define specific roles per user if needed
    ]
];
