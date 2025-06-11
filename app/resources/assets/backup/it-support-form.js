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