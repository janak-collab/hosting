<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation System - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="/assets/css/modules/dictation.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📝 Dictation System</h1>
                <p>Create procedure dictations for patients</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <!-- Batch Settings Banner -->
                <div class="batch-banner" id="batchBanner">
                    <div class="batch-info">
                        <div class="batch-details">
                            <div class="batch-item">
                                <span>👨‍⚕️</span>
                                <span id="selectedProvider">Provider</span>
                            </div>
                            <div class="batch-item">
                                <span>📍</span>
                                <span id="selectedLocation">Location</span>
                            </div>
                            <div class="batch-item">
                                <span>📅</span>
                                <span id="selectedDate">Date</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="changeSettingsBtn">Change Settings</button>
                    </div>
                </div>
                
                <!-- Batch Setup Section -->
                <div id="batchSetup">
                    <h3>Batch Settings</h3>
                    <p class="text-secondary">Set these once for all dictations in this session</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="provider" class="form-label">
                                Provider <span class="required">*</span>
                            </label>
                            <select id="provider" class="form-select" required>
                                <option value="">Select Provider</option>
                                <?php foreach ($providers as $provider): ?>
                                <option value="<?php echo $provider['id']; ?>">
                                    <?php echo htmlspecialchars($provider['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="form-label">
                                Location <span class="required">*</span>
                            </label>
                            <select id="location" class="form-select" required>
                                <option value="">Select Location</option>
                                <?php foreach ($locations as $location): ?>
                                <option value="<?php echo htmlspecialchars($location); ?>">
                                    <?php echo htmlspecialchars($location); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="procedure_date" class="form-label">
                                Procedure Date <span class="required">*</span>
                            </label>
                            <input type="date" id="procedure_date" class="form-input" required>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-primary" id="startBatchBtn">
                        Start Batch Dictations
                    </button>
                </div>
                
                <!-- Patient Section -->
                <div id="patientSection" class="patient-section">
                    <h3>Patient Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient_name" class="form-label">
                                Patient Name <span class="required">*</span>
                            </label>
                            <input type="text" id="patient_name" class="form-input" 
                                   placeholder="Enter patient name" autocomplete="off">
                        </div>
                        
                        <div class="form-group">
                            <label for="patient_dob" class="form-label">
                                Date of Birth <span class="required">*</span>
                            </label>
                            <input type="date" id="patient_dob" class="form-input">
                        </div>
                    </div>
                </div>
                
                <!-- Procedure Selection -->
                <div id="procedureSection" class="patient-section">
                    <h3>Select Procedure</h3>
                    
                    <!-- Category Filter Tabs -->
                    <div class="category-tabs" id="categoryTabs">
                        <button type="button" class="category-tab active" data-category="all">All</button>
                        <button type="button" class="category-tab" data-category="favorites">⭐ Favorites</button>
                    </div>
                    
                    <!-- Procedures Grid -->
                    <div class="procedure-grid" id="procedureList">
                        <!-- Populated by JavaScript -->
                    </div>
                    
                    <!-- Billing Codes Display -->
                    <div id="billingCodes" class="procedure-codes" style="display: none; margin-top: 1rem;"></div>
                </div>
                
                <!-- Preview Section -->
                <div id="previewSection" class="preview-section">
                    <h3>Dictation Preview</h3>
                    <div id="previewContent" class="preview-content"></div>
                    
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <button type="button" class="btn btn-secondary" onclick="window.print()">
                            🖨️ Print Preview
                        </button>
                        <button type="button" class="btn btn-primary" id="printBtn">
                            🖨️ Print Dictation
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <!-- Status Footer -->
    <div class="status-footer" id="statusFooter">
        <div class="status-content">
            <div class="status-left">
                <div class="status-item">
                    <span>📋</span>
                    <span>Dictations: <span class="print-counter" id="printCounter">0</span></span>
                </div>
                <div class="status-item">
                    <span>📍</span>
                    <span id="footerLocation">Location</span>
                </div>
                <div class="status-item">
                    <span>👨‍⚕️</span>
                    <span id="footerProvider">Provider</span>
                </div>
                <div class="status-item">
                    <span>📅</span>
                    <span id="footerDate">Date</span>
                </div>
            </div>
            <div class="status-right">
                <div class="status-item">
                    <span>⏱️</span>
                    <span>Session expires in: <span class="timer" id="sessionTimer">15:00</span></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Timeout Modal -->
    <div class="timeout-modal" id="timeoutModal">
        <div class="timeout-content">
            <h2>⚠️ Session Expiring</h2>
            <p>Your session will expire in:</p>
            <div class="timeout-timer" id="timeoutTimer">60</div>
            <p>Move your mouse or press any key to continue working.</p>
            <button type="button" class="btn btn-primary" onclick="window.resetIdleTimer()">
                Continue Working
            </button>
        </div>
    </div>
    
    <script src="/assets/js/dictation-form.js"></script>
</body>
</html>
