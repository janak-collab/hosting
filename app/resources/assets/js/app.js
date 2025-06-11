// ============================================
// GMPM Application JavaScript - Fully Consolidated
// Generated: $(date +"%Y-%m-%d %H:%M:%S")
// ============================================

// Namespace for GMPM application
window.GMPM = window.GMPM || {};

// ============================================
// Core Utilities
// ============================================
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
            'error': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 
1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            'success': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 
001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            'info': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 
00-1-1H9z" clip-rule="evenodd"/></svg>'
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

// Initialize modules based on page
document.addEventListener('DOMContentLoaded', function() {
    console.log('GMPM Application Initialized');


// ============================================
// phone-note-form Module
// ============================================

// phone-note-form.js - Phone Note Form Handler
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
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

    // Phone number formatting
    phoneInput.addEventListener('input', function() {
        // Remove non-digits
        let value = this.value.replace(/\D/g, '');
        
        // Limit to 10 digits
        if (value.length > 10) {
            value = value.substring(0, 10);
        }
        
        this.value = value;
        
        // Format preview
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
    });

    // HIPAA warning for third-party callers
    callerNameInput.addEventListener('input', function() {
        if (this.value.trim()) {
            hipaaWarning.style.display = 'flex';
            showAlert('info', 'HIPAA Notice: When a third party is calling, ensure they are authorized to receive patient information.');
        } else {
            hipaaWarning.style.display = 'none';
        }
    });

    // Character counter for description
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

    // Appointment date calculations
    function updateAppointmentInfo() {
        const lastSeen = new Date(lastSeenInput.value);
        const upcoming = new Date(upcomingInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (lastSeenInput.value && upcomingInput.value) {
            const daysBetween = Math.ceil((upcoming - lastSeen) / (1000 * 60 * 60 * 24));
            const daysFromToday = Math.ceil((upcoming - today) / (1000 * 60 * 60 * 24));
            
            let info = `${daysBetween} days from last appointment`;
            
            if (daysFromToday === 0) {
                info += ', appointment is today';
            } else if (daysFromToday === 1) {
                info += ', appointment is tomorrow';
            } else if (daysFromToday > 1) {
                info += `, appointment is in ${daysFromToday} days`;
            } else {
                info += `, appointment was ${Math.abs(daysFromToday)} days ago`;
            }
            
            appointmentInfo.textContent = info;
            appointmentInfo.style.color = daysFromToday >= 0 ? 'var(--success-color)' : 'var(--error-color)';
        } else {
            appointmentInfo.textContent = '';
        }
    }
    
    lastSeenInput.addEventListener('change', updateAppointmentInfo);
    upcomingInput.addEventListener('change', updateAppointmentInfo);

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
        const phone = phoneInput.value;
        if (phone.length !== 10) {
            phoneInput.classList.add('error');
            isValid = false;
            showAlert('error', 'Please enter a valid 10-digit phone number.');
        }
        
        return isValid;
    }

    // Show alert message
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const icon = type === 'error' ? '❌' : type === 'success' ? '✓' : 'ℹ️';
        alertDiv.innerHTML = `${icon} ${message}`;
        
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Form submission handling
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            showAlert('error', 'Please fill in all required fields correctly.');
            
            // Scroll to first error
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });

    // Auto-save functionality
    let autoSaveTimer;
    
    function autoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            const formData = {
                pname: document.getElementById('pname').value,
                dob: document.getElementById('dob').value,
                phone: phoneInput.value,
                caller_name: callerNameInput.value,
                last_seen: lastSeenInput.value,
                upcoming: upcomingInput.value,
                location: document.querySelector('input[name="location"]:checked')?.value || '',
                description: textarea.value
            };
            
            try {
                localStorage.setItem('phoneNoteDraft', JSON.stringify(formData));
                console.log('Form auto-saved');
            } catch (e) {
                console.error('Failed to auto-save:', e);
            }
        }, 1000);
    }

    // Load saved data
    function loadSaved() {
        try {
            const saved = localStorage.getItem('phoneNoteDraft');
            if (saved) {
                const data = JSON.parse(saved);
                
                if (data.pname) document.getElementById('pname').value = data.pname;
                if (data.dob) document.getElementById('dob').value = data.dob;
                if (data.phone) {
                    phoneInput.value = data.phone;
                    phoneInput.dispatchEvent(new Event('input'));
                }
                if (data.caller_name) {
                    callerNameInput.value = data.caller_name;
                    callerNameInput.dispatchEvent(new Event('input'));
                }
                if (data.last_seen) lastSeenInput.value = data.last_seen;
                if (data.upcoming) upcomingInput.value = data.upcoming;
                if (data.location) {
                    const radio = document.querySelector(`input[name="location"][value="${data.location}"]`);
                    if (radio) radio.checked = true;
                }
                if (data.description) {
                    textarea.value = data.description;
                    textarea.dispatchEvent(new Event('input'));
                }
                
                updateAppointmentInfo();
                showAlert('info', 'Draft loaded from previous session');
            }
        } catch (e) {
            console.error('Failed to load saved data:', e);
        }
    }

    // Auto-save on input
    form.addEventListener('input', autoSave);
    form.addEventListener('change', autoSave);
    
    // Load saved data on page load
    loadSaved();
    
    // Clear saved data on successful submission
    form.addEventListener('submit', function() {
        localStorage.removeItem('phoneNoteDraft');
    });

    // Handle provider button clicks
    const providerButtons = document.querySelectorAll('.provider-button');
    providerButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (!validateForm()) {
                showAlert('error', 'Please fill in all required fields before selecting a recipient.');
            } else {
                const provider = this.getAttribute('data-provider');
                submitFormToAPI(provider);
            }
        });
    });

    // Add field validation on blur
    const inputs = form.querySelectorAll('.form-input, .form-textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
    });
});

// API submission function for provider buttons
async function submitFormToAPI(provider) {
    const form = document.getElementById('phoneNoteForm');
    const formData = new FormData(form);
    formData.append('provider', provider);
    
    const alertContainer = document.getElementById('alertContainer');
    
    try {
        const response = await fetch('/api/phone-notes/submit.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Clear saved draft
            localStorage.removeItem('phoneNoteDraft');
            
            // Show success message
            alertContainer.innerHTML = '<div class="alert alert-success">✓ ' + result.message + '</div>';
            
            // Ask to print
            if (confirm('Phone note saved successfully! Would you like to print it?')) {
                window.open('/admin/phone-notes/print/' + result.id, '_blank');
            }
            
            // Redirect to list
            setTimeout(() => {
                window.location.href = '/admin/phone-notes';
            }, 1500);
            
        } else {
            let errorMsg = result.message || 'Failed to save';
            if (result.errors) {
                errorMsg += '\n' + Object.values(result.errors).join('\n');
            }
            alertContainer.innerHTML = '<div class="alert alert-error">❌ ' + errorMsg + '</div>';
        }
    } catch (error) {
        alertContainer.innerHTML = '<div class="alert alert-error">❌ Error: ' + error.message + '</div>';
    }
}


// ============================================
// it-support-form Module
// ============================================

// it-support-form.js - IT Support Form Handler
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
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

    // Form validation
    function validateForm() {
        let isValid = true;
        const errors = [];

        // Name validation
        if (!nameInput.value.trim()) {
            nameInput.classList.add('error');
            document.getElementById('nameError').textContent = 'Name is required';
            document.getElementById('nameError').style.display = 'block';
            errors.push('Please enter your name');
            isValid = false;
        } else {
            nameInput.classList.remove('error');
            document.getElementById('nameError').style.display = 'none';
        }

        // Location validation
        if (!locationSelect.value) {
            locationSelect.classList.add('error');
            document.getElementById('locationError').textContent = 'Please select a location';
            document.getElementById('locationError').style.display = 'block';
            errors.push('Please select your office location');
            isValid = false;
        } else {
            locationSelect.classList.remove('error');
            document.getElementById('locationError').style.display = 'none';
        }

        // Category validation
        const categorySelected = Array.from(categoryInputs).some(input => input.checked);
        if (!categorySelected) {
            document.getElementById('categoryError').textContent = 'Please select an issue category';
            document.getElementById('categoryError').style.display = 'block';
            errors.push('Please select an issue category');
            isValid = false;
        } else {
            document.getElementById('categoryError').style.display = 'none';
        }

        // Description validation
        if (!descriptionTextarea.value.trim()) {
            descriptionTextarea.classList.add('error');
            document.getElementById('descriptionError').textContent = 'Description is required';
            document.getElementById('descriptionError').style.display = 'block';
            errors.push('Please describe your issue');
            isValid = false;
        } else if (descriptionTextarea.value.trim().length < 10) {
            descriptionTextarea.classList.add('error');
            document.getElementById('descriptionError').textContent = 'Please provide more details (at least 10 characters)';
            document.getElementById('descriptionError').style.display = 'block';
            errors.push('Please provide more details about your issue');
            isValid = false;
        } else {
            descriptionTextarea.classList.remove('error');
            document.getElementById('descriptionError').style.display = 'none';
        }

        return { isValid, errors };
    }

    // Character counter
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

    // Show alert message
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const iconSvg = type === 'error' 
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
            : type === 'success'
            ? '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>'
            : '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';
        
        alertDiv.innerHTML = `${iconSvg} ${message}`;
        
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    // Priority level guidance
    priorityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value === 'critical') {
                showAlert('info', 'For critical issues, please also call IT at 410-555-1234 after submitting your ticket.');
            }
        });
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const validation = validateForm();
        if (!validation.isValid) {
            showAlert('error', 'Please correct the following errors: ' + validation.errors.join(', '));
            
            // Scroll to first error
            const firstError = form.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';

        // Simulate form submission (replace with actual submission)
        try {
            // Create form data
            const formData = new FormData(form);
            
            // In a real implementation, you would submit to the server here
            // const response = await fetch(form.action, {
            //     method: 'POST',
            //     body: formData
            // });
            
            // Simulate server delay
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            // Success handling
            showAlert('success', 'Your IT support ticket has been submitted successfully!');
            
            // Clear form
            form.reset();
            charCount.textContent = '0 / 2000';
            
            // Clear saved draft
            localStorage.removeItem('itSupportDraft');
            
            // Redirect after a delay
            setTimeout(() => {
                window.location.href = '/admin/tickets';
            }, 2000);
            
        } catch (error) {
            showAlert('error', 'An error occurred while submitting your ticket. Please try again.');
            console.error('Submission error:', error);
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
        }
    });

    // Auto-save functionality
    let autoSaveTimer;
    
    function autoSave() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            const formData = {
                name: nameInput.value,
                location: locationSelect.value,
                category: document.querySelector('input[name="category"]:checked')?.value || '',
                priority: document.querySelector('input[name="priority"]:checked')?.value || 'normal',
                description: descriptionTextarea.value
            };
            
            try {
                localStorage.setItem('itSupportDraft', JSON.stringify(formData));
                console.log('Form auto-saved');
            } catch (e) {
                console.error('Failed to auto-save:', e);
            }
        }, 1000);
    }

    // Load saved draft
    function loadSavedDraft() {
        try {
            const saved = localStorage.getItem('itSupportDraft');
            if (saved) {
                const data = JSON.parse(saved);
                
                if (data.name) nameInput.value = data.name;
                if (data.location) locationSelect.value = data.location;
                if (data.category) {
                    const categoryRadio = document.querySelector(`input[name="category"][value="${data.category}"]`);
                    if (categoryRadio) categoryRadio.checked = true;
                }
                if (data.priority) {
                    const priorityRadio = document.querySelector(`input[name="priority"][value="${data.priority}"]`);
                    if (priorityRadio) priorityRadio.checked = true;
                }
                if (data.description) {
                    descriptionTextarea.value = data.description;
                    descriptionTextarea.dispatchEvent(new Event('input'));
                }
                
                showAlert('info', 'Draft loaded from previous session');
            }
        } catch (e) {
            console.error('Failed to load saved draft:', e);
        }
    }

    // Enable auto-save
    form.addEventListener('input', autoSave);
    form.addEventListener('change', autoSave);

    // Load saved draft on page load
    loadSavedDraft();

    // Add real-time validation
    nameInput.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('error');
            document.getElementById('nameError').textContent = 'Name is required';
            document.getElementById('nameError').style.display = 'block';
        } else {
            this.classList.remove('error');
            document.getElementById('nameError').style.display = 'none';
        }
    });

    locationSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('error');
            document.getElementById('locationError').style.display = 'none';
        }
    });

    descriptionTextarea.addEventListener('blur', function() {
        if (!this.value.trim()) {
            this.classList.add('error');
            document.getElementById('descriptionError').textContent = 'Description is required';
            document.getElementById('descriptionError').style.display = 'block';
        } else if (this.value.trim().length < 10) {
            this.classList.add('error');
            document.getElementById('descriptionError').textContent = 'Please provide more details (at least 10 characters)';
            document.getElementById('descriptionError').style.display = 'block';
        } else {
            this.classList.remove('error');
            document.getElementById('descriptionError').style.display = 'none';
        }
    });

    // Category helper text
    categoryInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('categoryError').style.display = 'none';
                
                // Show helpful context based on category
                const categoryHelp = {
                    'hardware': 'Include device model/serial number if known',
                    'software': 'Include software name and version',
                    'network': 'Include affected services and error messages',
                    'phone': 'Include extension number and phone model',
                    'printer': 'Include printer name/location',
                    'email': 'Include email client and error messages',
                    'other': 'Please be as specific as possible'
                };
                
                const helpText = categoryHelp[this.value];
                if (helpText) {
                    descriptionTextarea.placeholder = `Please describe your issue in detail. ${helpText}`;
                }
            }
        });
    });
});

// ============================================
// ip-manager Module
// ============================================

// IP Manager JavaScript - CSP Compliant Version
document.addEventListener('DOMContentLoaded', function() {
    // Initialize IP count
    window.ipCount = document.querySelectorAll('.ip-row').length || 0;
    
    // IP validation function
    window.validateIPAddress = function(ip) {
        const ipRegex = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
        return ipRegex.test(ip);
    };
    
    // Add IP function
    window.addIP = function() {
        console.log('addIP function called');
        window.ipCount++;
        const ipList = document.getElementById('ipList');
        if (!ipList) {
            console.error('ipList element not found');
            return;
        }
        
        const newRow = document.createElement('div');
        newRow.className = 'ip-row';
        newRow.innerHTML = `
            <span class="row-number">#${window.ipCount}</span>
            <input 
                type="text" 
                name="ips[]" 
                class="form-input ip-input"
                placeholder="IP Address (e.g., 192.168.1.1)"
                pattern="^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$"
                title="Enter a valid IP address (e.g., 192.168.1.1)"
                required
                maxlength="15"
            >
            <input 
                type="text" 
                name="locations[]" 
                class="form-input"
                placeholder="Location/Office Name"
                required
                maxlength="100"
            >
            <button type="button" class="remove-btn">Remove</button>
        `;
        ipList.appendChild(newRow);
        
        // Attach event listeners to new elements
        attachRowEventListeners(newRow);
        
        // Focus on the new IP input
        const newIpInput = newRow.querySelector('input[name="ips[]"]');
        if (newIpInput) {
            newIpInput.focus();
        }
    };
    
    // Validate IP function
    window.validateIP = function(input) {
        const value = input.value.trim();
        const errorDiv = document.getElementById('ipError');
        
        if (value && !validateIPAddress(value)) {
            input.classList.add('error');
            errorDiv.textContent = `Invalid IP address: ${value}. Please enter a valid IP (e.g., 192.168.1.1)`;
            errorDiv.style.display = 'block';
        } else {
            input.classList.remove('error');
            // Clear error if all IPs are valid
            const allInputs = document.querySelectorAll('input[name="ips[]"]');
            const hasErrors = Array.from(allInputs).some(inp => inp.classList.contains('error'));
            if (!hasErrors) {
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
            }
        }
    };
    
    // Remove IP function
    window.removeIP = function(button) {
        const ipRows = document.querySelectorAll('.ip-row');
        if (ipRows.length <= 1) {
            showError('You must keep at least one IP address');
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
    
    // Show error function
    window.showError = function(message) {
        const alertContainer = document.getElementById('alertContainer');
        alertContainer.innerHTML = `
            <div class="alert alert-error">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                ${message}
            </div>
        `;
    };
    
    // Attach event listeners to a row
    function attachRowEventListeners(row) {
        // IP input blur event
        const ipInput = row.querySelector('.ip-input');
        if (ipInput) {
            ipInput.addEventListener('blur', function() {
                validateIP(this);
            });
        }
        
        // Remove button click event
        const removeBtn = row.querySelector('.remove-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                removeIP(this);
            });
        }
    }
    
    // Attach event listeners to existing rows
    const existingRows = document.querySelectorAll('.ip-row');
    existingRows.forEach(row => {
        attachRowEventListeners(row);
    });
    
    // Add IP button click event
    const addBtn = document.querySelector('#addIPBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            addIP();
        });
    }
    
    // Form validation
    const ipForm = document.getElementById('ipForm');
    if (ipForm) {
        ipForm.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const ipInputs = document.querySelectorAll('input[name="ips[]"]');
            const ipError = document.getElementById('ipError');
            
            let hasValidIP = false;
            let hasInvalidIP = false;
            let duplicateFound = false;
            const ipSet = new Set();
            
            // Clear previous errors
            ipError.textContent = '';
            ipError.style.display = 'none';
            
            ipInputs.forEach(input => {
                const ipValue = input.value.trim();
                if (ipValue !== '') {
                    hasValidIP = true;
                    
                    // Validate IP format
                    if (!validateIPAddress(ipValue)) {
                        hasInvalidIP = true;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                    
                    // Check for duplicates
                    if (ipSet.has(ipValue)) {
                        duplicateFound = true;
                        input.classList.add('error');
                    } else {
                        ipSet.add(ipValue);
                    }
                }
            });
            
            if (!hasValidIP) {
                e.preventDefault();
                ipError.textContent = 'Please add at least one IP address';
                ipError.style.display = 'block';
                return;
            }
            
            if (hasInvalidIP) {
                e.preventDefault();
                ipError.textContent = 'Please correct invalid IP addresses before saving';
                ipError.style.display = 'block';
                return;
            }
            
            if (duplicateFound) {
                e.preventDefault();
                ipError.textContent = 'Duplicate IP addresses found. Please remove duplicates.';
                ipError.style.display = 'block';
                return;
            }
            
            // Confirm before saving
            if (!confirm('Are you sure you want to update the IP addresses? This will affect access immediately.')) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';
        });
    }
    
    // Test if button is accessible
    const addBtnTest = document.querySelector('.btn-secondary');
    console.log('Add IP button found:', addBtnTest);
});

// ============================================
// phone-note-print Module
// ============================================

// phone-note-print.js
document.addEventListener('DOMContentLoaded', function() {
    // Get print button
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
});



// ============================================
// Application Initialization
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('GMPM Application Initialized');
    
    // Initialize modules based on page elements
    if (document.getElementById('phoneNoteForm')) {
        if (typeof GMPM.PhoneNote !== 'undefined' && GMPM.PhoneNote.init) {
            GMPM.PhoneNote.init();
        }
    }
    
    if (document.getElementById('supportForm')) {
        if (typeof GMPM.ITSupport !== 'undefined' && GMPM.ITSupport.init) {
            GMPM.ITSupport.init();
        }
    }
    
    if (document.getElementById('ipForm')) {
        if (typeof GMPM.IPManager !== 'undefined' && GMPM.IPManager.init) {
            GMPM.IPManager.init();
        }
    }
    
    // Initialize print handler if on print page
    if (document.querySelector('.print-container')) {
        if (typeof GMPM.PrintHandler !== 'undefined' && GMPM.PrintHandler.init) {
            GMPM.PrintHandler.init();
        }
    }
});
