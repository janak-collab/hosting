// GMPM Application JavaScript
const GMPM = {
    Utils: {
        showAlert: function(container, type, message, duration = 5000) {
            if (!container) return;

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            
            // Add icon based on type
            const icons = {
                'success': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>',
                'error': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                'info': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
                'warning': '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'
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

    // IP Manager Module - FIXED VERSION
    if (document.getElementById('ipForm')) {
        initializeIPManager();
    }

    // Print Handler Module
    if (document.getElementById('printButton')) {
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
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const upcomingInput = document.getElementById('upcoming');
    const appointmentInfo = document.getElementById('appointmentInfo');
    const providerButtons = document.querySelectorAll('.provider-button');
    const alertContainer = document.getElementById('alertContainer');

    // Phone number formatting and preview
    if (phoneInput && phonePreview) {
        phoneInput.addEventListener('input', function() {
            const value = this.value.replace(/\D/g, '');
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

    // Character counter
    if (descriptionInput && charCount) {
        descriptionInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length} / 2000`;
            if (length > 1800) {
                charCount.style.color = 'var(--error-color)';
            } else {
                charCount.style.color = 'var(--text-secondary)';
            }
        });
    }

    // Appointment date validation
    if (upcomingInput && appointmentInfo) {
        upcomingInput.addEventListener('change', function() {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (selectedDate < today) {
                appointmentInfo.textContent = '⚠️ This date is in the past';
                appointmentInfo.style.color = 'var(--error-color)';
            } else {
                appointmentInfo.textContent = '';
            }
        });
    }

    // Provider button handling
    providerButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const provider = this.getAttribute('data-provider');
            
            if (validateForm()) {
                submitPhoneNote(provider);
            }
        });
    });

    // Form validation
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

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

    // Submit phone note
    function submitPhoneNote(provider) {
        const formData = new FormData(form);
        formData.append('provider', provider);

        // Show loading state
        providerButtons.forEach(btn => btn.disabled = true);
        showAlert('info', 'Submitting phone note...');

        fetch('/api/phone-notes/submit', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', data.message || 'Phone note submitted successfully!');
                
                // Show print dialog
                if (data.id && confirm('Phone note saved! Would you like to print it now?')) {
                    window.open(`/admin/phone-notes/print/${data.id}`, '_blank');
                }
                
                // Reset form
                setTimeout(() => {
                    form.reset();
                    window.location.href = '/';
                }, 2000);
            } else {
                showAlert('error', data.message || 'Error submitting phone note');
                providerButtons.forEach(btn => btn.disabled = false);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Network error. Please try again.');
            providerButtons.forEach(btn => btn.disabled = false);
        });
    }
}

// ============================================
// IT Support Form Module
// ============================================
function initializeITSupportForm() {
    const form = document.getElementById('supportForm');
    const descriptionInput = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const alertContainer = document.getElementById('alertContainer');

    // Character counter
    if (descriptionInput && charCount) {
        descriptionInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length} / 2000`;
            if (length > 1800) {
                charCount.style.color = 'var(--error-color)';
            } else {
                charCount.style.color = 'var(--text-secondary)';
            }
        });
    }

    // Form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return;
            }

            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';

            const formData = new FormData(form);

            fetch('/api/it-support/submit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message || 'Ticket submitted successfully!');
                    
                    if (data.new_csrf_token) {
                        document.getElementById('csrfToken').value = data.new_csrf_token;
                    }
                    
                    // Reset form
                    setTimeout(() => {
                        form.reset();
                        if (charCount) charCount.textContent = '0 / 2000';
                    }, 1000);
                } else {
                    showAlert('error', data.error || 'Error submitting ticket');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Network error. Please try again.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                btnText.style.display = 'inline';
                btnSpinner.style.display = 'none';
            });
        });
    }

    // Form validation
    function validateForm() {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('error');
                isValid = false;
            } else {
                field.classList.remove('error');
            }
        });

        if (!isValid) {
            showAlert('error', 'Please fill in all required fields.');
        }

        return isValid;
    }

    // Show alert
    function showAlert(type, message) {
        if (alertContainer) {
            GMPM.Utils.showAlert(alertContainer, type, message);
        }
    }
}

// ============================================
// IP Manager Module - FIXED WITH EVENT DELEGATION
// ============================================
function initializeIPManager() {
    const ipForm = document.getElementById('ipForm');
    const ipList = document.getElementById('ipList');
    const addBtn = document.getElementById('addIPBtn');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    
    // Initialize counter
    let ipCount = document.querySelectorAll('.ip-row').length;

    // Add IP button
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            addIP();
        });
    }

    // Event delegation for remove buttons
    if (ipList) {
        ipList.addEventListener('click', function(e) {
            // Check if clicked element is a remove button
            if (e.target.classList.contains('remove-btn')) {
                e.preventDefault();
                removeIP(e.target);
            }
        });

        // Event delegation for IP validation
        ipList.addEventListener('blur', function(e) {
            if (e.target.classList.contains('ip-input')) {
                validateIP(e.target);
            }
        }, true);
    }

    // Form submission
    if (ipForm) {
        ipForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate all IPs
            const ipInputs = document.querySelectorAll('.ip-input');
            let isValid = true;
            
            ipInputs.forEach(input => {
                if (!validateIP(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                GMPM.Utils.showAlert(
                    document.getElementById('alertContainer'), 
                    'error', 
                    'Please fix invalid IP addresses before saving.'
                );
                return;
            }

            // Show loading state
            if (submitBtn && btnText && btnSpinner) {
                submitBtn.disabled = true;
                btnText.style.display = 'none';
                btnSpinner.style.display = 'inline-block';
            }

            // Submit form
            ipForm.submit();
        });
    }

    // Add IP function
    function addIP() {
        ipCount++;
        const newRow = document.createElement('div');
        newRow.className = 'ip-row';
        newRow.innerHTML = `
            <span class="row-number">#${ipCount}</span>
            <input type="text" name="ips[]" class="form-input ip-input"
                placeholder="IP Address (e.g., 192.168.1.1)" required maxlength="15"
                pattern="^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$">
            <input type="text" name="locations[]" class="form-input"
                placeholder="Location/Office Name" required maxlength="100">
            <button type="button" class="remove-btn">Remove</button>
        `;
        ipList.appendChild(newRow);

        // Focus on new IP input
        const newIpInput = newRow.querySelector('input[name="ips[]"]');
        if (newIpInput) {
            newIpInput.focus();
        }
        
        updateRowNumbers();
    }

    // Remove IP function
    function removeIP(button) {
        const ipRows = document.querySelectorAll('.ip-row');
        if (ipRows.length <= 1) {
            alert('You must keep at least one IP address');
            return;
        }

        const row = button.closest('.ip-row');
        const ipInput = row.querySelector('input[name="ips[]"]');
        const locationInput = row.querySelector('input[name="locations[]"]');
        
        const confirmMsg = (ipInput && ipInput.value) ? 
            `Remove IP ${ipInput.value} (${locationInput ? locationInput.value : ''})?` : 
            'Remove this row?';

        if (confirm(confirmMsg)) {
            row.remove();
            updateRowNumbers();
            GMPM.Utils.showAlert(
                document.getElementById('alertContainer'), 
                'info', 
                'IP address removed',
                3000
            );
        }
    }

    // Update row numbers
    function updateRowNumbers() {
        const rows = document.querySelectorAll('.ip-row');
        rows.forEach((row, index) => {
            const number = row.querySelector('.row-number');
            if (number) {
                number.textContent = `#${index + 1}`;
            }
        });
        ipCount = rows.length;
    }

    // Validate IP function
    function validateIP(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('ipError');
        
        if (!value) {
            input.classList.remove('error');
            return true;
        }

        const ipPattern = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        
        if (!ipPattern.test(value)) {
            input.classList.add('error');
            if (errorDiv) {
                errorDiv.textContent = `Invalid IP address: ${value}`;
                errorDiv.style.display = 'block';
            }
            return false;
        } else {
            input.classList.remove('error');
            if (errorDiv) {
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
            }
            return true;
        }
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
}

// ============================================
// Header Module (if needed)
// ============================================
function initializeHeader() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSearchToggle = document.getElementById('mobileSearchToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileSearch = document.getElementById('mobileSearch');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            this.classList.toggle('active');
            
            const menuIcon = this.querySelector('.menu-icon');
            const closeIcon = this.querySelector('.close-icon');
            
            if (mobileMenu.classList.contains('active')) {
                menuIcon.style.display = 'none';
                closeIcon.style.display = 'block';
            } else {
                menuIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        });
    }

    if (mobileSearchToggle && mobileSearch) {
        mobileSearchToggle.addEventListener('click', function() {
            mobileSearch.style.display = mobileSearch.style.display === 'none' ? 'block' : 'none';
            if (mobileSearch.style.display === 'block') {
                mobileSearch.querySelector('input').focus();
            }
        });
    }
}

// Initialize header on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initializeHeader();
});
