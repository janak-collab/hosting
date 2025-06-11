<?php
/**
 * Treatment Medications Form Component
 * 
 * Handles displaying and managing medication selection forms
 * 
 * @package MedicalScribe
 * @version 1.1
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/security.php';

// Get any stored form data from failed validation
$formData = $_SESSION['form_data'] ?? [];
$formErrors = $_SESSION['form_errors'] ?? [];

// Clear stored form data after retrieving it
unset($_SESSION['form_data'], $_SESSION['form_errors']);

/**
 * Helper function to check if a medication is selected
 */
function isSelected($fieldName, $value) {
    global $formData;
    return isset($formData[$fieldName]) && $formData[$fieldName] === $value;
}

/**
 * Helper function to check if a specific dose/frequency is selected
 */
function isOptionSelected($fieldName, $value) {
    global $formData;
    return isset($formData[$fieldName]) && $formData[$fieldName] === $value;
}

/**
 * Helper function to display error message
 */
function displayError($fieldName) {
    global $formErrors;
    if (isset($formErrors[$fieldName])) {
        return '<div class="error-message">' . secureSanitizeInput($formErrors[$fieldName]) . '</div>';
    }
    return '';
}
?>

<section class="medications" id="medications-section">
    <?php echo generateCsrfTokenField(); ?>
    
    <div class="error-container" id="medications-error-container"></div>
    <h2>Medications</h2>

    <!-- Anti-inflammatories Card -->
    <div class="medication-card">
        <div class="medication-card-header">
            <h3><?php echo secureSanitizeInput(MEDICATION_CATEGORIES_INFO['anti_inflammatories']['display_name']); ?></h3>
            <?php echo displayError('NSAID'); ?>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" 
                           name="NSAID" 
                           value="" 
                           <?php echo isSelected('NSAID', '') ? 'checked' : ''; ?>
                           aria-label="No anti-inflammatory medication">
                    <span class="radio-label">No Anti-inflammatory</span>
                </label>
            </div>
        </div>
        
        <div class="medication-card-content">
            <?php foreach (MEDICATION_DETAILS as $medName => $medInfo): ?>
                <?php if ($medInfo['category'] === 'anti_inflammatories'): ?>
                    <div class="medication-item">
                        <label class="radio-option">
                            <input type="radio" 
                                   name="NSAID" 
                                   value="<?php echo secureSanitizeInput($medName); ?>"
                                   <?php echo isSelected('NSAID', $medName) ? 'checked' : ''; ?>
                                   aria-label="Select <?php echo secureSanitizeInput($medInfo['display_name']); ?>">
                            <span class="radio-label"><?php echo secureSanitizeInput($medInfo['display_name']); ?></span>
                        </label>
                        
                        <div class="dosage-options" <?php echo isSelected('NSAID', $medName) ? '' : 'style="display: none;"'; ?>>
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <div class="radio-group">
                                    <?php foreach ($medInfo['doses'] as $dose): ?>
                                        <label class="radio-pill">
                                            <input type="radio" 
                                                   name="<?php echo getMedicationBaseName($medName); ?>_dose" 
                                                   value="<?php echo secureSanitizeInput($dose); ?>"
                                                   <?php echo isOptionSelected(getMedicationBaseName($medName).'_dose', $dose) ? 'checked' : ''; ?>
                                                   <?php echo !isSelected('NSAID', $medName) ? 'disabled' : ''; ?>
                                                   required
                                                   aria-required="true">
                                            <span class="radio-pill-label"><?php echo secureSanitizeInput($dose); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php echo displayError(getMedicationBaseName($medName).'_dose'); ?>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <div class="radio-group">
                                    <?php foreach ($medInfo['frequencies'] as $freq): ?>
                                        <label class="radio-pill">
                                            <input type="radio" 
                                                   name="<?php echo getMedicationBaseName($medName); ?>_frequency" 
                                                   value="<?php echo secureSanitizeInput($freq); ?>"
                                                   <?php echo isOptionSelected(getMedicationBaseName($medName).'_frequency', $freq) ? 'checked' : ''; ?>
                                                   <?php echo !isSelected('NSAID', $medName) ? 'disabled' : ''; ?>
                                                   required
                                                   aria-required="true">
                                            <span class="radio-pill-label"><?php echo secureSanitizeInput(FREQUENCY_LABELS[$freq]); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php echo displayError(getMedicationBaseName($medName).'_frequency'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Muscle Relaxers Card -->
    <div class="medication-card">
        <div class="medication-card-header">
            <h3><?php echo secureSanitizeInput(MEDICATION_CATEGORIES_INFO['muscle_relaxers']['display_name']); ?></h3>
            <?php echo displayError('mrelaxer'); ?>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" 
                           name="mrelaxer" 
                           value="" 
                           <?php echo isSelected('mrelaxer', '') ? 'checked' : ''; ?>
                           aria-label="No muscle relaxer medication">
                    <span class="radio-label">No Muscle Relaxer</span>
                </label>
            </div>
        </div>
        
        <div class="medication-card-content">
            <?php foreach (MEDICATION_DETAILS as $medName => $medInfo): ?>
                <?php if ($medInfo['category'] === 'muscle_relaxers'): ?>
                    <div class="medication-item">
                        <label class="radio-option">
                            <input type="radio" 
                                   name="mrelaxer" 
                                   value="<?php echo secureSanitizeInput($medName); ?>"
                                   <?php echo isSelected('mrelaxer', $medName) ? 'checked' : ''; ?>
                                   aria-label="Select <?php echo secureSanitizeInput($medInfo['display_name']); ?>">
                            <span class="radio-label"><?php echo secureSanitizeInput($medInfo['display_name']); ?></span>
                        </label>
                        
                        <div class="dosage-options" <?php echo isSelected('mrelaxer', $medName) ? '' : 'style="display: none;"'; ?>>
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <div class="radio-group">
                                    <?php foreach ($medInfo['doses'] as $dose): ?>
                                        <label class="radio-pill">
                                            <input type="radio" 
                                                   name="<?php echo getMedicationBaseName($medName); ?>_dose" 
                                                   value="<?php echo secureSanitizeInput($dose); ?>"
                                                   <?php echo isOptionSelected(getMedicationBaseName($medName).'_dose', $dose) ? 'checked' : ''; ?>
                                                   <?php echo !isSelected('mrelaxer', $medName) ? 'disabled' : ''; ?>
                                                   required
                                                   aria-required="true">
                                            <span class="radio-pill-label"><?php echo secureSanitizeInput($dose); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php echo displayError(getMedicationBaseName($medName).'_dose'); ?>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <div class="radio-group">
                                    <?php foreach ($medInfo['frequencies'] as $freq): ?>
                                        <label class="radio-pill">
                                            <input type="radio" 
                                                   name="<?php echo getMedicationBaseName($medName); ?>_frequency" 
                                                   value="<?php echo secureSanitizeInput($freq); ?>"
                                                   <?php echo isOptionSelected(getMedicationBaseName($medName).'_frequency', $freq) ? 'checked' : ''; ?>
                                                   <?php echo !isSelected('mrelaxer', $medName) ? 'disabled' : ''; ?>
                                                   required
                                                   aria-required="true">
                                            <span class="radio-pill-label"><?php echo secureSanitizeInput(FREQUENCY_LABELS[$freq]); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                                <?php echo displayError(getMedicationBaseName($medName).'_frequency'); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Nerve Agents Card -->
    <div class="medication-card">
        <div class="medication-card-header">
            <h3><?php echo secureSanitizeInput(MEDICATION_CATEGORIES_INFO['nerve_agents']['display_name']); ?></h3>
            <?php echo displayError('nerve_agent'); ?>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" 
                           name="nerve_agent" 
                           value="" 
                           <?php echo isSelected('nerve_agent', '') ? 'checked' : ''; ?>
                           aria-label="No nerve agent medication">
                    <span class="radio-label">No Nerve Agent</span>
                </label>
            </div>
        </div>
        
        <div class="medication-card-content">
            <?php foreach (MEDICATION_DETAILS as $medName => $medInfo): ?>
                <?php if ($medInfo['category'] === 'nerve_agents'): ?>
                    <div class="medication-item">
                        <label class="radio-option">
                            <input type="radio" 
                                   name="nerve_agent" 
                                   value="<?php echo secureSanitizeInput($medName); ?>"
                                   <?php echo isSelected('nerve_agent', $medName) ? 'checked' : ''; ?>
                                   aria-label="Select <?php echo secureSanitizeInput($medInfo['display_name']); ?>">
                            <span class="radio-label"><?php echo secureSanitizeInput($medInfo['display_name']); ?></span>
                        </label>
                        
                        <?php if ($medName === 'Gabapentin titration'): ?>
                            <div class="dosage-options" <?php echo isSelected('nerve_agent', $medName) ? '' : 'style="display: none;"'; ?>>
                                <div class="dose-group">
                                    <span class="option-label">Duration:</span>
                                    <div class="radio-group">
                                        <?php foreach ($medInfo['durations'] as $duration): ?>
                                            <label class="radio-pill">
                                                <input type="radio" 
                                                       name="gabapentin_titration" 
                                                       value="<?php echo secureSanitizeInput($duration); ?>"
                                                       <?php echo isOptionSelected('gabapentin_titration', $duration) ? 'checked' : ''; ?>
                                                       <?php echo !isSelected('nerve_agent', $medName) ? 'disabled' : ''; ?>
                                                       required
                                                       aria-required="true">
                                                <span class="radio-pill-label"><?php echo secureSanitizeInput($duration); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo displayError('gabapentin_titration'); ?>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="dosage-options" <?php echo isSelected('nerve_agent', $medName) ? '' : 'style="display: none;"'; ?>>
                                <div class="dose-group">
                                    <span class="option-label">Dose:</span>
                                    <div class="radio-group">
                                        <?php foreach ($medInfo['doses'] as $dose): ?>
                                            <label class="radio-pill">
                                                <input type="radio" 
                                                       name="<?php echo getMedicationBaseName($medName); ?>_dose" 
                                                       value="<?php echo secureSanitizeInput($dose); ?>"
                                                       <?php echo isOptionSelected(getMedicationBaseName($medName).'_dose', $dose) ? 'checked' : ''; ?>
                                                       <?php echo !isSelected('nerve_agent', $medName) ? 'disabled' : ''; ?>
                                                       required
                                                       aria-required="true">
                                                <span class="radio-pill-label"><?php echo secureSanitizeInput($dose); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo displayError(getMedicationBaseName($medName).'_dose'); ?>
                                </div>
                                
                                <div class="frequency-group">
                                    <span class="option-label">Frequency:</span>
                                    <div class="radio-group">
                                        <?php foreach ($medInfo['frequencies'] as $freq): ?>
                                            <label class="radio-pill">
                                                <input type="radio" 
                                                       name="<?php echo getMedicationBaseName($medName); ?>_frequency" 
                                                       value="<?php echo secureSanitizeInput($freq); ?>"
                                                       <?php echo isOptionSelected(getMedicationBaseName($medName).'_frequency', $freq) ? 'checked' : ''; ?>
                                                       <?php echo !isSelected('nerve_agent', $medName) ? 'disabled' : ''; ?>
                                                       required
                                                       aria-required="true">
                                                <span class="radio-pill-label"><?php echo secureSanitizeInput(FREQUENCY_LABELS[$freq]); ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php echo displayError(getMedicationBaseName($medName).'_frequency'); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Helper function to toggle dosage options
    function toggleDosageOptions(medicationItem, show) {
        const dosageOptions = medicationItem.querySelector('.dosage-options');
        if (dosageOptions) {
            dosageOptions.style.display = show ? 'block' : 'none';
            
            // Enable/disable inputs based on selection
            const inputs = dosageOptions.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => {
                input.disabled = !show;
                if (!show) {
                    input.checked = false;
                }
            });
        }
    }

    // Handle medication selection changes
    function handleMedicationSelection(category) {
        const container = document.querySelector(`[name="${category}"]`).closest('.medication-card');
        const medications = container.querySelectorAll('.medication-item');
        
        medications.forEach(medication => {
            const radio = medication.querySelector(`input[name="${category}"]`);
            if (radio) {
                toggleDosageOptions(medication, radio.checked && radio.value !== '');
            }
        });
    }

    // Add event listeners to all medication radio buttons
    ['NSAID', 'mrelaxer', 'nerve_agent'].forEach(category => {
        const radios = document.querySelectorAll(`input[name="${category}"]`);
        radios.forEach(radio => {
            radio.addEventListener('change', () => handleMedicationSelection(category));
        });
        
        // Initialize state
        handleMedicationSelection(category);
    });

    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            let isValid = true;
            const errors = {};

            // Validate each selected medication
            ['NSAID', 'mrelaxer', 'nerve_agent'].forEach(category => {
                const selectedMed = form.querySelector(`input[name="${category}"]:checked`);
                if (selectedMed && selectedMed.value) {
                    const baseName = selectedMed.value.toLowerCase().replace(/\s+/g, '_');
                    
                    // Special handling for Gabapentin titration
                    if (selectedMed.value === 'Gabapentin titration') {
                        const titration = form.querySelector('input[name="gabapentin_titration"]:checked');
                        if (!titration) {
                            isValid = false;
                            errors[`${category}_duration`] = 'Please select a titration duration';
                        }
                    } else {
                        // Check dose
                        const doseSelected = form.querySelector(`input[name="${baseName}_dose"]:checked`);
                        if (!doseSelected) {
                            isValid = false;
                            errors[`${category}_dose`] = 'Please select a dose';
                        }
                        
                        // Check frequency
                        const freqSelected = form.querySelector(`input[name="${baseName}_frequency"]:checked`);
                        if (!freqSelected) {
                            isValid = false;
                            errors[`${category}_frequency`] = 'Please select a frequency';
                        }
                    }
                }
            });

            // Display errors if any
            if (!isValid) {
                event.preventDefault();
                const errorContainer = document.getElementById('medications-error-container');
                errorContainer.innerHTML = '';
                
                Object.entries(errors).forEach(([field, message]) => {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.textContent = message;
                    errorContainer.appendChild(errorDiv);
                    
                    // Also display error near the relevant field
                    const fieldError = document.querySelector(`[data-error-for="${field}"]`);
                    if (fieldError) {
                        fieldError.textContent = message;
                    }
                });
                
                // Scroll to first error
                errorContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // Clear error messages when making new selections
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const errorContainer = document.getElementById('medications-error-container');
            errorContainer.innerHTML = '';
            
            // Clear field-specific error messages
            document.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
            });
        });
    });
});
</script>