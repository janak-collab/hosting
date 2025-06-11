<?php
// Set no-cache headers
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// $procedures is passed from the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dictation Generator - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        /* Existing styles */
        .procedure-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid var(--border-color);
        }
        
        .procedure-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .procedure-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .procedure-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        #dictationForm {
            display: none;
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        
        #dictationOutput {
            display: none;
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        
        .generated-dictation {
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            padding: 1.5rem;
            margin: 1rem 0;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        /* Privacy Features Styles */
        .privacy-settings {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .privacy-settings h3 {
            margin-top: 0;
            color: #1976d2;
            font-size: 1rem;
        }
        
        .privacy-option {
            margin: 0.5rem 0;
        }
        
        .privacy-option input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        
        .session-timer {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            display: none;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .session-timer.warning {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        
        /* Secure Print Styles */
        @media print {
            body {
                position: relative;
            }
            
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 120px;
                color: rgba(0, 0, 0, 0.1);
                z-index: -1;
                white-space: nowrap;
                user-select: none;
            }
            
            .confidential-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                text-align: center;
                background: red;
                color: white;
                font-weight: bold;
                padding: 5px;
                font-size: 14px;
            }
            
            .confidential-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                background: red;
                color: white;
                font-weight: bold;
                padding: 5px;
                font-size: 12px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .generated-dictation {
                max-height: none !important;
                page-break-inside: avoid;
            }
        }
        
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .session-timer {
                right: 0.5rem;
                top: 0.5rem;
                font-size: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Session Timer -->
        <div class="session-timer no-print" id="sessionTimer">
            <span id="timerText">Session timeout in: 15:00</span>
        </div>
        
        <div class="form-card">
            <div class="form-header">
                <h1>Dictation Generator</h1>
                <p>Select a procedure and fill in patient information</p>
            </div>
            
            <div class="form-content">
                <!-- Privacy Settings -->
                <div class="privacy-settings no-print">
                    <h3>🔒 Privacy Settings</h3>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="autoClearOnPrint" checked>
                            Auto-clear form after printing
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="autoClearOnCopy" checked>
                            Auto-clear form after copying to clipboard
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="sessionTimeout" checked>
                            Enable 15-minute inactivity timeout
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="addWatermark" checked>
                            Add CONFIDENTIAL watermark when printing
                        </label>
                    </div>
                </div>
                
                <!-- Procedure Selection -->
                <div id="procedureSelection">
                    <h2>Select a Procedure</h2>
                    <?php if (empty($procedures)): ?>
                        <p>No procedures found. Please add procedures to the database.</p>
                    <?php else: ?>
                        <?php foreach ($procedures as $procedure): ?>
                            <?php 
                                $procId = $procedure['id'];
                                $procName = $procedure['name'];
                                $procDesc = substr($procedure['template'], 0, 100);
                            ?>
                            <div class="procedure-card" data-id="<?php echo htmlspecialchars($procId); ?>" data-name="<?php echo htmlspecialchars($procName); ?>">
                                <div class="procedure-title"><?php echo htmlspecialchars($procName); ?></div>
                                <div class="procedure-description"><?php echo htmlspecialchars($procDesc); ?>...</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Patient Form -->
                <div id="dictationForm">
                    <h2>Patient Information for: <span id="selectedProcedure"></span></h2>
                    <form id="patientForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Patient Name <span class="required">*</span></label>
                                <input type="text" name="patient_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date of Birth <span class="required">*</span></label>
                                <input type="date" name="dob" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">MRN <span class="required">*</span></label>
                                <input type="text" name="mrn" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Procedure Date <span class="required">*</span></label>
                                <input type="date" name="procedure_date" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="additional_notes" class="form-textarea"></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" onclick="generateDictation()">Generate Dictation</button>
                            <button type="button" class="btn btn-secondary" onclick="cancelForm()">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <!-- Output -->
                <div id="dictationOutput">
                    <h2>Generated Dictation</h2>
                    <div class="generated-dictation" id="generatedText"></div>
                    <div class="form-actions no-print">
                        <button class="btn btn-primary" onclick="printDictation()">🖨️ Print</button>
                        <button class="btn btn-success" onclick="copyToClipboard()">📋 Copy to Clipboard</button>
                        <button class="btn btn-secondary" onclick="resetForm()">🔄 New Dictation</button>
                    </div>
                    
                    <!-- Print-only elements -->
                    <div class="watermark" style="display: none;">CONFIDENTIAL</div>
                    <div class="confidential-header" style="display: none;">CONFIDENTIAL PATIENT INFORMATION</div>
                    <div class="confidential-footer" style="display: none;">
                        This document contains confidential patient information. Handle according to HIPAA guidelines.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>

    <script>
        // Store procedures data
        const procedures = <?php echo json_encode($procedures, JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        let selectedProcedureId = null;
        let selectedTemplate = '';
        
        // Privacy settings variables
        let sessionTimeoutId = null;
        let activityTimeoutId = null;
        let timeRemaining = 900; // 15 minutes in seconds
        let timerInterval = null;
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // Add click handlers to procedure cards
            document.querySelectorAll('.procedure-card').forEach(card => {
                card.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    selectProcedure(id, name);
                });
            });
            
            // Start session timeout if enabled
            if (document.getElementById('sessionTimeout').checked) {
                startSessionTimeout();
            }
            
            // Monitor activity
            ['mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, resetActivityTimeout, true);
            });
            
            // Monitor privacy settings changes
            document.getElementById('sessionTimeout').addEventListener('change', function() {
                if (this.checked) {
                    startSessionTimeout();
                } else {
                    stopSessionTimeout();
                }
            });
            
            // Clear on tab close
            window.addEventListener('beforeunload', function(e) {
                if (document.querySelector('#dictationForm').style.display !== 'none' ||
                    document.querySelector('#dictationOutput').style.display !== 'none') {
                    clearFormData();
                }
            });
            
            // Handle visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.hidden && document.getElementById('sessionTimeout').checked) {
                    clearInterval(timerInterval);
                } else if (!document.hidden && document.getElementById('sessionTimeout').checked) {
                    startTimer();
                }
            });
        });
        
        // Session timeout functions
        function startSessionTimeout() {
            resetActivityTimeout();
            startTimer();
            document.getElementById('sessionTimer').style.display = 'block';
        }
        
        function stopSessionTimeout() {
            clearTimeout(activityTimeoutId);
            clearInterval(timerInterval);
            document.getElementById('sessionTimer').style.display = 'none';
            timeRemaining = 900;
        }
        
        function resetActivityTimeout() {
            clearTimeout(activityTimeoutId);
            timeRemaining = 900;
            updateTimerDisplay();
            
            if (document.getElementById('sessionTimeout').checked) {
                activityTimeoutId = setTimeout(function() {
                    showTimeoutWarning();
                }, 840000); // 14 minutes
            }
        }
        
        function startTimer() {
            clearInterval(timerInterval);
            timerInterval = setInterval(function() {
                timeRemaining--;
                updateTimerDisplay();
                
                if (timeRemaining === 60) {
                    document.getElementById('sessionTimer').classList.add('warning');
                }
                
                if (timeRemaining <= 0) {
                    clearAndReset();
                    alert('Session timed out due to inactivity. All patient data has been cleared for security.');
                    stopSessionTimeout();
                }
            }, 1000);
        }
        
        function updateTimerDisplay() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            document.getElementById('timerText').textContent = 
                `Session timeout in: ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        function showTimeoutWarning() {
            if (confirm('Your session will timeout in 1 minute due to inactivity. Click OK to continue working.')) {
                resetActivityTimeout();
            }
        }
        
        // Clear all form data
        function clearFormData() {
            document.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach(input => {
                input.value = '';
            });
            document.getElementById('generatedText').textContent = '';
        }
        
        // Clear and reset
        function clearAndReset() {
            clearFormData();
            document.getElementById('procedureSelection').style.display = 'block';
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('dictationOutput').style.display = 'none';
            selectedProcedureId = null;
            selectedTemplate = '';
            resetActivityTimeout();
        }
        
        // Select procedure
        function selectProcedure(id, name) {
            selectedProcedureId = id;
            const procedure = procedures.find(p => p.id == id);
            
            if (!procedure) {
                alert('Error: Procedure not found');
                return;
            }
            
            selectedTemplate = procedure.template;
            
            document.getElementById('procedureSelection').style.display = 'none';
            document.getElementById('dictationForm').style.display = 'block';
            document.getElementById('selectedProcedure').textContent = name;
            
            // Focus first input
            document.querySelector('#dictationForm input').focus();
            
            // Reset activity timeout
            resetActivityTimeout();
        }
        
        // Generate dictation
        function generateDictation() {
            const form = document.getElementById('patientForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);
            let dictation = selectedTemplate;
            
            // Replace placeholders
            for (let [key, value] of formData.entries()) {
                const placeholder = new RegExp('{{' + key + '}}', 'g');
                dictation = dictation.replace(placeholder, value || '[Not provided]');
            }
            
            // Add timestamp
            dictation = 'Generated: ' + new Date().toLocaleString() + '\n\n' + dictation;
            
            document.getElementById('generatedText').textContent = dictation;
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('dictationOutput').style.display = 'block';
            
            resetActivityTimeout();
        }
        
        // Enhanced print function
        function printDictation() {
            // Add watermark if enabled
            const watermark = document.querySelector('.watermark');
            const header = document.querySelector('.confidential-header');
            const footer = document.querySelector('.confidential-footer');
            
            if (document.getElementById('addWatermark').checked) {
                watermark.style.display = 'block';
                header.style.display = 'block';
                footer.style.display = 'block';
            }
            
            // Trigger print
            window.print();
            
            // Hide watermark elements after print
            setTimeout(() => {
                watermark.style.display = 'none';
                header.style.display = 'none';
                footer.style.display = 'none';
                
                // Auto-clear if enabled
                if (document.getElementById('autoClearOnPrint').checked) {
                    clearAndReset();
                    alert('Patient data has been cleared for security.');
                }
            }, 100);
        }
        
        // Enhanced copy function
        function copyToClipboard() {
            const dictationText = document.getElementById('generatedText').textContent;
            
            // Add confidentiality notice if watermark is enabled
            const textToCopy = document.getElementById('addWatermark').checked 
                ? `[CONFIDENTIAL PATIENT INFORMATION]\n\n${dictationText}\n\n[This document contains confidential patient information. Handle according to HIPAA guidelines.]`
                : dictationText;
            
            navigator.clipboard.writeText(textToCopy).then(() => {
                alert('Dictation copied to clipboard!');
                
                // Auto-clear if enabled
                if (document.getElementById('autoClearOnCopy').checked) {
                    clearAndReset();
                    alert('Patient data has been cleared for security.');
                }
            }).catch(err => {
                console.error('Failed to copy:', err);
                alert('Failed to copy to clipboard. Please try again.');
            });
        }
        
        // Cancel form
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? All entered data will be lost.')) {
                document.getElementById('dictationForm').style.display = 'none';
                document.getElementById('procedureSelection').style.display = 'block';
                document.getElementById('patientForm').reset();
                resetActivityTimeout();
            }
        }
        
        // Reset form
        function resetForm() {
            clearAndReset();
        }
    </script>
</body>
</html>
