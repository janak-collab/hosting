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
