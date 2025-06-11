<?php
/**
 * Main configuration file for Medical Scribe application
 * 
 * Sets up constants, error reporting, and security headers
 * 
 * @package MedicalScribe
 * @version 1.0
 */

session_start();

// Add this to config.php
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

/**
 * Allowed healthcare providers with their NPI numbers
 */
define('ALLOWED_PROVIDERS', [
    'Janak Vidyarthi, MD NPI #1619170685',
    'Daniel Ezidiegwu, DO NPI #1184159444',
    'Brandi Senior, PA-C NPI #1346415890'
]);

/**
 * Available medications by category with allowed options
 */
define('ALLOWED_MEDICATIONS', [
    'nsaids' => ['nabumetone', 'celecoxib/Celebrex', 'meloxicam'],
    'muscle_relaxers' => ['cyclobenzaprine/Flexeril', 'metaxalone/Skelaxin', 'methocarbamol/Robaxin', 'baclofen'],
    'nerve_agents' => ['duloxetine/Cymbalta', 'pregabalin/Lyrica', 'Gabapentin', 'Gabapentin titration']
]);

/**
 * Available medications doses
 */
define('FLEXERIL_DOSES', ['5mg', '10mg']);
define('METHOCARBAMOL_DOSES', ['500mg', '750mg']);
define('GABAPENTIN_DOSES', ['100mg', '300mg', '600mg']);
define('LYRICA_DOSES', ['25mg', '50mg', '75mg']);
define('CYMBALTA_DOSES', ['20mg', '30mg', '40mg', '60mg']);
define('CELEBREX_DOSES', ['100mg', '200mg']);
define('MELOXICAM_DOSES', ['7.5mg', '15mg']);
define('BACLOFEN_DOSES', ['5mg', '10mg']);
define('SKELAXIN_DOSES', ['400mg', '800mg']);

// Treatment options
define('QUTENZA_OPTIONS', [
    'sides' => ['right', 'left', 'bilateral'],
    'areas' => ['top', 'bottom', 'both', 'flank']
]);

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
?>