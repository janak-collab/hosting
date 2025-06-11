<?php
require_once 'config.php';

/**
 * Sanitizes input data to prevent XSS attacks
 * 
 * @param mixed $data The input data to sanitize (string or array)
 * @return mixed Sanitized data
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Validates a date string against a specified format
 * 
 * @param string $date The date string to validate
 * @param string $format The expected date format (default: 'Y-m-d')
 * @return bool True if date is valid, false otherwise
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Format date from YYYY-mm-dd to mm-dd-YYYY
 * 
 * @param string $date Date string in YYYY-mm-dd format
 * @return string Date in mm-dd-YYYY format, or empty string if invalid
 */
function formatDate($date) {
    if (empty($date)) return '';
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d ? $d->format('m-d-Y') : '';
}

/**
 * Generates a unique order number
 * 
 * @return string Formatted order number (e.g., ORD-20240107-ab123)
 */
function generateOrderNumber() {
    return 'ORD-' . date('Ymd') . '-' . substr(uniqid(), -5);
}

/**
 * Formats medication data with warnings and instructions
 * 
 * @param array $medications Array of medications to format
 * @return array Formatted medication data with warnings and instructions
 */
function formatMedications($medications) {
    $formatted = [];
    foreach ($medications as $type => $meds) {
        foreach ($meds as $med) {
            $formatted[] = [
                'name' => $med,
                'warnings' => getMedicationWarnings($med),
                'instructions' => getMedicationInstructions($med)
            ];
        }
    }
    return $formatted;
}

/**
 * Gets warning information for a specific medication
 * 
 * @param string $medication Name of the medication
 * @return string Warning message for the medication
 */
function getMedicationWarnings($medication) {
    $warnings = [
        'Nabumetone' => 'Should not be taken more than 2 times per day.',
        'Celebrex/celecoxib' => 'Take with food to prevent stomach upset.',
        'Meloxicam' => 'May increase risk of heart attack or stroke.',
        'Flexeril' => 'May cause drowsiness. Do not drive until you know how this medication affects you.',
        'Skelaxin' => 'Take with food for better absorption.',
        'Methocarbamol' => 'May cause dizziness or drowsiness.',
        'Baclofen' => 'Do not stop taking suddenly without consulting your doctor.',
        'Cymbalta/duloxetine' => 'May cause nausea or dizziness initially. Take with food.',
        'Lyrica/pregabalin' => 'May cause drowsiness or dizziness. Use caution when driving.',
        'Gabapentin' => 'May cause drowsiness. Start at lowest dose and increase as tolerated.',
        'Gabapentin titration' => 'Follow titration schedule exactly as prescribed.'
    ];
    
    return $warnings[$medication] ?? '';
}

/**
 * Gets dosage instructions for a specific medication
 * 
 * @param string $medication Name of the medication
 * @return string Dosage instructions for the medication
 */
function getMedicationInstructions($medication) {
    $instructions = [
        'Nabumetone' => 'Take with food twice daily.',
        'Celebrex/celecoxib' => 'Take once daily with food.',
        'Meloxicam' => 'Take once daily.',
        'Flexeril' => 'Take at bedtime initially. May increase to twice daily if needed.',
        'Skelaxin' => 'Take 2-3 times daily as needed.',
        'Methocarbamol' => 'Take 3-4 times daily as needed.',
        'Baclofen' => 'Take 3 times daily, evenly spaced.',
        'Cymbalta/duloxetine' => 'Take in the morning with or without food.',
        'Lyrica/pregabalin' => 'Take first dose at bedtime, then as prescribed.',
        'Gabapentin' => 'Take with food. Increase dose gradually as directed.',
        'Gabapentin titration' => 'Follow provided titration schedule carefully.'
    ];
    
    return $instructions[$medication] ?? '';
}

function formatImagingSelection($part, $type, $side) {
    $formattedType = $type === 'xray' ? 'XR' : 'MRI';
    $formattedSide = ucfirst($side);
    return "$part $formattedType $formattedSide";
}

function formatSpineImaging($part, $type) {
    $formattedType = $type === 'xray' ? 'XR' : 'MRI';
    return ucfirst($part) . " spine $formattedType";
}

function formatQutenzaInstructions($side, $area) {
    $instructions = [
        'side' => ucfirst($side),
        'area' => [
            'top' => 'Top of Feet',
            'bottom' => 'Bottom of Feet',
            'both' => 'Top and Bottom of Feet',
            'flank' => 'Flank'
        ][$area] ?? ''
    ];
    
    return $instructions;
}
?>