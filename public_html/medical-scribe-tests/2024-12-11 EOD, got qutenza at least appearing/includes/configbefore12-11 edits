<?php
/**
 * Main configuration file for Medical Scribe application
 */

// Error reporting configuration
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/error.log');

// Security headers
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Database configuration
define('DB_CONFIG', [
    "host" => "localhost",
    "dbname" => "jvidyart_timecard",
    "username" => "jvidyart_janak",
    "password" => "himabim1",
    "options" => [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
]);

// Application paths and security settings
define('BASE_URL', '/private_area/medical-scribe');
define('INCLUDES_PATH', __DIR__ . '/includes');
define('LOGS_PATH', __DIR__ . '/logs');
define('MAX_FORM_SIZE', 1024 * 1024); // 1MB

// ICD10 Codes
define('ICD10_CODES', [
    'shoulder' => [
        'right' => 'M25.511 (Pain in right shoulder)',
        'left' => 'M25.512 (Pain in left shoulder)',
        'bilateral' => 'M25.519 (Pain in unspecified shoulder)'
    ],
    'hip' => [
        'right' => 'M25.551 (Pain in right hip)',
        'left' => 'M25.552 (Pain in left hip)',
        'bilateral' => 'M25.559 (Pain in unspecified hip)'
    ],
    'knee' => [
        'right' => 'M25.561 (Pain in right knee)',
        'left' => 'M25.562 (Pain in left knee)',
        'bilateral' => 'M25.569 (Pain in unspecified knee)'
    ],
    'SIJ' => [
        'right' => 'M46.1 (Right sacroiliitis, not elsewhere classified)',
        'left' => 'M46.1 (Left sacroiliitis, not elsewhere classified)',
        'bilateral' => 'M46.1 (Bilateral sacroiliitis, not elsewhere classified)'
    ],
    'spine' => [
        'cervical' => [
            'xray' => 'M54.2 (Cervicalgia)',
            'mri' => 'M54.12 (Cervical radiculopathy)'
        ],
        'thoracic' => [
            'xray' => 'M54.6 (Pain in thoracic spine)',
            'mri' => 'M54.14 (Thoracic radiculopathy)'
        ],
        'lumbar' => [
            'xray' => 'M54.50 (Low back pain)',
            'mri' => 'M54.16 (Lumbar radiculopathy)'
        ]
    ]
]);

// Allowed healthcare providers
define('ALLOWED_PROVIDERS', [
    'Janak Vidyarthi, MD NPI #1619170685',
    'Daniel Ezidiegwu, DO NPI #1184159444',
    'Brandi Senior, PA-C NPI #1346415890'
]);

// Medication categories
define('MEDICATION_CATEGORIES_INFO', [
    'anti_inflammatories' => [
        'display_name' => 'Anti-inflammatory',
        'form_field' => 'NSAID'
    ],
    'muscle_relaxers' => [
        'display_name' => 'Muscle Relaxer',
        'form_field' => 'mrelaxer'
    ],
    'nerve_agents' => [
        'display_name' => 'Nerve Agent',
        'form_field' => 'nerve_agent'
    ]
]);

// Medication details
define('MEDICATION_DETAILS', [
    'nabumetone' => [
        'category' => 'anti_inflammatories',
        'doses' => ['250mg', '500mg'],
        'frequencies' => ['qday', 'bid'],
        'warnings' => 'Should not be taken more than 2 times per day.',
        'instructions' => 'Take with food.',
        'display_name' => 'Nabumetone'
    ],
    'celecoxib/Celebrex' => [
        'category' => 'anti_inflammatories',
        'doses' => ['100mg', '200mg'],
        'frequencies' => ['qday'],
        'warnings' => 'Take with food to prevent stomach upset.',
        'instructions' => 'Take once daily with food.',
        'display_name' => 'Celecoxib (Celebrex)'
    ],
    'meloxicam' => [
        'category' => 'anti_inflammatories',
        'doses' => ['7.5mg', '15mg'],
        'frequencies' => ['qday'],
        'warnings' => 'May increase risk of heart attack or stroke.',
        'instructions' => 'Take once daily.',
        'display_name' => 'Meloxicam'
    ],
    'cyclobenzaprine/Flexeril' => [
        'category' => 'muscle_relaxers',
        'doses' => ['5mg', '10mg'],
        'frequencies' => ['qday', 'bid'],
        'warnings' => 'May cause drowsiness. Do not drive until you know how this medication affects you.',
        'instructions' => 'Take at bedtime initially. May increase to twice daily if needed.',
        'display_name' => 'Cyclobenzaprine (Flexeril)'
    ],
    'metaxalone/Skelaxin' => [
        'category' => 'muscle_relaxers',
        'doses' => ['400mg', '800mg'],
        'frequencies' => ['qday', 'bid'],
        'warnings' => 'Take with food for better absorption.',
        'instructions' => 'Take with food.',
        'display_name' => 'Metaxalone (Skelaxin)'
    ],
    'methocarbamol/Robaxin' => [
        'category' => 'muscle_relaxers',
        'doses' => ['500mg', '750mg'],
        'frequencies' => ['qday', 'bid'],
        'warnings' => 'May cause dizziness or drowsiness.',
        'instructions' => 'Take with food.',
        'display_name' => 'Methocarbamol (Robaxin)'
    ],
    'baclofen' => [
        'category' => 'muscle_relaxers',
        'doses' => ['5mg', '10mg'],
        'frequencies' => ['qday', 'bid', 'tid'],
        'warnings' => 'Do not stop taking suddenly without consulting your doctor.',
        'instructions' => 'Take at evenly spaced intervals throughout the day.',
        'display_name' => 'Baclofen'
    ],
    'duloxetine/Cymbalta' => [
        'category' => 'nerve_agents',
        'doses' => ['20mg', '30mg', '40mg', '60mg'],
        'frequencies' => ['qday'],
        'warnings' => 'May cause nausea or dizziness initially.',
        'instructions' => 'Take in the morning with or without food.',
        'display_name' => 'Duloxetine (Cymbalta)'
    ],
    'pregabalin/Lyrica' => [
        'category' => 'nerve_agents',
        'doses' => ['25mg', '50mg', '75mg'],
        'frequencies' => ['qday', 'bid'],
        'warnings' => 'May cause drowsiness or dizziness. Use caution when driving.',
        'instructions' => 'Take first dose at bedtime, then as prescribed.',
        'display_name' => 'Pregabalin (Lyrica)'
    ],
    'Gabapentin' => [
        'category' => 'nerve_agents',
        'doses' => ['100mg', '300mg', '600mg'],
        'frequencies' => ['qhs', 'bid', 'tid'],
        'warnings' => 'May cause drowsiness. Start at lowest dose and increase as tolerated.',
        'instructions' => 'Take with food. Space doses evenly throughout the day.',
        'display_name' => 'Gabapentin'
    ],
    'Gabapentin titration' => [
        'category' => 'nerve_agents',
        'durations' => ['1 week', '2 weeks', '4 weeks'],
        'warnings' => 'Follow titration schedule exactly as prescribed.',
        'instructions' => 'Follow provided titration schedule carefully.',
        'display_name' => 'Gabapentin (Titration)'
    ]
]);

// Frequency labels
define('FREQUENCY_LABELS', [
    'qday' => 'Once daily',
    'bid' => 'Twice daily',
    'tid' => 'Three times daily',
    'qhs' => 'At bedtime'
]);

// Form validation rules
define('VALIDATION_RULES', [
    'patient_name' => [
        'pattern' => "/^[A-Za-z][A-Za-z\-\. ']{1,49}$/",
        'message' => 'Please enter a valid name (2-50 characters, letters, spaces, dots and hyphens only)'
    ],
    'dos' => [
        'days_before' => 30,
        'days_after' => 30,
        'message' => 'Date of service must be within 30 days of today'
    ]
]);

/**
 * Get database connection
 */
function getDbConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_CONFIG['host'] . ";dbname=" . DB_CONFIG['dbname'],
                DB_CONFIG['username'],
                DB_CONFIG['password'],
                DB_CONFIG['options']
            );
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            return null;
        }
    }
    
    return $pdo;
}