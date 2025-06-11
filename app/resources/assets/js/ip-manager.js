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