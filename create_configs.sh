#!/bin/bash
# GMPM Create Configuration Files
# Run from /home/gmpmus/

echo "Creating configuration files..."

# Create app.php config
cat > app/config/app.php << 'EOF'
<?php
return [
    'name' => 'Greater Maryland Pain Management',
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'timezone' => 'America/New_York',
    'url' => env('APP_URL', 'https://gmpm.us'),
    
    'locations' => [
        'Catonsville',
        'Edgewater',
        'Elkridge',
        'Glen Burnie',
        'Leonardtown',
        'Odenton',
        'Prince Frederick'
    ]
];
EOF

# Create database.php config
cat > app/config/database.php << 'EOF'
<?php
return [
    'driver' => 'mysql',
    'host' => env('DB_HOST', 'localhost'),
    'database' => env('DB_NAME', ''),
    'username' => env('DB_USERNAME', ''),
    'password' => env('DB_PASSWORD', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];
EOF

# Create security.php config
cat > app/config/security.php << 'EOF'
<?php
return [
    'ip_whitelist' => [
        '98.233.204.84' => 'Catonsville Office',
        '173.67.39.5' => 'Edgewater Office',
        '68.134.39.4' => 'Elkridge Office',
        '71.244.156.161' => 'Glen Burnie Office',
        '68.134.31.125' => 'Home',
        '24.245.103.202' => 'Leonardtown Office',
        '72.81.228.74' => 'Odenton Office',
        '73.39.186.209' => 'Prince Frederick Office',
        '65.181.111.128' => 'Server'
    ],
    
    'rate_limit' => [
        'max_attempts' => 5,
        'window_minutes' => 5
    ],
    
    'session' => [
        'lifetime' => 120,
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]
];
EOF

# Create providers.php config (simplified for now)
cat > app/config/providers.php << 'EOF'
<?php
return [
    [
        'name' => 'Dr. Ahmed Abbas',
        'display_name' => 'Dr. Ahmed Abbas',
        'email' => 'provider1@gmpm.us',
        'phone' => '410-555-0001',
        'active' => true
    ],
    [
        'name' => 'Dr. Provider Two',
        'display_name' => 'Dr. Provider Two',
        'email' => 'provider2@gmpm.us',
        'phone' => '410-555-0002',
        'active' => true
    ],
    // Add more providers as needed
];
EOF

echo "Configuration files created!"
echo ""
echo "Config files created:"
echo "  - app/config/app.php"
echo "  - app/config/database.php"
echo "  - app/config/security.php"
echo "  - app/config/providers.php"
