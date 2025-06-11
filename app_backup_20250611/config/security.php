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
