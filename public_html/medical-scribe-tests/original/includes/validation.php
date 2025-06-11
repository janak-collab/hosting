<?php
require_once 'config.php';

/**
 * Validates patient information form data
 * 
 * @param array $data Associative array containing patient form data
 * @return array Array of validation errors, empty if no errors
 */
function validatePatientInfo($data) {
    $errors = [];
    
    // Patient name validation
    if (empty($data['patient_name'])) {
        $errors['patient_name'] = 'Patient name is required';
    } elseif (!preg_match("/^[a-zA-Z. -]{2,50}$/", $data['patient_name'])) {
        $errors['patient_name'] = 'Invalid patient name format';
    }
    
    // DOB validation
    if (empty($data['dob'])) {
        $errors['dob'] = 'Date of birth is required';
    } elseif (!validateDate($data['dob'])) {
        $errors['dob'] = 'Invalid date of birth format';
    } else {
        $dob = new DateTime($data['dob']);
        $today = new DateTime();
        if ($dob > $today) {
            $errors['dob'] = 'Date of birth cannot be in the future';
        }
    }
    
    // DOS validation
    if (empty($data['dos'])) {
        $errors['dos'] = 'Date of service is required';
    } elseif (!validateDate($data['dos'])) {
        $errors['dos'] = 'Invalid date of service format';
    }
    
    // Provider validation
    if (empty($data['provider_name']) || !in_array($data['provider_name'], ALLOWED_PROVIDERS)) {
        $errors['provider_name'] = 'Invalid provider selected';
    }
    
    return $errors;
}

/**
 * Validates medication selections and dosages
 * 
 * @param array $data Associative array containing medication form data
 * @return array Array of validation errors, empty if no errors
 */
function validateMedications($data) {
    $errors = [];
    
    // Validate NSAIDs
    if (!empty($data['NSAID'])) {
        foreach ($data['NSAID'] as $nsaid) {
            if (!in_array($nsaid, ALLOWED_MEDICATIONS['nsaids'])) {
                $errors['nsaid'] = 'Invalid NSAID selected';
                break;
            }
        }
    }
    
    // Validate muscle relaxers and their doses
    if (!empty($data['mrelaxer'])) {
        foreach ($data['mrelaxer'] as $relaxer) {
            if (!in_array($relaxer, ALLOWED_MEDICATIONS['muscle_relaxers'])) {
                $errors['mrelaxer'] = 'Invalid muscle relaxer selected';
                break;
            }
            
            // Validate doses if selected
            if ($relaxer === 'Flexeril' && !empty($data['flexeril_dose'])) {
                if (!in_array($data['flexeril_dose'], FLEXERIL_DOSES)) {
                    $errors['flexeril_dose'] = 'Invalid Flexeril dose';
                }
            }
            
            if ($relaxer === 'Methocarbamol' && !empty($data['methocarbamol_dose'])) {
                if (!in_array($data['methocarbamol_dose'], METHOCARBAMOL_DOSES)) {
                    $errors['methocarbamol_dose'] = 'Invalid Methocarbamol dose';
                }
            }
        }
    }
    
        // Validate nerve agents
    if (!empty($data['nerve_agent'])) {
        if (!in_array($data['nerve_agent'], ALLOWED_MEDICATIONS['nerve_agents'])) {
            $errors['nerve_agent'] = 'Invalid nerve agent selected';
        } else {
            if ($data['nerve_agent'] === 'Gabapentin titration') {
                if (empty($data['gabapentin_titration'])) {
                    $errors['nerve_agent'] = 'Gabapentin titration duration required';
                }
            } else {
                $baseNerve = str_replace(['/', ' '], '_', strtolower($data['nerve_agent']));
                if (empty($data[$baseNerve . '_dose']) || empty($data[$baseNerve . '_frequency'])) {
                    $errors['nerve_agent'] = 'Nerve agent dose and frequency required';
                }
            }
        }
    }
    
    return $errors;
}

// Update Qutenza validation to match new config
function validateQutenza($data) {
    $errors = [];
    
    if (in_array('Qutenza', $data['treatment'] ?? [])) {
        if (empty($data['qutenza_side']) || !in_array($data['qutenza_side'], QUTENZA_OPTIONS['sides'])) {
            $errors[] = 'Valid Qutenza side selection required';
        }
        
        if (empty($data['qutenza_area']) || !in_array($data['qutenza_area'], QUTENZA_OPTIONS['areas'])) {
            $errors[] = 'Valid Qutenza treatment area required';
        }
    }
    
    return $errors;
}

function validateImaging($data) {
    $errors = [];
    
    // Validate bilateral imaging selections
    $bilateralParts = ['shoulder', 'hip', 'SIJ', 'knee'];
    foreach ($bilateralParts as $part) {
        if (!empty($data[$part . '_xray']) && !empty($data[$part . '_mri'])) {
            $errors[] = "Cannot select both X-ray and MRI for $part";
        }
    }
    
    // Validate spine imaging
    $spineParts = ['cervical', 'thoracic', 'lumbar'];
    foreach ($spineParts as $part) {
        if (!empty($data[$part . '_spine'])) {
            $value = $data[$part . '_spine'];
            if (strpos($value, 'XR') !== false && strpos($value, 'MRI') !== false) {
                $errors[] = "Cannot select both X-ray and MRI for $part spine";
            }
        }
    }
    
    return $errors;
}
?>