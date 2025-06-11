// Dictation Form JavaScript
(function() {
    'use strict';
    
    // Session data
    const sessionData = {
        session_start_time: Date.now(),
        dictation_count: 0,
        current_dictation_start: null,
        provider_id: null,
        provider_name: null,
        location: null,
        procedure_date: null,
        selectedProcedure: null
    };
    
    // Timer variables
    let idleTime = 0;
    const MAX_IDLE = 15 * 60; // 15 minutes in seconds
    let timerInterval;
    let idleInterval;
    
    // Cache DOM elements
    const elements = {
        // Batch setup
        provider: document.getElementById('provider'),
        location: document.getElementById('location'),
        procedureDate: document.getElementById('procedure_date'),
        batchSetup: document.getElementById('batchSetup'),
        batchBanner: document.getElementById('batchBanner'),
        startBatchBtn: document.getElementById('startBatchBtn'),
        changeSettingsBtn: document.getElementById('changeSettingsBtn'),
        
        // Patient info
        patientSection: document.getElementById('patientSection'),
        procedureSection: document.getElementById('procedureSection'),
        patientName: document.getElementById('patient_name'),
        dob: document.getElementById('patient_dob'),
        
        // Procedures
        procedureList: document.getElementById('procedureList'),
        categoryTabs: document.getElementById('categoryTabs'),
        billingCodes: document.getElementById('billingCodes'),
        
        // Preview
        previewSection: document.getElementById('previewSection'),
        previewContent: document.getElementById('previewContent'),
        printBtn: document.getElementById('printBtn'),
        
        // Status footer
        statusFooter: document.getElementById('statusFooter'),
        printCounter: document.getElementById('printCounter'),
        footerLocation: document.getElementById('footerLocation'),
        footerProvider: document.getElementById('footerProvider'),
        footerDate: document.getElementById('footerDate'),
        sessionTimer: document.getElementById('sessionTimer'),
        
        // Modal
        timeoutModal: document.getElementById('timeoutModal'),
        timeoutTimer: document.getElementById('timeoutTimer'),
        
        // Alert container
        alertContainer: document.getElementById('alertContainer')
    };
    
    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        setupIdleTimer();
        
        // Log form access
        logAudit('view_form', null, {
            user_agent: navigator.userAgent
        });
    });
    
    // Event Listeners
    function initializeEventListeners() {
        // Batch setup
        elements.startBatchBtn.addEventListener('click', startBatchMode);
        elements.changeSettingsBtn.addEventListener('click', changeBatchSettings);
        
        // Provider change loads procedures
        elements.provider.addEventListener('change', function() {
            if (this.value) {
                loadProcedures(this.value);
                logAudit('select_provider', null, {
                    provider_id: this.value,
                    provider_name: this.options[this.selectedIndex].text
                });
            }
        });
        
        // Location change
        elements.location.addEventListener('change', function() {
            if (this.value) {
                logAudit('select_location', null, {
                    location: this.value
                });
            }
        });
        
        // Date change
        elements.procedureDate.addEventListener('change', function() {
            if (this.value) {
                logAudit('select_date', null, {
                    procedure_date: this.value
                });
            }
        });
        
        // Patient field listeners - Fixed to avoid circular dependency
        elements.patientName.addEventListener('input', function() {
            if (validatePatient()) {
                populateTemplate();
            }
        });
        
        elements.dob.addEventListener('change', function() {
            if (validatePatient()) {
                populateTemplate();
            }
        });
        
        // Print button
        elements.printBtn.addEventListener('click', function() {
            if (!validatePatient()) {
                showAlert('error', 'Please fill in patient name and date of birth');
                return;
            }
            
            if (!sessionData.selectedProcedure) {
                showAlert('error', 'Please select a procedure');
                return;
            }
            
            // Make sure template is populated
            populateTemplate();
            
            // Log print action
            logAudit('print_dictation', sessionData.selectedProcedure.id, {
                procedure_name: sessionData.selectedProcedure.name
            });
            
            window.print();
        });
        
        // Auto-clear after print
        window.addEventListener('afterprint', function() {
            clearPatientFields();
            sessionData.dictation_count++;
            updateFooterStats();
            showAlert('success', '✓ Dictation printed - Ready for next patient');
            elements.patientName.focus();
            
            // Hide preview
            elements.previewSection.classList.remove('active');
        });
        
        // Handle page unload
        window.addEventListener('beforeunload', handleSessionEnd);
    }
    
    // Validate patient info - Fixed to not call populateTemplate
    function validatePatient() {
        const name = elements.patientName.value.trim();
        const dob = elements.dob.value;
        
        if (name && dob && sessionData.selectedProcedure) {
            return true;
        }
        return false;
    }
    
    // Start batch mode
    function startBatchMode() {
        const provider = elements.provider.value;
        const location = elements.location.value;
        const date = elements.procedureDate.value;
        
        if (!provider || !location || !date) {
            showAlert('error', 'Please fill in all batch settings before starting');
            return;
        }
        
        // Save batch settings
        sessionData.provider_id = provider;
        sessionData.provider_name = elements.provider.options[elements.provider.selectedIndex].text;
        sessionData.location = location;
        sessionData.procedure_date = date;
        sessionData.session_start_time = Date.now();
        
        // Update UI
        document.getElementById('selectedProvider').textContent = sessionData.provider_name;
        document.getElementById('selectedLocation').textContent = location;
        document.getElementById('selectedDate').textContent = formatDate(date);
        
        // Show/hide sections
        elements.batchSetup.style.display = 'none';
        elements.batchBanner.classList.add('active');
        elements.patientSection.classList.add('active');
        elements.procedureSection.classList.add('active');
        elements.statusFooter.classList.add('active');
        
        // Update footer
        updateFooterStats();
        
        // Focus on patient name
        elements.patientName.focus();
        
        // Log session start
        logAudit('session_start', null, {
            provider_id: provider,
            location: location,
            procedure_date: date
        });
    }
    
    // Change batch settings
    function changeBatchSettings() {
        if (confirm('Changing settings will clear all patient data. Continue?')) {
            clearPatientFields();
            elements.batchSetup.style.display = 'block';
            elements.batchBanner.classList.remove('active');
            elements.patientSection.classList.remove('active');
            elements.procedureSection.classList.remove('active');
            
            logAudit('change_settings', null, {
                previous_count: sessionData.dictation_count
            });
        }
    }
    
    // Load procedures for provider
    async function loadProcedures(providerId) {
        try {
            const response = await fetch(`/api/dictation/get-templates?provider_id=${providerId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load procedures');
            
            const data = await response.json();
            if (data.success && data.procedures) {
                displayProcedures(data.procedures);
                setupCategoryFilters(data.procedures);
                
                logAudit('load_procedures', null, {
                    provider_id: providerId,
                    procedure_count: data.procedures.length
                });
            }
        } catch (error) {
            console.error('Error loading procedures:', error);
            showAlert('error', 'Failed to load procedures. Please try again.');
        }
    }
    
    // Display procedures
    function displayProcedures(procedures) {
        elements.procedureList.innerHTML = '';
        
        procedures.forEach(procedure => {
            const card = document.createElement('div');
            card.className = 'procedure-card';
            if (procedure.is_favorite == 1) {
                card.classList.add('favorite');
            }
            
            card.innerHTML = `
                <div class="procedure-name">${procedure.name}</div>
                <div class="procedure-category">${procedure.category}</div>
            `;
            
            // Select procedure
            card.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.procedure-card').forEach(c => c.classList.remove('selected'));
                
                // Add selection
                this.classList.add('selected');
                sessionData.selectedProcedure = procedure;
                
                // Show billing codes
                updateBillingCodes(procedure.billing_codes);
                
                // If patient info is complete, populate template
                if (validatePatient()) {
                    populateTemplate();
                }
                
                // Log procedure selection
                logAudit('select_procedure', procedure.id, {
                    procedure_name: procedure.name,
                    category: procedure.category
                });
            });
            
            elements.procedureList.appendChild(card);
        });
    }
    
    // Setup category filters
    function setupCategoryFilters(procedures) {
        const categories = new Set(['all', 'favorites']);
        procedures.forEach(p => categories.add(p.category));
        
        elements.categoryTabs.innerHTML = '';
        
        categories.forEach(category => {
            const tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'category-tab';
            tab.dataset.category = category;
            tab.textContent = category === 'all' ? 'All' : 
                           category === 'favorites' ? '⭐ Favorites' : category;
            
            if (category === 'all') tab.classList.add('active');
            
            tab.addEventListener('click', function() {
                filterByCategory(category, procedures);
                
                // Update active tab
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                logAudit('filter_category', null, { category: category });
            });
            
            elements.categoryTabs.appendChild(tab);
        });
    }
    
    // Filter procedures by category
    function filterByCategory(category, procedures) {
        const cards = elements.procedureList.querySelectorAll('.procedure-card');
        
        procedures.forEach((procedure, index) => {
            const card = cards[index];
            if (!card) return;
            
            if (category === 'all') {
                card.style.display = 'block';
            } else if (category === 'favorites') {
                card.style.display = procedure.is_favorite == 1 ? 'block' : 'none';
            } else {
                card.style.display = procedure.category === category ? 'block' : 'none';
            }
        });
    }
    
    // Populate template with patient data - Fixed to not call validatePatient
    function populateTemplate() {
        const procedure = sessionData.selectedProcedure;
        if (!procedure) return;
        
        const template = procedure.final_template || procedure.template_content;
        const patientName = elements.patientName.value.trim();
        const dob = elements.dob.value;
        const provider = elements.provider.options[elements.provider.selectedIndex].text;
        const location = elements.location.value;
        const procedureDate = elements.procedureDate.value;
        
        let populated = template
            .replace(/\[PATIENT_NAME\]/g, patientName)
            .replace(/\[DOB\]/g, formatDate(dob))
            .replace(/\[PROVIDER\]/g, provider)
            .replace(/\[LOCATION\]/g, location)
            .replace(/\[PROCEDURE_DATE\]/g, formatDate(procedureDate))
            .replace(/\[DATE\]/g, formatDate(new Date()))
            .replace(/\[TIME\]/g, new Date().toLocaleTimeString());
        
        // Update preview
        elements.previewContent.textContent = populated;
        elements.previewSection.classList.add('active');
    }
    
    // Update billing codes display
    function updateBillingCodes(codes) {
        if (!elements.billingCodes) return;
        
        if (codes && codes.length > 0) {
            const codesList = codes.map(code => 
                `${code.cpt_code} - ${code.description}`
            ).join(', ');
            elements.billingCodes.textContent = `Billing: ${codesList}`;
            elements.billingCodes.style.display = 'block';
        } else {
            elements.billingCodes.style.display = 'none';
        }
    }
    
    // Clear patient fields (keep batch settings)
    function clearPatientFields() {
        elements.patientName.value = '';
        elements.dob.value = '';
        
        // Clear procedure selection
        document.querySelectorAll('.procedure-card').forEach(c => c.classList.remove('selected'));
        sessionData.selectedProcedure = null;
        
        // Hide preview
        elements.previewSection.classList.remove('active');
        
        // Hide billing codes
        if (elements.billingCodes) elements.billingCodes.style.display = 'none';
    }
    
    // Update footer statistics
    function updateFooterStats() {
        elements.printCounter.textContent = sessionData.dictation_count;
        elements.footerLocation.textContent = sessionData.location || 'Not set';
        elements.footerProvider.textContent = sessionData.provider_name || 'Not set';
        elements.footerDate.textContent = sessionData.procedure_date ? 
            formatDate(sessionData.procedure_date) : 'Not set';
    }
    
    // Format date
    function formatDate(dateStr) {
        if (!dateStr) return '';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
        });
    }
    
    // Show alert message
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const icon = type === 'error' ? '❌' : type === 'success' ? '✓' : 'ℹ️';
        alertDiv.innerHTML = `${icon} ${message}`;
        
        elements.alertContainer.innerHTML = '';
        elements.alertContainer.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
    
    // Idle timer setup
    function setupIdleTimer() {
        // Reset timer on activity
        ['mousemove', 'keypress', 'click', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetIdleTimer);
        });
        
        // Start timer
        idleInterval = setInterval(incrementIdleTime, 1000);
        timerInterval = setInterval(updateTimer, 1000);
    }
    
    // Reset idle timer
    window.resetIdleTimer = function() {
        idleTime = 0;
        elements.timeoutModal.classList.remove('active');
    };
    
    // Increment idle time
    function incrementIdleTime() {
        idleTime++;
        
        // Show warning at 1 minute left
        if (idleTime === MAX_IDLE - 60) {
            elements.timeoutModal.classList.add('active');
        }
        
        // Timeout
        if (idleTime >= MAX_IDLE) {
            handleTimeout();
        }
    }
    
    // Update timer display
    function updateTimer() {
        const remaining = MAX_IDLE - idleTime;
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        elements.sessionTimer.textContent = display;
        
        // Update timer styling
        if (remaining <= 60) {
            elements.sessionTimer.classList.add('critical');
            elements.timeoutTimer.textContent = remaining;
        } else if (remaining <= 120) {
            elements.sessionTimer.classList.add('warning');
        }
    }
    
    // Handle timeout
    function handleTimeout() {
        clearInterval(idleInterval);
        clearInterval(timerInterval);
        
        logAudit('session_timeout', null, {
            dictation_count: sessionData.dictation_count,
            session_duration: Date.now() - sessionData.session_start_time
        });
        
        alert('Your session has expired due to inactivity. You will be redirected to the portal.');
        window.location.href = '/';
    }
    
    // Handle session end
    function handleSessionEnd() {
        const sessionEndData = {
            action: 'session_end',
            dictation_count: sessionData.dictation_count,
            session_duration: Date.now() - sessionData.session_start_time,
            provider_id: sessionData.provider_id,
            location: sessionData.location
        };
        
        // Use sendBeacon for reliable delivery
        const blob = new Blob([JSON.stringify(sessionEndData)], { type: 'application/json' });
        navigator.sendBeacon('/api/dictation/log-audit', blob);
    }
    
    // Log audit
    async function logAudit(action, procedureId, metadata = {}) {
        try {
            const data = {
                action: action,
                procedure_id: procedureId,
                metadata: metadata
            };
            
            await fetch('/api/dictation/log-audit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });
        } catch (error) {
            console.error('Failed to log audit:', error);
        }
    }
})();
