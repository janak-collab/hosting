<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Note - Greater Maryland Pain Management</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="theme-color" content="#f26522">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="stylesheet" href="/assets/css/modules/phone-notes.css">
    <link rel="stylesheet" href="/assets/css/header-styles.css">
</head>
<body>
    <?php require_once APP_PATH . '/templates/components/header.php'; ?>

    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📞 Phone Note</h1>
                <p>Create a professional phone message record</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <div class="info-box">
                    💡 <strong>HIPAA Reminder:</strong> Ensure caller authorization before discussing patient information.
                </div>
                
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <small>Logged in as: <?php echo htmlspecialchars($user); ?></small>
                </div>
                
                <form method="post" id="phoneNoteForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Patient Name <span class="required">*</span></label>
                            <input type="text" id="pname" name="pname" class="form-input" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="dob" name="dob" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-input" required maxlength="10" placeholder="1234567890">
                            <div class="phone-preview" id="phonePreview"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Caller Name (if different from patient)</label>
                            <input type="text" id="caller_name" name="caller_name" class="form-input" maxlength="100">
                            <div class="hipaa-warning" id="hipaaWarning">
                                <span>⚠️</span>
                                <div>
                                    <strong>HIPAA Alert:</strong> Third-party caller detected. Verify caller is on patient's HIPAA authorization before proceeding.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date Last Seen <span class="required">*</span></label>
                            <input type="date" id="last_seen" name="last_seen" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Next Appointment <span class="required">*</span></label>
                            <input type="date" id="upcoming" name="upcoming" class="form-input" required>
                            <div class="appointment-info" id="appointmentInfo"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Office Location <span class="required">*</span></label>
                        <select name="location" class="form-select" required>
                            <option value="">Select location</option>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?php echo htmlspecialchars($location); ?>"><?php echo htmlspecialchars($location); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Message Description <span class="required">*</span></label>
                        <textarea id="description" name="description" class="form-textarea" required maxlength="2000" placeholder="Provide detailed information about the patient's concern..."></textarea>
                        <div class="char-count" id="charCount">0 / 2000</div>
                    </div>
                    
                    <div class="provider-section">
                        <h3>Select Message Recipient</h3>
                        <div class="provider-buttons">
                            <?php foreach ($providers as $provider): ?>
                            <button type="button" class="provider-button" data-provider="<?php echo htmlspecialchars($provider['name']); ?>">
                                <?php echo htmlspecialchars($provider['display_name']); ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <script src="/assets/js/app.js"></script>
    <script src="/assets/js/header.js"></script>
</body>
</html>
