/**
 * Initialize all form handlers
 */
document.addEventListener('DOMContentLoaded', function() {
    // Existing initialization
    initializeFormValidation();
    initializeForm();
    attachEventListeners();
    initializeMedicationControls();
    handleQutenzaSelection();
    initializeImagingHandlers();
    initializeProviderValidation();
    
    // New initializations from consolidated code
    initializeImagingGrid();
    initializeSignatureCanvas();
    initializeBracesHandlers();
    initializePhysicalTherapyHandlers();
});

/**
 * Initialize form validation for required fields
 */
function initializeFormValidation() {
    const form = document.getElementById('radiologyForm');
    // Modified selector to exclude the hidden provider validation input
    const requiredInputs = form.querySelectorAll('input[required]:not([id="provider_validation"])');
    
    requiredInputs.forEach(input => {
        // Create error message div after each required input
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = `Please ${input.type === 'date' ? 'select' : 'enter'} ${input.previousElementSibling?.textContent.toLowerCase() || 'this field'}`;
        input.parentNode.appendChild(errorDiv);

        // Show error message initially for all required fields except date of service
        if (input.id !== 'dos') {
            errorDiv.style.display = 'block';
        } else {
            errorDiv.style.display = 'none';
        }

        // Add invalid event handler
        input.addEventListener('invalid', (e) => {
            e.preventDefault(); // Prevent default browser validation popup
            input.classList.add('input-error');
            const errorMessage = input.parentNode.querySelector('.field-error');
            if (errorMessage) {
                errorMessage.style.display = 'block';
            }
        });

        // Add input event handler to remove error state
        input.addEventListener('input', () => {
            input.classList.remove('input-error');
            const errorMessage = input.parentNode.querySelector('.field-error');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        });
    });
}

/**
 * Format date to mm-dd-YYYY
 */
function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const year = date.getFullYear();
    return `${month}-${day}-${year}`;
}

/**
 * Initialize form with default values and date constraints
 */
function initializeForm() {
    // Set DOS to today's date with timezone adjustment
    const today = new Date();
    today.setHours(0, 0, 0, 0);  // Set to midnight
    const todayISO = today.toISOString().split('T')[0];
    const dosInput = document.getElementById('dos');
    if (dosInput) {
        dosInput.value = todayISO;
        
        // Calculate 90 days ago for DOS validation
        const ninetyDaysAgo = new Date(today);
        ninetyDaysAgo.setDate(ninetyDaysAgo.getDate() - 90);
        ninetyDaysAgo.setHours(0, 0, 0, 0);
        
        // Add event listener for DOS date validation
        dosInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            selectedDate.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                showErrors(['Date of Service cannot be in the future']);
                this.value = todayISO;
            } else if (selectedDate < ninetyDaysAgo) {
                showErrors(['Date of Service cannot be more than 90 days in the past']);
                this.value = todayISO;
            }
        });
    }
    
    // Set DOB year constraints
    const dobInput = document.getElementById('dob');
    if (dobInput) {
        dobInput.min = '1900-01-01';
        dobInput.max = todayISO;
        dobInput.value = ''; // Clear any default value
        
        // Add event listener for DOB validation
        dobInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            selectedDate.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                showErrors(['Date of Birth cannot be in the future']);
                this.value = '';
            }
        });
    }
}

/**
 * Initialize medication controls
 */
function initializeMedicationControls() {
    // Hide all dosage options initially
    hideAllDosageOptions();
    
    // Disable all dose and frequency selectors initially
    document.querySelectorAll('.dosage-options input[type="radio"]').forEach(input => {
        input.disabled = true;
    });
}

/**
 * Handle Qutenza treatment selection
 */
function handleQutenzaSelection() {
    const qutenzaCheckbox = document.querySelector('input[value="Qutenza"]');
    if (!qutenzaCheckbox) return;

    qutenzaCheckbox.addEventListener('change', function() {
        const treatmentOptions = this.closest('.treatment-item').querySelector('.treatment-options');
        const sideInputs = document.querySelectorAll('input[name="qutenza_side"]');
        const areaInputs = document.querySelectorAll('input[name="qutenza_area"]');
        
        if (this.checked) {
            treatmentOptions.style.display = 'block';
            // Only enable side selection initially
            sideInputs.forEach(input => input.disabled = false);
            // Keep area inputs disabled until side is selected
            areaInputs.forEach(input => {
                input.disabled = true;
                input.checked = false;
            });
        } else {
            treatmentOptions.style.display = 'none';
            // Reset and disable all inputs when unchecked
            sideInputs.forEach(input => {
                input.disabled = true;
                input.checked = false;
            });
            areaInputs.forEach(input => {
                input.disabled = true;
                input.checked = false;
            });
        }
    });

    // Add event listeners for side selection
    const sideInputs = document.querySelectorAll('input[name="qutenza_side"]');
    sideInputs.forEach(sideInput => {
        sideInput.addEventListener('change', function() {
            const areaInputs = document.querySelectorAll('input[name="qutenza_area"]');
            if (this.checked) {
                // Enable area selection only after side is selected
                areaInputs.forEach(input => input.disabled = false);
            } else {
                // Disable and clear area selection if side is unchecked
                areaInputs.forEach(input => {
                    input.disabled = true;
                    input.checked = false;
                });
            }
        });
    });
}

/**
 * Hide all medication dosage options
 */
function hideAllDosageOptions() {
    document.querySelectorAll('.dosage-options').forEach(options => {
        options.style.display = 'none';
    });
}

/**
 * Clear all dose and frequency selections for a medication
 */
function clearDoseAndFrequency(medicationItem) {
    if (!medicationItem) return;
    
    medicationItem.querySelectorAll('.dosage-options input[type="radio"]').forEach(radio => {
        radio.checked = false;
        radio.disabled = true;
    });
}

/**
 * Helper function to get medication base name
 */
function getMedicationBaseName(medicationName) {
    if (medicationName.includes('/')) {
        const parts = medicationName.split('/');
        const brandName = parts[1].trim();
        const nameMap = {
            'Skelaxin': 'metaxalone',
            'Flexeril': 'flexeril',
            'Robaxin': 'methocarbamol',
            'Celebrex': 'celebrex',
            'Cymbalta': 'cymbalta',
            'Lyrica': 'lyrica'
        };
        return nameMap[brandName] || brandName.toLowerCase();
    }
    
    const medicationMap = {
        'nabumetone': 'nabumetone',
        'meloxicam': 'meloxicam',
        'baclofen': 'baclofen',
        'Gabapentin': 'gabapentin',
        'Gabapentin titration': 'gabapentin_titration'
    };
    return medicationMap[medicationName.toLowerCase()] || medicationName.toLowerCase();
}

/**
 * Validate medication selection
 */
function validateMedication(formData, medicationType, medicationField) {
    const currentSelection = document.querySelector(`input[name="${medicationType}"]:checked`);
    if (currentSelection && currentSelection.value) {
        const selectedMed = currentSelection.value;
        const baseName = getMedicationBaseName(selectedMed);

        if (selectedMed === 'Gabapentin titration') {
            const hasDuration = formData.get('gabapentin_titration');
            if (!hasDuration) {
                return `${selectedMed} duration`;
            }
        } else {
            const hasDose = formData.get(`${baseName}_dose`);
            const hasFreq = formData.get(`${baseName}_frequency`);
            
            if (!hasDose || (!hasFreq && selectedMed !== 'Gabapentin titration')) {
                return `${selectedMed} dose and frequency`;
            }
        }
    }
    return null;
}

/**
 * Validate Qutenza selections
 */
function validateQutenza(formData, missingFields) {
    const qutenza = document.querySelector('input[value="Qutenza"]:checked');
    if (qutenza) {
        if (!formData.get('qutenza_side')) {
            missingFields.push('Qutenza side selection');
        }
        if (!formData.get('qutenza_area')) {
            missingFields.push('Qutenza treatment area');
        }
        if (formData.get('qutenza_area') === 'other' && !formData.get('qutenza_other_specify')) {
            missingFields.push('Qutenza other area specification');
        }
    }
}

/**
 * Attach event listeners to form elements
 */
function attachEventListeners() {
    const form = document.getElementById("radiologyForm");
    if (form) {
        form.addEventListener("submit", handleFormSubmission);
    }
    
    attachMedicationListeners();
}

/**
 * Attach medication-related event listeners
 */
function attachMedicationListeners() {
    // Handle NSAID selection
    document.querySelectorAll('input[name="NSAID"]').forEach(radio => {
        radio.addEventListener('change', function() {
            handleMedicationSelection(this, 'NSAID');
        });
    });

    // Handle Muscle Relaxer selection
    document.querySelectorAll('input[name="mrelaxer"]').forEach(radio => {
        radio.addEventListener('change', function() {
            handleMedicationSelection(this, 'mrelaxer');
        });
    });

    // Add this section for Nerve Agents
    document.querySelectorAll('input[name="nerve_agent"]').forEach(radio => {
        radio.addEventListener('change', function() {
            handleMedicationSelection(this, 'nerve_agent');
        });
    });
}

/**
 * Handle medication selection changes
 */
function handleMedicationSelection(radioButton, medicationType) {
    const sameTypeMedications = document.querySelectorAll(`.medication-item input[name="${medicationType}"]`);
    
    sameTypeMedications.forEach(input => {
        if (input !== radioButton) {
            const medicationItem = input.closest('.medication-item');
            if (medicationItem) {
                const dosageOptions = medicationItem.querySelector('.dosage-options');
                if (dosageOptions) {
                    dosageOptions.style.display = 'none';
                    dosageOptions.querySelectorAll('input[type="radio"]').forEach(radio => {
                        radio.checked = false;
                        radio.disabled = true;
                    });
                }
            }
        }
    });
    
    if (radioButton.checked) {
        const medicationItem = radioButton.closest('.medication-item');
        const dosageOptions = medicationItem.querySelector('.dosage-options');
        if (dosageOptions) {
            dosageOptions.style.display = 'block';
            dosageOptions.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.disabled = false;
            });
        }
    }
}

/**
 * Handle imaging selection and enforce mutual exclusivity rules
 */
function handleImagingSelection(part, type, side) {
    // Get the current input and the opposite type input
    const oppositeType = type === 'xray' ? 'mri' : 'xray';
    
    // If selecting bilateral
    if (side === 'bilateral') {
        // Uncheck all opposite type inputs
        document.querySelectorAll(`input[name="${part}_${oppositeType}"]`).forEach(input => {
            input.checked = false;
        });
        return;
    }

    // For single-side selections (left or right)
    if (side === 'left' || side === 'right') {
        // Find and uncheck the opposite type on the same side
        const oppositeInput = document.querySelector(
            `input[name="${part}_${oppositeType}"][value*="${side.charAt(0).toUpperCase() + side.slice(1)}"]`
        );
        if (oppositeInput) {
            oppositeInput.checked = false;
        }

        // Clear bilateral selections for both types
        const bilateralInputs = document.querySelectorAll(
            `input[name="${part}_${type}"][value*="Bilateral"], input[name="${part}_${oppositeType}"][value*="Bilateral"]`
        );
        bilateralInputs.forEach(input => {
            input.checked = false;
        });
    }
}

// Remove onclick handlers from HTML and use event listeners
document.addEventListener('DOMContentLoaded', function() {
    const bilateralParts = ['shoulder', 'hip', 'SIJ', 'knee'];
    bilateralParts.forEach(part => {
        ['xray', 'mri'].forEach(type => {
            document.querySelectorAll(`input[name="${part}_${type}"]`).forEach(input => {
                input.addEventListener('change', function() {
                    if (this.checked) {
                        const value = this.value.toLowerCase();
                        const side = value.includes('right') ? 'right' :
                                   value.includes('left') ? 'left' : 'bilateral';
                        handleImagingSelection(part, type, side);
                    }
                });
            });
        });
    });
});

/**
 * Clear all imaging selections
 */
function clearAllImaging() {
    console.log('Starting clearAllImaging function');
    
    // First, clear all visible radio buttons and checkboxes
    document.querySelectorAll('input[type="radio"][name*="_type"], input[type="radio"][name*="_side"], input[type="radio"][name*="_spine"]').forEach(input => {
        input.checked = false;
        if (input.name.includes('_side')) {
            input.disabled = true;
        }
    });

    // Clear all bilateral parts (shoulder, hip, SIJ, knee)
    const bilateralParts = ['shoulder', 'hip', 'SIJ', 'knee'];
    bilateralParts.forEach(part => {
        console.log(`Clearing ${part} imaging selections`);
        
        // Clear all radio buttons for this part
        document.querySelectorAll(`input[name="${part}_type"], input[name="${part}_side"]`).forEach(input => {
            input.checked = false;
            if (input.name.includes('_side')) {
                input.disabled = true;
            }
        });

        // Find and remove all hidden inputs for both xray and mri
        ['xray', 'mri'].forEach(type => {
            const existingInputs = document.querySelectorAll(`input[type="hidden"][name="${part}_${type}"]`);
            existingInputs.forEach(input => {
                console.log(`Removing hidden input for ${part}_${type}`);
                input.remove();
            });
        });
    });

    // Clear spine parts
    const spineParts = ['cervical', 'thoracic', 'lumbar'];
    spineParts.forEach(part => {
        console.log(`Clearing ${part} spine selections`);
        
        // Clear radio buttons
        document.querySelectorAll(`input[name="${part}_spine"]:not([type="hidden"])`).forEach(input => {
            input.checked = false;
        });

        // Remove hidden inputs
        document.querySelectorAll(`input[type="hidden"][name="${part}_spine"]`).forEach(input => {
            console.log(`Removing hidden spine input for ${part}_spine`);
            input.remove();
        });
    });

    // Run additional cleanup
    cleanupHiddenImagingInputs();
    
    // Reset form imaging section
    const form = document.getElementById('radiologyForm');
    if (form) {
        const imagingSection = form.querySelector('.imaging-section');
        if (imagingSection) {
            const inputs = imagingSection.querySelectorAll('input[type="radio"], input[type="checkbox"]');
            inputs.forEach(input => {
                input.checked = false;
                if (input.name.includes('_side')) {
                    input.disabled = true;
                }
            });
        }
    }

    // Final verification
    const remainingHiddenInputs = form.querySelectorAll('input[type="hidden"][name*="_xray"], input[type="hidden"][name*="_mri"], input[type="hidden"][name*="_spine"]');
    console.log(`Remaining imaging hidden inputs after clearing: ${remainingHiddenInputs.length}`);
    remainingHiddenInputs.forEach(input => {
        console.log(`Warning: Found remaining hidden input: ${input.name} = ${input.value}`);
        // Force remove any remaining imaging hidden inputs
        input.remove();
    });
}

/**
 * Initialize imaging selection handlers
 */
function initializeImagingHandlers() {
    // Add clear all button handler
    const clearButton = document.getElementById('clearAllImaging');
    if (clearButton) {
        clearButton.addEventListener('click', clearAllImaging);
    }

    // Handle bilateral imaging selections
    const bilateralParts = ['shoulder', 'hip', 'SIJ', 'knee'];
    bilateralParts.forEach(part => {
        // Type selection (X-Ray/MRI)
        document.querySelectorAll(`input[name="${part}_type"]`).forEach(typeInput => {
            typeInput.addEventListener('change', function() {
                const sideInputs = document.querySelectorAll(`input[name="${part}_side"]`);
                sideInputs.forEach(input => {
                    input.disabled = !this.checked;
                    if (this.checked && input.checked) {
                        // Create or update the hidden input for the complete selection
                        updateImagingSelection(part, this.value, input.value);
                    }
                });
            });
        });

        // Side selection
        document.querySelectorAll(`input[name="${part}_side"]`).forEach(sideInput => {
            sideInput.addEventListener('change', function() {
                if (this.checked) {
                    const typeInput = document.querySelector(`input[name="${part}_type"]:checked`);
                    if (typeInput) {
                        updateImagingSelection(part, typeInput.value, this.value);
                    }
                }
            });
        });
    });

    // Handle spine imaging
    ['cervical', 'thoracic', 'lumbar'].forEach(part => {
        document.querySelectorAll(`input[name="${part}_spine"]`).forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    // Create or update hidden input for spine selection
                    updateSpineImagingSelection(part, this.value);
                }
            });
        });
    });
}

/**
 * Update imaging selection with hidden input
 */
function updateImagingSelection(part, type, side) {
    const formattedType = type === 'xray' ? 'XR' : 'MRI';
    const formattedSide = side.charAt(0).toUpperCase() + side.slice(1);
    const value = `${part.toUpperCase()} ${formattedType} ${formattedSide}`;
    
    let hiddenInput = document.querySelector(`input[name="${part}_${type.toLowerCase()}"]`);
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `${part}_${type.toLowerCase()}`;
        document.getElementById('radiologyForm').appendChild(hiddenInput);
    }
    hiddenInput.value = value;
}

/**
 * Update spine imaging selection with hidden input
 */
function updateSpineImagingSelection(part, value) {
    let hiddenInput = document.querySelector(`input[name="${part}_spine"]`);
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = `${part}_spine`;
        document.getElementById('radiologyForm').appendChild(hiddenInput);
    }
    hiddenInput.value = value;
}

/**
 * Initialize provider validation
 */
function validateProvider(radioButton) {
    const validationInput = document.getElementById('provider_validation');
    const errorMessage = document.querySelector('.provider-error');
    const providerGrid = document.querySelector('.provider-grid');
    
    if (radioButton.checked) {
        validationInput.value = radioButton.value;
        providerGrid.classList.remove('provider-grid-error');
        errorMessage.style.display = 'none';
    } else {
        validationInput.value = '';
        providerGrid.classList.add('provider-grid-error');
        errorMessage.style.display = 'block';
    }
}

function initializeProviderValidation() {
    const providerInputs = document.querySelectorAll('input[name="provider_name"]');
    const providerGrid = document.querySelector('.provider-grid');
    const validationInput = document.getElementById('provider_validation');
    
    // Set initial validation state
    providerGrid.setAttribute('aria-invalid', 'true');

    // Add validation message container if it doesn't exist
    if (!document.querySelector('.provider-error')) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'provider-error';
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'Please choose a provider';
        providerGrid.parentNode.insertBefore(errorDiv, providerGrid.nextSibling);
    } else {
        // Also handle case where error div already exists
        document.querySelector('.provider-error').style.display = 'block';
    }

    // Add required attribute to validation input
    validationInput.required = true;

    // Add change handlers to all radio buttons
    providerInputs.forEach(input => {
        input.addEventListener('change', () => {
            if (input.checked) {
                validationInput.value = input.value;
                providerGrid.setAttribute('aria-invalid', 'false');
                providerGrid.classList.remove('provider-grid-error');
                document.querySelector('.provider-error').style.display = 'none';
            }
        });
    });

    // Handle form validation
    document.querySelector('form').addEventListener('invalid', (e) => {
        if (e.target === validationInput) {
            e.preventDefault();
            providerGrid.setAttribute('aria-invalid', 'true');
            providerGrid.classList.add('provider-grid-error');
            document.querySelector('.provider-error').style.display = 'block';
            providerGrid.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }, true);
}

// Add CSS for error state
const style = document.createElement('style');
style.textContent = `
    .provider-grid-error {
        border: 2px solid #dc3545;
        border-radius: 4px;
        padding: 4px;
    }
`;
document.head.appendChild(style);

/**
 * Format patient name from "Firstname Lastname" to "Lastname, Firstname"
 */
function formatNameForFileName(fullName) {
    const nameParts = fullName.trim().split(' ');
    if (nameParts.length < 2) return fullName;
    
    const firstName = nameParts[0];
    const lastName = nameParts.slice(1).join(' ');
    return `${lastName}, ${firstName}`;
}

/**
 * Handle form submission
 */
async function handleFormSubmission(event) {
    event.preventDefault();
    console.log('Form submission started');
    clearErrors();
    
    const loadingIndicator = showLoadingIndicator();
    
    try {
        const formData = new FormData(event.target);
        const missingFields = [];
        let firstMissingElement = null;

        // Debug: Log all form data
        console.log('Form data entries at submission:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        // Remove imaging fields from FormData
        const imagingFields = [
            'shoulder_xray', 'shoulder_mri', 
            'hip_xray', 'hip_mri',
            'SIJ_xray', 'SIJ_mri', 
            'knee_xray', 'knee_mri',
            'cervical_spine', 'thoracic_spine', 'lumbar_spine'
        ];
        
        imagingFields.forEach(field => {
            formData.delete(field);
        });

        // Proceed with redirect
        redirectToProcess(formData);

        // Handle required fields validation
        const requiredFields = ['patient_name', 'dob', 'dos', 'provider_name'];
        for (const field of requiredFields) {
            const value = formData.get(field);
            if (!value || value.trim() === '') {
                missingFields.push(field.replace('_', ' '));
                if (!firstMissingElement) {
                    firstMissingElement = document.getElementById(field);
                }
            }
        }

        // If we have missing fields, show error
        if (missingFields.length > 0) {
            console.log('Missing fields:', missingFields);
            const errorMessage = `Missing required fields: ${missingFields.join(', ')}`;
            showErrors([errorMessage]);
            
            if (firstMissingElement) {
                console.log('Scrolling to first missing element');
                firstMissingElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            hideLoadingIndicator(loadingIndicator);
            return;
        }

        // Validate the form data one last time
        const finalFormData = new FormData(event.target);
        let finalHasImagingData = false;
        for (let [key, value] of finalFormData.entries()) {
            if ((key.includes('_xray') || key.includes('_mri') || key.includes('_spine')) && value && value.trim() !== '') {
                finalHasImagingData = true;
                break;
            }
        }

        console.log('Proceeding with form submission');
        
        // Submit form to server
        const response = await fetch("radiology-order.php", {
            method: "POST",
            body: finalFormData
        });
        
        const contentType = response.headers.get("content-type");
        console.log('Response content type:', contentType);
        
        if (!response.ok) {
            let errorMessage = `Server error (${response.status})`;
            if (contentType && contentType.includes('application/json')) {
                const errorData = await response.json();
                errorMessage = errorData.message || errorMessage;
            }
            throw new Error(errorMessage);
        }
        
        // Handle JSON response indicating no PDF
        if (contentType && contentType.includes('application/json')) {
            const jsonResponse = await response.json();
            if (jsonResponse.noPdf) {
                redirectToProcess(finalFormData);
                return;
            }
        }
        
        // Handle PDF response
        if (contentType && contentType.includes('application/pdf')) {
            const blob = await response.blob();
            if (blob.size === 0) {
                throw new Error('Received empty response from server');
            }
            
            const patientName = finalFormData.get('patient_name');
            downloadPdf(blob, `${formatNameForFileName(patientName)} radiology order.pdf`);
        }
        
        // Redirect after successful submission
        redirectToProcess(finalFormData);
        
    } catch (error) {
        console.error('Error details:', error);
        showErrors([error.message]);
    } finally {
        hideLoadingIndicator(loadingIndicator);
    }
}

/**
 * Clean up any remaining hidden imaging inputs
 */
function cleanupHiddenImagingInputs() {
    const form = document.getElementById('radiologyForm');
    const hiddenInputs = form.querySelectorAll('input[type="hidden"]');
    
    hiddenInputs.forEach(input => {
        const name = input.name;
        // Check if this is an imaging-related input
        if (name.includes('_xray') || name.includes('_mri') || name.includes('_spine')) {
            // If it has no value or empty value, remove it
            if (!input.value || input.value.trim() === '') {
                input.remove();
            }
        }
    });
}

/**
 * Display error messages to user
 */
function showErrors(errors) {
    console.log('Showing errors:', errors);
    const errorContainer = document.getElementById('error-container');
    if (!errorContainer) {
        console.error('Error container not found');
        alert(errors.join('\n'));
        return;
    }
    
    errorContainer.innerHTML = '';
    errors.forEach(error => {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = error;
        errorContainer.appendChild(errorDiv);
    });
    
    errorContainer.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Download PDF file
 */
function downloadPdf(blob, fileName) {
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = fileName;
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
}

/**
 * Show loading indicator while processing
 */
function showLoadingIndicator() {
    const loader = document.createElement('div');
    loader.className = 'loading-indicator';
    loader.innerHTML = 'Processing your request...';
    document.body.appendChild(loader);
    return loader;
}

/**
 * Hide and remove loading indicator
 */
function hideLoadingIndicator(loader) {
    if (loader && loader.parentElement) {
        loader.parentElement.removeChild(loader);
    }
}

/**
 * Redirect to process page with form data
 */
function redirectToProcess(formData) {
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        params.append(key, value);
    }
    window.location.href = 'process.php?' + params.toString();
}

/**
 * Clear all error messages
 */
function clearErrors() {
    const errorContainer = document.getElementById('error-container');
    if (errorContainer) {
        errorContainer.innerHTML = '';
    }
}

/**
 * Initialize imaging grid functionality
 * From diagnostics.php
 */
function initializeImagingGrid() {
    const expandButton = document.getElementById('expandImaging');
    const imagingGrid = document.querySelector('.imaging-grid');
    
    if (expandButton && imagingGrid) {
        expandButton.addEventListener('click', function() {
            imagingGrid.classList.toggle('collapsed');
            expandButton.textContent = imagingGrid.classList.contains('collapsed') 
                ? 'Add Imaging' 
                : 'Clear Imaging';
                
            if (imagingGrid.classList.contains('collapsed')) {
                clearAllImaging();
            }
        });

        // Initialize type selection handlers
        document.querySelectorAll('[name$="_type"]').forEach(input => {
            input.addEventListener('change', function() {
                const key = this.name.replace('_type', '');
                const sideInputs = document.querySelectorAll(`[name="${key}_side"]`);
                sideInputs.forEach(input => input.disabled = false);
            });
        });
    }
}

/**
 * Initialize signature canvas functionality
 */
function initializeSignatureCanvas() {
    const canvas = document.getElementById("signatureCanvas");
    if (!canvas) return;

    const ctx = canvas.getContext("2d");
    let drawing = false;

    function handleTouch(event) {
        event.preventDefault();
        const touch = event.touches[0];
        const mouseEvent = new MouseEvent("mousemove", {
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        
        if (event.type === "touchstart") {
            startDrawing(mouseEvent);
        } else if (event.type === "touchmove") {
            draw(mouseEvent);
        }
    }

    function startDrawing(event) {
        drawing = true;
        draw(event);
    }

    function stopDrawing() {
        drawing = false;
        ctx.beginPath();
    }

    function draw(event) {
        if (!drawing) return;

        ctx.lineWidth = 2;
        ctx.lineCap = "round";
        ctx.strokeStyle = "black";

        const rect = canvas.getBoundingClientRect();
        const x = event.clientX - rect.left;
        const y = event.clientY - rect.top;

        ctx.lineTo(x, y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(x, y);
    }

    // Drawing event listeners
    canvas.addEventListener("mousedown", startDrawing);
    canvas.addEventListener("mouseup", stopDrawing);
    canvas.addEventListener("mousemove", draw);
    canvas.addEventListener("touchstart", handleTouch);
    canvas.addEventListener("touchend", stopDrawing);
    canvas.addEventListener("touchmove", handleTouch);

    // Button event listeners
    const clearButton = document.getElementById('clearSignatureCanvas');
    if (clearButton) {
        clearButton.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });
    }

    const saveButton = document.getElementById('saveSignatureCanvas');
    if (saveButton) {
        saveButton.addEventListener('click', () => {
            const dataURL = canvas.toDataURL();
            document.getElementById("signatureInput").value = dataURL;
            document.getElementById("signatureForm").submit();
        });
    }
}

/**
 * Initialize braces form handlers
 * From treatment-braces.php
 */
function initializeBracesHandlers() {
    const bracesCheckbox = document.querySelector('input[value="braces"]');
    if (!bracesCheckbox) return;

    const treatmentOptions = bracesCheckbox.closest('.treatment-item').querySelector('.treatment-options');
    
    bracesCheckbox.addEventListener('change', function() {
        treatmentOptions.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            // Reset all selections when main checkbox is unchecked
            resetBraceSelections();
        }
    });

    // Handle knee brace side options
    const kneeBraceCheckbox = document.querySelector('input[name="knee_brace"]');
    if (kneeBraceCheckbox) {
        const kneeSideOptions = kneeBraceCheckbox.closest('.brace-group').querySelector('.side-options');
        
        kneeBraceCheckbox.addEventListener('change', function() {
            kneeSideOptions.style.display = this.checked ? 'block' : 'none';
            if (!this.checked) {
                clearKneeBraceSelections();
            }
        });
    }
}

/**
 * Initialize physical therapy form handlers
 * From treatment-physical-therapy.php
 */
function initializePhysicalTherapyHandlers() {
    const ptCheckbox = document.querySelector('input[value="physical_therapy"]');
    if (!ptCheckbox) return;

    const treatmentOptions = ptCheckbox.closest('.treatment-item').querySelector('.treatment-options');
    
    ptCheckbox.addEventListener('change', function() {
        treatmentOptions.style.display = this.checked ? 'block' : 'none';
    });

    // Handle side options for conditions that require laterality
    const conditionTriggers = document.querySelectorAll('.condition-trigger');
    conditionTriggers.forEach(trigger => {
        trigger.addEventListener('change', function() {
            const sideOptions = this.closest('.condition-group').querySelector('.side-options');
            if (sideOptions) {
                sideOptions.style.display = this.checked ? 'block' : 'none';
                
                if (!this.checked) {
                    clearConditionSideSelections(this);
                }
            }
        });
    });
}

// Helper functions for braces and PT handlers
function resetBraceSelections() {
    document.querySelectorAll('input[name="back_brace_type"]').forEach(radio => {
        if (radio.value === 'none') radio.checked = true;
        else radio.checked = false;
    });
    document.querySelector('input[name="knee_brace"]').checked = false;
    clearKneeBraceSelections();
}

function clearKneeBraceSelections() {
    document.querySelectorAll('input[name="knee_brace_side"]').forEach(radio => {
        radio.checked = false;
    });
}

function clearConditionSideSelections(triggerElement) {
    const radioInputs = triggerElement.closest('.condition-group')
        .querySelectorAll('input[type="radio"]');
    radioInputs.forEach(radio => radio.checked = false);
}