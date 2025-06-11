<?php
require_once 'config.php';

/**
 * Helper functions for form display and data formatting
 */

/**
 * Gets base name for medication form fields
 * 
 * @param string $medicationName Full medication name
 * @return string Base name for form fields
 */
function getMedicationBaseName($medicationName) {
    // Handle special cases first
    if ($medicationName === 'Gabapentin titration') {
        return 'gabapentin_titration';
    }
    
    // Remove any brand names after slash
    if (strpos($medicationName, '/') !== false) {
        $parts = explode('/', $medicationName);
        $medicationName = trim($parts[0]);
    }
    
    // Convert to lowercase and replace spaces with underscores
    return strtolower(str_replace(' ', '_', $medicationName));
}

/**
 * Gets medication display name
 * 
 * @param string $medicationName Medication name
 * @return string Display name
 */
function getMedicationDisplayName($medicationName) {
    return MEDICATION_DETAILS[$medicationName]['display_name'] ?? $medicationName;
}

/**
 * Gets full medication details formatted for display
 * 
 * @param array $data Form data
 * @param string $medicationName Medication name
 * @return array|null Formatted medication details
 */
function getMedicationFullDetails($data, $medicationName) {
    $details = [];
    $baseName = getMedicationBaseName($medicationName);
    
    // Get medication configuration
    if (!isset(MEDICATION_DETAILS[$medicationName])) return null;
    $medConfig = MEDICATION_DETAILS[$medicationName];
    
    // Basic details
    $details['name'] = getMedicationDisplayName($medicationName);
    $details['warnings'] = $medConfig['warnings'] ?? '';
    $details['instructions'] = $medConfig['instructions'] ?? '';
    
    // Handle Gabapentin titration specially
    if ($medicationName === 'Gabapentin titration') {
        $duration = $data[$baseName] ?? '';
        $details['duration'] = $duration;
        return $details;
    }
    
    // Get dose and frequency
    $doseKey = "{$baseName}_dose";
    $freqKey = "{$baseName}_frequency";
    
    $details['dose'] = $data[$doseKey] ?? '';
    $details['frequency'] = formatFrequency($data[$freqKey] ?? '');
    
    return $details;
}

/**
 * Format frequency for display
 */
function formatFrequency($frequency) {
    return FREQUENCY_LABELS[$frequency] ?? $frequency;
}

/**
 * Check if any medications are selected
 */
function hasAnyMedication($data) {
    foreach (MEDICATION_CATEGORIES_INFO as $category) {
        $fieldName = $category['form_field'];
        if (!empty($data[$fieldName])) {
            return true;
        }
    }
    return false;
}

/**
 * Format date for display
 */
function formatDate($date) {
    if (empty($date)) return '';
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d ? $d->format('m-d-Y') : '';
}

/**
 * Format provider name for display
 */
function formatProviderName($fullProviderString) {
    $parts = explode('NPI', $fullProviderString);
    return trim($parts[0]);
}

/**
 * Format imaging selection
 */
function formatImagingSelection($part, $type, $side) {
    $formattedType = $type === 'xray' ? 'XR' : 'MRI';
    $formattedSide = ucfirst($side);
    return "$part $formattedType $formattedSide";
}

/**
 * Format spine imaging description
 */
function formatSpineImaging($part, $type) {
    $formattedType = $type === 'xray' ? 'XR' : 'MRI';
    return ucfirst($part) . " spine $formattedType";
}

/**
 * Process form submission
 */
function processFormSubmission($data) {
    // Validate CSRF token first
    if (!validateCsrfToken()) {
        return ['error' => 'Invalid security token'];
    }

    // Validate required fields
    $requiredFields = ['patient_name', 'dob', 'dos', 'provider_name'];
    foreach ($requiredFields as $field) {
        if (empty($data[$field])) {
            return ['error' => ucfirst(str_replace('_', ' ', $field)) . ' is required'];
        }
    }

    return ['success' => true, 'data' => $data];
}

// Add session handling if not already present
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>