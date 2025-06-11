// ============================================
// GMPM Application JavaScript - Fully Consolidated
// Generated: $(date +"%Y-%m-%d %H:%M:%S")
// ============================================

// Namespace for GMPM application
window.GMPM = window.GMPM || {};

// ============================================
// Utility Functions
// ============================================
GMPM.Utils = {
    // Show alert message
    showAlert: function(container, type, message, duration = 5000) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const icons = {
            'error': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            'success': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            'info': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>'
        };
        
        alertDiv.innerHTML = `${icons[type] || ''} ${message}`;
        
        container.innerHTML = '';
        container.appendChild(alertDiv);
        
        if (duration > 0) {
            setTimeout(() => {
                alertDiv.remove();
            }, duration);
        }
    }
};

// Initialize application
document.addEventListener('DOMContentLoaded', function() {
    console.log('GMPM Application Initialized');
    
    // Initialize specific modules based on page elements
    initializeModules();
});

// Module initialization
function initializeModules() {
    // Phone Note Form Module
    if (document.getElementById('phoneNoteForm')) {
        initializePhoneNoteForm();
    }
    
    // IT Support Form Module
    if (document.getElementById('supportForm')) {
        initializeITSupportForm();
    }
    
    // IP Manager Module
    if (document.getElementById('ipForm')) {
        initializeIPManager();
    }
    
    // Print Handler Module
    if (document.getElementById('printButton') || document.querySelector('.print-container')) {
        initializePrintHandler();
    }
}

// ============================================
// Phone Note Form Module
// ============================================
function initializePhoneNoteForm() {
    const form = document.getElementById('phoneNoteForm');
    const phoneInput = document.getElementById('phone');
    const phonePreview = document.getElementById('phonePreview');
    const callerNameInput = document.getElementById('caller_name');
    const hipaaWarning = document.getElementById('hipaaWarning');
    const lastSeenInput = document.getElementById('last_seen');
    const upcomingInput = document.getElementById('upcoming');
    const appointmentInfo = document.getElementById('appointmentInfo');
    const textarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const alertContainer = document.getElementById('alertContainer');

    if (!form) return;

    // Phone number formatting
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.substring(0, 10);
            }
            this.value = value;
            
            if (phonePreview) {
                if (value.length === 10) {
                    const formatted = `(${value.substring(0,3)}) ${value.substring(3,6)}-${value.substring(6)}`;
                    phonePreview.textContent = `Preview: ${formatted}`;
                    phonePreview.style.color = 'var(--success-color)';
                    this.classList.remove('error');
                } else if (value.length > 0) {
                    phonePreview.textContent = `${value.length}/10 digits entered`;
                    phonePreview.style.color = 'var(--text-secondary)';
                } else {
                    phonePreview.textContent = '';
                }
            }
        });
    }

    // HIPAA warning for third-party callers
    if (callerNameInput && hipaaWarning) {
        callerNameInput.addEventListener('input', function() {
            if (this.value.trim()) {
                hipaaWarning.style.display = 'flex';
                showAlert('info', 'HIPAA Notice: When a third party is calling, ensure they are authorized to receive patient information.');
            } else {
                hipaaWarning.style.display = 'none';
            }
        });
    }

    // Character counter for description
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count} / 2000`;
            
            if (count > 1800) {
                charCount.style.color = 'var(--error-color)';
            } else if (count > 1500) {
                charCount.style.color = 'var(--warning-color)';
            } else {
                charCount.style.color = 'var(--text-secondary)';
            }
        });
    }

    // Form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });
        
        // Phone number validation
        if (phoneInput) {
            const phone = phoneInput.value;
            if (phone.length !== 10) {
                phoneInput.classList.add('error');
                isValid = false;
                showAlert('error', 'Please enter a valid 10-digit phone number.');
            }
        }
        
        return isValid;
    }

    // Show alert message
    function showAlert(type, message) {
        if (alertContainer) {
            GMPM.Utils.showAlert(alertContainer, type, message);
        }
    }

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            showAlert('error', 'Please fill in all required fields correctly.');
            
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
}

// ============================================
// IT Support Form Module
// ============================================
function initializeITSupportForm() {
    const form = document.getElementById('supportForm');
    const nameInput = document.getElementById('name');
    const locationSelect = document.getElementById('location');
    const categoryInputs = document.querySelectorAll('input[name="category"]');
    const priorityInputs = document.querySelectorAll('input[name="priority"]');
    const descriptionTextarea = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const alertContainer = document.getElementById('alertContainer');

    if (!form) return;

    // Character counter
    if (descriptionTextarea && charCount) {
        descriptionTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = `${count} / 2000`;
            
            if (count > 1800) {
                charCount.style.color = 'var(--error-color)';
            } else if (count > 1500) {
                charCount.style.color = 'var(--warning-color)';
            } else {
                charCount.style.color = 'var(--text-secondary)';
            }
        });
    }

    // Form validation
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Name validation
        if (nameInput && !nameInput.value.trim()) {
            nameInput.classList.add('error');
            const nameError = document.getElementById('nameError');
            if (nameError) {
                nameError.textContent = 'Name is required';
                nameError.style.display = 'block';
            }
            errors.push('Please enter your name');
            isValid = false;
        }

        // Location validation
        if (locationSelect && !locationSelect.value) {
            locationSelect.classList.add('error');
            const locationError = document.getElementById('locationError');
            if (locationError) {
                locationError.textContent = 'Please select a location';
                locationError.style.display = 'block';
            }
            errors.push('Please select your office location');
            isValid = false;
        }

        // Category validation
        const categorySelected = Array.from(categoryInputs).some(input => input.checked);
        if (!categorySelected) {
            const categoryError = document.getElementById('categoryError');
            if (categoryError) {
                categoryError.textContent = 'Please select an issue category';
                categoryError.style.display = 'block';
            }
            errors.push('Please select an issue category');
            isValid = false;
        }

        // Description validation
        if (descriptionTextarea && !descriptionTextarea.value.trim()) {
            descriptionTextarea.classList.add('error');
            const descriptionError = document.getElementById('descriptionError');
            if (descriptionError) {
                descriptionError.textContent = 'Description is required';
                descriptionError.style.display = 'block';
            }
            errors.push('Please describe your issue');
            isValid = false;
        }

        return { isValid, errors };
    }

    // Show alert message
    function showAlert(type, message) {
        if (alertContainer) {
            GMPM.Utils.showAlert(alertContainer, type, message);
        }
    }

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const validation = validateForm();
        if (!validation.isValid) {
            showAlert('error', 'Please correct the following errors: ' + validation.errors.join(', '));
            
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return;
        }

        // Show loading state
        if (submitBtn) submitBtn.disabled = true;
        if (btnText) btnText.style.display = 'none';
        if (btnSpinner) btnSpinner.style.display = 'inline-block';

        // Form submission will be handled by the form action
        form.submit();
    });
}

// ============================================
// IP Manager Module
// ============================================
function initializeIPManager() {
    window.ipCount = document.querySelectorAll('.ip-row').length || 0;
    
    // IP validation function
    window.validateIPAddress = function(ip) {
        const ipRegex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        return ipRegex.test(ip);
    };
    
    // Add IP function
    window.addIP = function() {
        window.ipCount++;
        const ipList = document.getElementById('ipList');
        if (!ipList) return;
        
        const newRow = document.createElement('div');
        newRow.className = 'ip-row';
        newRow.innerHTML = `
            <span class="row-number">#${window.ipCount}</span>
            <input type="text" name="ips[]" class="form-input ip-input" 
                placeholder="IP Address (e.g., 192.168.1.1)" required maxlength="15">
            <input type="text" name="locations[]" class="form-input" 
                placeholder="Location/Office Name" required maxlength="100">
            <button type="button" class="remove-btn" onclick="removeIP(this)">Remove</button>
        `;
        ipList.appendChild(newRow);
        
        const newIpInput = newRow.querySelector('input[name="ips[]"]');
        if (newIpInput) {
            newIpInput.focus();
            newIpInput.addEventListener('blur', function() {
                validateIP(this);
            });
        }
    };
    
    // Remove IP function
    window.removeIP = function(button) {
        const ipRows = document.querySelectorAll('.ip-row');
        if (ipRows.length <= 1) {
            alert('You must keep at least one IP address');
            return;
        }
        
        if (confirm('Remove this IP address?')) {
            button.closest('.ip-row').remove();
            updateRowNumbers();
        }
    };
    
    // Update row numbers
    window.updateRowNumbers = function() {
        const rows = document.querySelectorAll('.ip-row');
        rows.forEach((row, index) => {
            const number = row.querySelector('.row-number');
            if (number) {
                number.textContent = `#${index + 1}`;
            }
        });
        window.ipCount = rows.length;
    };
    
    // Validate IP function
    window.validateIP = function(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('ipError');
        
        if (value && !validateIPAddress(value)) {
            input.classList.add('error');
            if (errorDiv) {
                errorDiv.textContent = `Invalid IP address: ${value}`;
                errorDiv.style.display = 'block';
            }
        } else {
            input.classList.remove('error');
        }
    };
    
    // Add button event listener
    const addBtn = document.getElementById('addIPBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            addIP();
        });
    }
}

// ============================================
// Print Handler Module
// ============================================
function initializePrintHandler() {
    const printButton = document.getElementById('printButton');
    const closeButton = document.getElementById('closeButton');
    
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            window.close();
        });
    }
    
    // Auto-print after page loads
    setTimeout(function() {
        window.print();
    }, 500);
}
