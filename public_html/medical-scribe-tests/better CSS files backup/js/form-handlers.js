/**
 * Form Handler Master Script
 * 
 * Main sections:
 * 1. Core Initialization
 * 2. Form Validation
 * 3. Medication Management 
 * 4. Imaging Controls
 * 5. Provider Validation
 * 6. Signature Canvas
 * 7. Treatment Management (PT & Braces)
 * 8. Qutenza Controls
 * 9. Utility Functions
 */

// ================================
// 1. CORE INITIALIZATION
// ================================

/**
 * Master initialization function called on DOM load
 */
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('radiologyForm');
    if (form) {
        // Remove any existing event listeners
        form.replaceWith(form.cloneNode(true));
        
        // Get the fresh form reference
        const newForm = document.getElementById('radiologyForm');
        
        // Add a simple submit handler
        newForm.addEventListener('submit', async function(event) {
            event.preventDefault();
            console.log('Form submission started');
            
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = true;
            
            try {
                const formData = new FormData(event.target);
                console.log('Submitting form data:', Object.fromEntries(formData));
                
                const response = await fetch("radiology-order.php", {
                    method: "POST",
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Handle successful submission
                console.log('Form submitted successfully');
                window.location.href = 'process.php?' + new URLSearchParams(formData);
                
            } catch (error) {
                console.error('Submission failed:', error);
                alert('Form submission failed: ' + error.message);
            } finally {
                submitButton.disabled = false;
            }
        });
    }
});

/**
 * Initialize form with default values and date constraints
 */
function initializeForm() {
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const todayISO = today.toISOString().split('T')[0];
    
    initializeDOS(today, todayISO);
    initializeDOB(today, todayISO);
    initializeRequiredFields();
    initializeAccessibility();
}

/**
 * Initialize accessibility features
 */
function initializeAccessibility() {
    // Add ARIA labels
    document.querySelectorAll('.form-group').forEach(group => {
        const label = group.querySelector('label');
        const input = group.querySelector('input, select');
        if (label && input && !input.getAttribute('aria-label')) {
            input.setAttribute('aria-label', label.textContent.trim());
        }
    });

    // Add error message regions
    document.querySelectorAll('.error-message').forEach(error => {
        error.setAttribute('role', 'alert');
        error.setAttribute('aria-live', 'polite');
    });
}

/**
 * Initialize Date of Service field
 */
function initializeDOS(today, todayISO) {
    const dosInput = document.getElementById('dos');
    if (!dosInput) return;
    
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
    
    const thirtyDaysAhead = new Date(today);
    thirtyDaysAhead.setDate(thirtyDaysAhead.getDate() + 30);
    
    dosInput.value = todayISO;
    dosInput.min = thirtyDaysAgo.toISOString().split('T')[0];
    dosInput.max = thirtyDaysAhead.toISOString().split('T')[0];
    
    // Add event listeners
    dosInput.addEventListener('change', function() {
        validateDateOfService(this, today, thirtyDaysAgo, thirtyDaysAhead);
    });

    // Add validation on blur
    dosInput.addEventListener('blur', function() {
        if (!this.value) {
            this.value = todayISO;
            validateDateOfService(this, today, thirtyDaysAgo, thirtyDaysAhead);
        }
    });
}

/**
 * Initialize Date of Birth field
 */
function initializeDOB(today, todayISO) {
    const dobInput = document.getElementById('dob');
    if (!dobInput) return;

    // Monitor the input constantly
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' || mutation.type === 'characterData') {
                validateDateOfBirth(dobInput, today);
            }
        });
    });

    observer.observe(dobInput, {
        attributes: true,
        characterData: true,
        subtree: true
    });

    // Add all possible event listeners
    ['input', 'change', 'blur', 'keyup', 'paste'].forEach(eventType => {
        dobInput.addEventListener(eventType, () => {
            validateDateOfBirth(dobInput, today);
        });
    });

    // Also check periodically
    setInterval(() => {
        if (dobInput.value.length === 10) {
            validateDateOfBirth(dobInput, today);
        }
    }, 100);

    // Add a style for the new validation message
    const style = document.createElement('style');
    style.textContent = `
        .year-validation {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }
    `;
    document.head.appendChild(style);
}

/**
 * Attach global event listeners
 */
function attachEventListeners() {
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Clear errors on Escape
        if (e.key === 'Escape') {
            clearErrors();
        }
        
        // Submit form on Ctrl+Enter or Cmd+Enter
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const submitButton = document.getElementById('submitButton');
            if (submitButton && !submitButton.disabled) {
                submitButton.click();
            }
        }
    });

    // Auto-save form data
    const formInputs = document.querySelectorAll('input, select, textarea');
    formInputs.forEach(input => {
        input.addEventListener('change', debounce(() => {
            saveFormState();
        }, 1000));
    });
}

/**
 * Save form state to localStorage
 */
function saveFormState() {
    const form = document.getElementById('radiologyForm');
    if (!form) return;

    const formData = new FormData(form);
    const formState = {};

    for (const [key, value] of formData.entries()) {
        formState[key] = value;
    }

    try {
        localStorage.setItem('formState', JSON.stringify(formState));
        console.log('Form state saved');
    } catch (error) {
        console.error('Error saving form state:', error);
    }
}

/**
 * Restore form state from localStorage
 */
function restoreFormState() {
    try {
        const savedState = localStorage.getItem('formState');
        if (savedState) {
            const formState = JSON.parse(savedState);
            const form = document.getElementById('radiologyForm');
            
            if (form) {
                Object.entries(formState).forEach(([key, value]) => {
                    const input = form.elements[key];
                    if (input) {
                        input.value = value;
                    }
                });
            }
        }
    } catch (error) {
        console.error('Error restoring form state:', error);
    }
}

// ================================
// 2. FORM VALIDATION
// ================================

/**
 * Creates a debounced version of a function
 * @param {Function} func - Function to debounce
 * @param {number} wait - Milliseconds to wait between calls
 * @returns {Function} Debounced function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const form = document.getElementById('radiologyForm');
    if (!form) {
        console.error('Form not found');
        return;
    }

    // Reset error states
    document.querySelectorAll('.error-message').forEach(errorDiv => {
        errorDiv.style.display = 'none';
    });
    
    document.querySelectorAll('.form-group').forEach(group => {
        group.setAttribute('aria-invalid', 'false');
    });

    const requiredInputs = form.querySelectorAll('input[required]:not([id="provider_validation"])');
    setupRequiredFieldValidation(requiredInputs);
    setupPatientNameValidation(form);
}

/**
 * Setup validation for required fields
 */
function setupRequiredFieldValidation(requiredInputs) {
    requiredInputs.forEach(input => {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;
        
        const label = formGroup.querySelector('label');
        const fieldName = label ? 
            label.textContent.replace(':', '').replace('*', '').trim() : 
            input.id;
        
        setupFieldValidation(input, fieldName);
    });
}

/**
 * Setup validation for individual field with debouncing
 */
function setupFieldValidation(element, fieldLabel) {
    const formGroup = element.closest('.form-group');
    if (!formGroup) return;

    let errorDiv = formGroup.querySelector('.error-message');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        formGroup.appendChild(errorDiv);
    }

    // Create debounced version of validateField
    const debouncedValidation = debounce(
        () => validateField(element, fieldLabel, formGroup, errorDiv),
        300
    );

    // Use debounced validation for input events
    element.addEventListener('input', debouncedValidation);
    
    // Use immediate validation for blur events
    element.addEventListener('blur', () => 
        validateField(element, fieldLabel, formGroup, errorDiv)
    );
}

/**
 * Setup patient name validation
 */
function setupPatientNameValidation(form) {
    const patientNameInput = form.querySelector('#patient_name');
    if (!patientNameInput) return;

    const formGroup = patientNameInput.closest('.form-group');
    const errorDiv = formGroup?.querySelector('.error-message');
    
    if (!formGroup || !errorDiv) return;

    // Create debounced version of name validation
    const debouncedNameValidation = debounce(() => {
        validatePatientName(patientNameInput, formGroup, errorDiv);
    }, 300);

    patientNameInput.addEventListener('input', debouncedNameValidation);
    patientNameInput.addEventListener('blur', () => {
        validatePatientName(patientNameInput, formGroup, errorDiv);
    });
}

/**
 * Validate patient name
 */
function validatePatientName(input, formGroup, errorDiv) {
    const value = input.value.trim();
    let isValid = true;
    let errorMessage = '';

    if (value === '') {
        isValid = false;
        errorMessage = 'Patient Name is required';
    } else if (!/^[A-Za-z\s-']+$/.test(value)) {
        isValid = false;
        errorMessage = 'Patient Name can only contain letters, spaces, hyphens, and apostrophes';
    } else if (value.split(' ').length < 2) {
        isValid = false;
        errorMessage = 'Please enter both first and last name';
    }

    updateFieldValidationState(formGroup, input, errorDiv, isValid, errorMessage);
    return isValid;
}

/**
 * Validate Date of Birth
 */
function validateDateOfBirth(input, today) {
    const value = input.value;
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup?.querySelector('.error-message');

    // Create or get a persistent year validation result div
    let yearValidationDiv = formGroup?.querySelector('.year-validation');
    if (!yearValidationDiv) {
        yearValidationDiv = document.createElement('div');
        yearValidationDiv.className = 'year-validation error-message';
        formGroup?.appendChild(yearValidationDiv);
    }

    // If we have a complete date, check the year separately
    if (value && value.length === 10) {
        const year = parseInt(value.substring(0, 4));
        if (year < 1900) {
            // Always show year error if year < 1900
            yearValidationDiv.style.display = 'block';
            yearValidationDiv.textContent = 'Year must be 1900 or later';
            input.classList.add('input-error');
            formGroup?.setAttribute('aria-invalid', 'true');
            // Prevent form submission
            input.setCustomValidity('Year must be 1900 or later');
            return false;
        } else {
            yearValidationDiv.style.display = 'none';
            input.classList.remove('input-error');
            formGroup?.setAttribute('aria-invalid', 'false');
            input.setCustomValidity('');
        }
    }

    // Regular validation logic
    let isValid = true;
    let errorMessage = '';

    if (!value) {
        isValid = false;
        errorMessage = 'Date of Birth is required';
    } else {
        const selectedDate = new Date(value);
        if (selectedDate > today) {
            isValid = false;
            errorMessage = 'Date of Birth cannot be in the future';
        } else {
            const dosInput = document.getElementById('dos');
            if (dosInput && dosInput.value) {
                if (!validateDatesRelationship(input, dosInput)) {
                    isValid = false;
                    errorMessage = 'Date of Birth must be before Date of Service';
                }
            }
        }
    }

    // Update regular validation state
    if (errorDiv) {
        errorDiv.style.display = isValid ? 'none' : 'block';
        errorDiv.textContent = errorMessage;
    }

    return isValid;
}

function initializeDOB(today, todayISO) {
    const dobInput = document.getElementById('dob');
    if (!dobInput) return;
    
    // Add multiple event listeners to catch all changes
    ['input', 'change', 'blur', 'keyup'].forEach(eventType => {
        dobInput.addEventListener(eventType, () => {
            validateDateOfBirth(dobInput, today);
        });
    });
}

/**
 * Validate relationships between dates
 */
function validateDatesRelationship(dob, dos) {
    const dobDate = new Date(dob.value);
    const dosDate = new Date(dos.value);
    
    // Set times to midnight to compare dates only
    dobDate.setHours(0, 0, 0, 0);
    dosDate.setHours(0, 0, 0, 0);
    
    return dobDate < dosDate;
}

// Add this function for date of service validation
function validateDateOfService(input, today, thirtyDaysAgo, thirtyDaysAhead) {
    const selectedDate = new Date(input.value);
    selectedDate.setHours(0, 0, 0, 0);
    
    const formGroup = input.closest('.form-group');
    const errorDiv = formGroup?.querySelector('.error-message');
    
    let isValid = true;
    let errorMessage = '';

    if (selectedDate < thirtyDaysAgo || selectedDate > thirtyDaysAhead) {
        isValid = false;
        errorMessage = 'Date of Service must be within 30 days of today';
    }

    updateFieldValidationState(formGroup, input, errorDiv, isValid, errorMessage);
    return isValid;
}

// Add this function for required fields initialization
function initializeRequiredFields() {
    const form = document.getElementById('radiologyForm');
    if (!form) return;

    // Add required attribute to necessary fields
    const requiredFields = [
        'patient_name',
        'dob',
        'dos'
    ];

    requiredFields.forEach(fieldId => {
        const input = form.querySelector(`#${fieldId}`);
        if (input) {
            input.required = true;
            
            // Add visual indicator for required fields
            const label = input.closest('.form-group')?.querySelector('label');
            if (label && !label.textContent.includes('*')) {
                label.textContent += ' *';
            }
        }
    });
}

/**
 * Validate individual field
 */
function validateField(element, fieldLabel, formGroup, errorDiv) {
    const value = element.value.trim();
    const isValid = value !== '';
    
    formGroup.setAttribute('aria-invalid', (!isValid).toString());
    element.classList.toggle('input-error', !isValid);
    errorDiv.style.display = isValid ? 'none' : 'block';
    errorDiv.textContent = isValid ? '' : `${fieldLabel} is required`;
    
    return isValid;
}

/**
 * Update field validation state
 */
function updateFieldValidationState(formGroup, input, errorDiv, isValid, errorMessage = '') {
    if (formGroup) {
        formGroup.setAttribute('aria-invalid', (!isValid).toString());
        input.classList.toggle('input-error', !isValid);
        
        if (errorDiv) {
            errorDiv.style.display = isValid ? 'none' : 'block';
            if (errorMessage) {
                errorDiv.textContent = errorMessage;
            }
        }
    }
}

async function handleFormSubmit(event) {
    event.preventDefault();
    console.log('Form submit handler triggered');
    const submitButton = document.getElementById('submitButton');
    
    try {
        console.log('Attempting form submission...');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        const formData = new FormData(event.target);
        console.log('Form data:', Object.fromEntries(formData));
        
        await submitFormData(formData);
        
    } catch (error) {
        console.error('Submission error:', error);
        showErrors([error.message]);
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Submit';
    }
}

async function submitFormData(formData) {
    const response = await fetch("radiology-order.php", {
        method: "POST",
        body: formData
    });

    if (!response.ok) {
        throw new Error(`Server error: ${response.status}`);
    }

    const contentType = response.headers.get("content-type");
    
    // Handle JSON response indicating no PDF
    if (contentType && contentType.includes('application/json')) {
        const jsonResponse = await response.json();
        if (jsonResponse.noPdf) {
            redirectToProcess(formData);
            return;
        }
    }
    
    // Handle PDF response
    if (contentType && contentType.includes('application/pdf')) {
        const blob = await response.blob();
        if (blob.size === 0) {
            throw new Error('Received empty response from server');
        }
        
        const patientName = formData.get('patient_name');
        downloadPdf(blob, `${formatNameForFileName(patientName)} radiology order.pdf`);
    }
    
    // Redirect after successful submission
    redirectToProcess(formData);
}

function redirectToProcess(formData) {
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
        params.append(key, value);
    }
    window.location.href = 'process.php?' + params.toString();
}

// ================================
// 3. MEDICATION MANAGEMENT
// ================================

const MEDICATION_FREQUENCIES = {
    'qday': 'Once daily',
    'bid': 'Twice daily',
    'tid': 'Three times daily',
    'qhs': 'At bedtime'
};

/**
 * Initialize medication controls
 */
function initializeMedicationControls() {
    hideAllDosageOptions();
    disableAllDosageInputs();
    attachMedicationListeners();
}

/**
 * Hide all dosage options
 */
function hideAllDosageOptions() {
    document.querySelectorAll('.dosage-options').forEach(options => {
        options.style.display = 'none';
    });
}

/**
 * Disable all dosage inputs
 */
function disableAllDosageInputs() {
    document.querySelectorAll('.dosage-options input[type="radio"]').forEach(input => {
        input.disabled = true;
    });
}

/**
 * Handle medication selection
 * @param {HTMLInputElement} input - The selected medication input
 * @param {string} type - Medication type ('NSAID', 'mrelaxer', or 'nerve_agent')
 */
function handleMedicationSelection(input, type) {
    const medicationItem = input.closest('.medication-item');
    if (!medicationItem) return;
    
    const dosageOptions = medicationItem.querySelector('.dosage-options');
    if (!dosageOptions) return;
    
    // Show/hide dosage options based on selection
    if (input.checked && input.value !== 'none') {
        // Show dosage options
        dosageOptions.style.display = 'block';
        
        // Enable radio buttons
        dosageOptions.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.disabled = false;
        });
        
        // Clear any previous selection
        dosageOptions.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
            radio.checked = false;
        });
    } else {
        // Hide dosage options
        dosageOptions.style.display = 'none';
        
        // Disable and clear radio buttons
        dosageOptions.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.disabled = true;
            radio.checked = false;
        });
    }
}

/**
 * Attach medication listeners
 */
function attachMedicationListeners() {
    ['NSAID', 'mrelaxer', 'nerve_agent'].forEach(type => {
        document.querySelectorAll(`input[name="${type}"]`).forEach(radio => {
            radio.addEventListener('change', function() {
                handleMedicationSelection(this, type);
            });
        });
    });
}

// ================================
// 4. IMAGING CONTROLS
// ================================

/**
 * Initialize imaging handlers
 */
function initializeImagingHandlers() {
    initializeClearButton();
    initializeBilateralHandlers();
    initializeSpineHandlers();
}

/**
 * Initialize clear button functionality
 */
function initializeClearButton() {
    const clearButton = document.getElementById('clearAllImaging');
    if (clearButton) {
        clearButton.addEventListener('click', clearAllImaging);
    }
}

/**
 * Initialize bilateral imaging handlers
 */
function initializeBilateralHandlers() {
    const bilateralParts = ['shoulder', 'hip', 'SIJ', 'knee'];
    bilateralParts.forEach(part => {
        initializeTypeHandlers(part);
        initializeSideHandlers(part);
    });
}

/**
 * Initialize type handlers for bilateral imaging
 * @param {string} part - Body part name (e.g., 'shoulder', 'hip', 'SIJ', 'knee')
 */
function initializeTypeHandlers(part) {
    // Handle X-ray type selection
    document.querySelectorAll(`input[name="${part}_xray"]`).forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                handleImagingSelection(part, 'xray', this.value.toLowerCase());
            }
        });
    });

    // Handle MRI type selection
    document.querySelectorAll(`input[name="${part}_mri"]`).forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                handleImagingSelection(part, 'mri', this.value.toLowerCase());
            }
        });
    });
}

/**
 * Initialize side handlers for bilateral imaging
 * @param {string} part - Body part name (e.g., 'shoulder', 'hip', 'SIJ', 'knee')
 */
function initializeSideHandlers(part) {
    document.querySelectorAll(`input[name="${part}_side"]`).forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                const selectedType = document.querySelector(`input[name="${part}_type"]:checked`)?.value;
                if (selectedType) {
                    handleImagingSelection(part, selectedType, this.value.toLowerCase());
                }
            }
        });
    });
}

/**
 * Initialize spine imaging handlers
 */
function initializeSpineHandlers() {
    ['cervical', 'thoracic', 'lumbar'].forEach(part => {
        document.querySelectorAll(`input[name="${part}_spine"]`).forEach(input => {
            input.addEventListener('change', function() {
                if (this.checked) {
                    updateSpineImagingSelection(part, this.value);
                }
            });
        });
    });
}

/**
 * Initialize imaging grid
 */
function initializeImagingGrid() {
    const toggleButton = document.getElementById('toggleImaging');
    const imagingContent = document.getElementById('imagingContent');
    
    if (toggleButton && imagingContent) {
        setupImagingToggle(toggleButton, imagingContent);
        initializeTypeSelectionHandlers();
    }
}

/**
 * Setup imaging toggle
 */
function setupImagingToggle(toggleButton, imagingContent) {
    toggleButton.addEventListener('click', function() {
        imagingContent.classList.toggle('collapsed');
        this.textContent = imagingContent.classList.contains('collapsed') 
            ? 'No Imaging' 
            : 'Clear Imaging';
        
        if (imagingContent.classList.contains('collapsed')) {
            clearAllImaging();
        }
    });
}

/**
 * Handle imaging selection
 */
function handleImagingSelection(part, type, side) {
    const oppositeType = type === 'xray' ? 'mri' : 'xray';
    
    if (side === 'bilateral') {
        clearOppositeTypeSelections(part, oppositeType);
        return;
    }
    
    if (side === 'left' || side === 'right') {
        handleSingleSideSelection(part, type, side, oppositeType);
    }
}

/**
 * Clear opposite type selections for bilateral imaging
 */
function clearOppositeTypeSelections(part, oppositeType) {
    document.querySelectorAll(`input[name="${part}_${oppositeType}"]`).forEach(input => {
        input.checked = false;
    });
}

/**
 * Handle single side selection logic
 */
function handleSingleSideSelection(part, type, side, oppositeType) {
    // Clear opposite type on same side
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

/**
 * Clear all imaging selections
 */
function clearAllImaging() {
    console.log('Starting clearAllImaging function');
    
    clearVisibleImagingInputs();
    clearBilateralImagingParts();
    clearSpineImagingParts();
    cleanupHiddenImagingInputs();
    
    verifyImagingCleanup();
}

// ================================
// 5. PROVIDER VALIDATION
// ================================

/**
 * Initialize provider validation
 */
function initializeProviderValidation() {
    const providerInputs = document.querySelectorAll('input[name="provider_name"]');
    const providerGrid = document.querySelector('.provider-grid');
    const validationInput = document.getElementById('provider_validation');
    const errorMessage = document.querySelector('.provider-grid + .error-message');
    
    if (!providerGrid || !validationInput || !errorMessage) {
        console.error('Provider validation elements not found');
        return;
    }
    
    // Set initial state
    providerGrid.setAttribute('aria-invalid', 'false');
    validationInput.required = true;
    errorMessage.style.display = 'none';
    
    // Handle provider selection
    providerInputs.forEach(input => {
        input.addEventListener('change', () => {
            if (input.checked) {
                handleProviderSelection(input.value, providerGrid, validationInput, errorMessage);
            }
        });
    });
    
    // Handle form validation
    document.querySelector('form').addEventListener('invalid', (e) => {
        if (e.target === validationInput) {
            handleInvalidProvider(e, providerGrid, errorMessage);
        }
    }, true);
    
    // Add form submit handler
    document.querySelector('form').addEventListener('submit', (e) => {
        if (!validationInput.value) {
            e.preventDefault();
            handleInvalidProvider(new Event('invalid'), providerGrid, errorMessage);
        }
    });
}

/**
 * Handle provider selection
 */
function handleProviderSelection(value, providerGrid, validationInput, errorMessage) {
    validationInput.value = value;
    providerGrid.setAttribute('aria-invalid', 'false');
    providerGrid.classList.remove('provider-grid-error');
    errorMessage.style.display = 'none';
}

/**
 * Handle invalid provider selection
 */
function handleInvalidProvider(e, providerGrid, errorMessage) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    providerGrid.setAttribute('aria-invalid', 'true');
    providerGrid.classList.add('provider-grid-error');
    errorMessage.style.display = 'block';
    providerGrid.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ================================
// 6. SIGNATURE CANVAS
// ================================

/**
 * Initialize signature canvas
 */
function initializeSignatureCanvas() {
    console.log('Initializing signature canvas');
    
    const canvas = document.getElementById("signatureCanvas");
    if (!canvas) {
        console.error('Canvas element not found');
        return;
    }
    
    const ctx = canvas.getContext("2d");
    if (!ctx) {
        console.error('Could not get canvas context');
        return;
    }
    
    let drawing = false;
    
    setupCanvasEventListeners(canvas, ctx, drawing);
    setupCanvasButtons(canvas, ctx);
}

/**
 * Setup canvas event listeners
 */
function setupCanvasEventListeners(canvas, ctx, drawing) {
    canvas.addEventListener("mousedown", e => startDrawing(e, ctx));
    canvas.addEventListener("mouseup", () => stopDrawing(ctx));
    canvas.addEventListener("mousemove", e => draw(e, canvas, ctx));
    
    // Touch events
    canvas.addEventListener("touchstart", e => handleTouch(e, ctx));
    canvas.addEventListener("touchend", () => stopDrawing(ctx));
    canvas.addEventListener("touchmove", e => handleTouch(e, ctx));
}

/**
 * Start drawing on canvas
 */
function startDrawing(event, ctx) {
    ctx.beginPath();
    draw(event, ctx);
}

/**
 * Stop drawing on canvas
 */
function stopDrawing(ctx) {
    ctx.beginPath();
}

/**
 * Draw on canvas
 */
function draw(event, canvas, ctx) {
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    
    ctx.lineWidth = 2;
    ctx.lineCap = "round";
    ctx.strokeStyle = "black";
    ctx.lineTo(x, y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(x, y);
}

/**
 * Handle touch events for canvas
 */
function handleTouch(event, ctx) {
    event.preventDefault();
    const touch = event.touches[0];
    const mouseEvent = new MouseEvent("mousemove", {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    
    if (event.type === "touchstart") {
        startDrawing(mouseEvent, ctx);
    } else if (event.type === "touchmove") {
        draw(mouseEvent, ctx);
    }
}

/**
 * Setup canvas buttons
 */
function setupCanvasButtons(canvas, ctx) {
    setupClearButton(canvas, ctx);
    setupSaveButton(canvas);
}

/**
 * Setup clear button
 */
function setupClearButton(canvas, ctx) {
    const clearButton = document.getElementById('clearSignatureCanvas');
    if (clearButton) {
        clearButton.addEventListener('click', () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });
    }
}

/**
 * Setup save button
 */
function setupSaveButton(canvas) {
    const saveButton = document.getElementById('saveSignatureCanvas');
    if (saveButton) {
        saveButton.addEventListener('click', () => {
            const dataURL = canvas.toDataURL();
            document.getElementById("signatureInput").value = dataURL;
            document.getElementById("signatureForm").submit();
        });
    }
}

// ================================
// 7. TREATMENT MANAGEMENT
// ================================

/**
 * Initialize braces form handlers
 */
function initializeBracesHandlers() {
    const bracesCheckbox = document.querySelector('input[value="braces"]');
    if (!bracesCheckbox) {
        console.log('Braces checkbox not found');
        return;
    }
    
    setupBracesEventListeners(bracesCheckbox);
    setupKneeBraceHandlers();
}

/**
 * Setup braces event listeners
 */
function setupBracesEventListeners(bracesCheckbox) {
    const treatmentOptions = bracesCheckbox.closest('.treatment-item')?.querySelector('.treatment-options');
    if (!treatmentOptions) {
        console.error('Treatment options container not found');
        return;
    }
    
    bracesCheckbox.addEventListener('change', function() {
        treatmentOptions.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            resetBraceSelections();
        }
    });
}

/**
 * Setup knee brace handlers
 */
function setupKneeBraceHandlers() {
    const kneeBraceCheckbox = document.querySelector('input[name="knee_brace"]');
    if (!kneeBraceCheckbox) {
        console.log('Knee brace checkbox not found');
        return;
    }
    
    const kneeSideOptions = kneeBraceCheckbox.closest('.brace-group')?.querySelector('.side-options');
    if (!kneeSideOptions) {
        console.error('Knee side options container not found');
        return;
    }
    
    kneeBraceCheckbox.addEventListener('change', function() {
        kneeSideOptions.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            clearKneeBraceSelections();
        }
    });
}

/**
 * Initialize physical therapy handlers
 */
function initializePhysicalTherapyHandlers() {
    const ptCheckbox = document.querySelector('input[value="physical_therapy"]');
    if (!ptCheckbox) {
        console.log('Physical therapy checkbox not found');
        return;
    }
    
    setupPTEventListeners(ptCheckbox);
    setupConditionHandlers();
}

/**
 * Setup physical therapy event listeners
 */
function setupPTEventListeners(ptCheckbox) {
    const treatmentOptions = ptCheckbox.closest('.treatment-item')?.querySelector('.treatment-options');
    if (!treatmentOptions) {
        console.error('PT treatment options container not found');
        return;
    }
    
    ptCheckbox.addEventListener('change', function() {
        treatmentOptions.style.display = this.checked ? 'block' : 'none';
        if (!this.checked) {
            clearAllPTSelections();
        }
    });
}

// ================================
// 8. QUTENZA CONTROLS
// ================================

/**
 * Initialize Qutenza controls
 */
function initializeQutenzaControls() {
    const button = document.getElementById('toggleQutenza');
    const content = document.getElementById('qutenzaContent');
    if (!button || !content) {
        console.error('Qutenza elements not found');
        return;
    }
    
    // Set initial state
    content.classList.add('collapsed');
    disableQutenzaInputs();
    
    button.addEventListener('click', function() {
        const isExpanding = content.classList.contains('collapsed');
        content.classList.toggle('collapsed');
        this.textContent = isExpanding ? 'Clear Qutenza' : 'Add Qutenza';
        this.classList.toggle('active', isExpanding);
        
        if (!isExpanding) {
            clearQutenzaSelections();
            disableQutenzaInputs();
        } else {
            enableQutenzaAreaInputs();
        }
    });
    
    // Setup area selection handlers
    setupQutenzaAreaHandlers();
}

/**
 * Setup Qutenza area handlers
 */
function setupQutenzaAreaHandlers() {
    document.querySelectorAll('input[name="qutenza_area"]').forEach(input => {
        input.addEventListener('change', function() {
            handleQutenzaAreaSelection(this);
        });
    });
}

/**
 * Handle Qutenza area selection
 */
function handleQutenzaAreaSelection(input) {
    // Hide all dosage options first
    document.querySelectorAll('.qutenza-options .dosage-options').forEach(options => {
        options.style.display = 'none';
        options.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.disabled = true;
            radio.checked = false;
        });
    });
    
    // If an area is selected (not the "No Qutenza Treatment" option)
    if (input.checked && input.value !== '') {
        const medicationItem = input.closest('.medication-item');
        const dosageOptions = medicationItem?.querySelector('.dosage-options');
        if (dosageOptions) {
            dosageOptions.style.display = 'block';
            dosageOptions.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.disabled = false;
            });
        }
    }
}

// ================================
// 9. UTILITY FUNCTIONS
// ================================

/**
 * Format date string
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
 * Format patient name for file name
 */
function formatNameForFileName(fullName) {
    const nameParts = fullName.trim().split(' ');
    if (nameParts.length < 2) return fullName;
    
    const firstName = nameParts[0];
    const lastName = nameParts.slice(1).join(' ');
    return `${lastName}, ${firstName}`;
}

/**
 * Show error messages
 */
function showErrors(errors) {
    console.log('Showing errors:', errors);
    const errorContainer = document.getElementById('error-container');
    
    if (!errorContainer) {
        console.error('Error container not found');
        alert(errors.join('\n'));
        return;
    }
    
    // Clear existing errors
    errorContainer.innerHTML = '';
    
    // Create error message elements
    errors.forEach(error => {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.style.display = 'block'; // Ensure error is visible
        errorDiv.textContent = error;
        errorContainer.appendChild(errorDiv);
    });
    
    // Make sure error container is visible
    errorContainer.style.display = 'block';
    errorContainer.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Clear error messages
 */
function clearErrors() {
    const errorContainer = document.getElementById('error-container');
    if (errorContainer) {
        errorContainer.innerHTML = '';
    }
}

/**
 * Show loading indicator
 */
function showLoadingIndicator() {
    const loader = createElement('div', {
        className: 'loading-indicator',
        innerHTML: 'Processing your request...'
    });
    document.body.appendChild(loader);
    return loader;
}

/**
 * Hide loading indicator
 */
function hideLoadingIndicator(loader) {
    if (loader?.parentElement) {
        loader.parentElement.removeChild(loader);
    }
}

/**
 * Create DOM element with attributes
 */
function createElement(tag, attributes = {}, parent = null) {
    const element = document.createElement(tag);
    Object.entries(attributes).forEach(([key, value]) => {
        element[key] = value;
    });
    if (parent) {
        parent.appendChild(element);
    }
    return element;
}

/**
 * Download PDF file
 */
function downloadPdf(blob, fileName) {
    const url = window.URL.createObjectURL(blob);
    const link = createElement('a', {
        href: url,
        download: fileName
    }, document.body);
    
    link.click();
    document.body.removeChild(link);
    window.URL.revokeObjectURL(url);
}

/**
 * Log form data entries for debugging
 */
function logFormData(formData, context = '') {
    console.group(context || 'Form Data Entries');
    for (const [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    console.groupEnd();
}

window.FormHandlers = {
    // Core initialization
    initializeForm,
    initializeFormValidation,
    initializeFormSubmission,
    
    // Feature initialization
    initializeMedicationControls,
    initializeImagingHandlers,
    initializeProviderValidation,
    initializeSignatureCanvas,
    initializeBracesHandlers,
    initializePhysicalTherapyHandlers,
    initializeQutenzaControls,
    
    // Form validation
    validateField,
    validateDateOfService,
    validateDateOfBirth,
    
    // Utility functions
    formatDate,
    formatNameForFileName,
    showErrors,
    clearErrors,
    showLoadingIndicator,
    hideLoadingIndicator,
    createElement,
    downloadPdf,
    logFormData
};