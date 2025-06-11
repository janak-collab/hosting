<?php
require_once 'config.php';

/**
 * Core validation functions for medications and other form data
 */

/**
 * Gets medication category field name for validation errors
 * 
 * @param string $medicationName Medication name
 * @return string Category form field name
 */
function getMedicationCategoryField($medicationName) {
    if (!isset(MEDICATION_DETAILS[$medicationName])) {
        return '';
    }
    
    $category = MEDICATION_DETAILS[$medicationName]['category'];
    foreach (MEDICATION_CATEGORIES_INFO as $categoryKey => $info) {
        if ($categoryKey === $category) {
            return $info['form_field'];
        }
    }
    return '';
}

/**
 * Validates if a medication exists in configuration
 * 
 * @param string $medicationName Medication to check
 * @return bool True if medication exists
 */
function validateMedicationExists($medicationName) {
    return isset(MEDICATION_DETAILS[$medicationName]);
}

/**
 * Validates medication belongs to category
 * 
 * @param string $medicationName Medication name
 * @param string $categoryKey Category to check
 * @return bool True if medication belongs to category
 */
function validateMedicationCategory($medicationName, $categoryKey) {
    if (!validateMedicationExists($medicationName)) {
        return false;
    }
    return MEDICATION_DETAILS[$medicationName]['category'] === $categoryKey;
}

/**
 * Validates medication dose
 * 
 * @param string $medicationName Medication name
 * @param string $dose Dose to validate
 * @return bool True if dose is valid
 */
function validateMedicationDose($medicationName, $dose) {
    if (!validateMedicationExists($medicationName)) {
        return false;
    }
    return in_array($dose, MEDICATION_DETAILS[$medicationName]['doses'] ?? []);
}

/**
 * Validates medication frequency
 * 
 * @param string $medicationName Medication name
 * @param string $frequency Frequency to validate
 * @return bool True if frequency is valid
 */
function validateMedicationFrequency($medicationName, $frequency) {
    if (!validateMedicationExists($medicationName)) {
        return false;
    }
    return in_array($frequency, MEDICATION_DETAILS[$medicationName]['frequencies'] ?? []);
}

/**
 * Validates titration duration
 * 
 * @param string $medicationName Medication name
 * @param string $duration Duration to validate
 * @return bool True if duration is valid
 */
function validateMedicationDuration($medicationName, $duration) {
    if (!validateMedicationExists($medicationName)) {
        return false;
    }
    return in_array($duration, MEDICATION_DETAILS[$medicationName]['durations'] ?? []);
}

/**
 * Validates all medication selections and details
 * 
 * @param array $data Form data
 * @return array Array of validation errors
 */
function validateMedications($data) {
    $errors = [];
    
    foreach (MEDICATION_CATEGORIES_INFO as $categoryKey => $categoryInfo) {
        $fieldName = $categoryInfo['form_field'];
        
        if (!empty($data[$fieldName])) {
            $medicationName = $data[$fieldName];
            
            // Validate medication exists
            if (!validateMedicationExists($medicationName)) {
                $errors[$fieldName] = "Invalid {$categoryInfo['display_name']} medication selected";
                continue;
            }
            
            // Get medication-specific validation errors
            $medErrors = validateMedicationDetails($data, $medicationName);
            $errors = array_merge($errors, $medErrors);
        }
    }
    
    return $errors;
}

/**
 * Validates details for a specific medication
 * 
 * @param array $data Form data
 * @param string $medicationName Medication name
 * @return array Array of validation errors
 */
function validateMedicationDetails($data, $medicationName) {
    $errors = [];
    
    // Special handling for Gabapentin titration
    if ($medicationName === 'Gabapentin titration') {
        if (empty($data['gabapentin_titration']) || 
            !validateMedicationDuration($medicationName, $data['gabapentin_titration'])) {
            $errors['nerve_agent_duration'] = 'Invalid titration duration selected';
        }
        return $errors;
    }
    
    $categoryField = getMedicationCategoryField($medicationName);
    $baseName = getMedicationBaseName($medicationName);
    
    // Validate dose
    $doseKey = "{$baseName}_dose";
    if (empty($data[$doseKey]) || !validateMedicationDose($medicationName, $data[$doseKey])) {
        $errors["{$categoryField}_dose"] = "Invalid dose selected for " . getMedicationDisplayName($medicationName);
    }
    
    // Validate frequency
    $freqKey = "{$baseName}_frequency";
    if (empty($data[$freqKey]) || !validateMedicationFrequency($medicationName, $data[$freqKey])) {
        $errors["{$categoryField}_frequency"] = "Invalid frequency selected for " . getMedicationDisplayName($medicationName);
    }
    
    return $errors;
}

// Other existing validation functions remain unchanged
function validatePatientInfo($data) {
    // ... existing code ...
}

function validateDate($date, $format = 'Y-m-d') {
    // ... existing code ...
}

function validateQutenza($data) {
    // ... existing code ...
}

function validateImaging($data) {
    // ... existing code ...
}
?>