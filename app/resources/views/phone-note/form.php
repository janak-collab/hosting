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
                <?php echo csrf_field(); ?>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Patient Name <span class="required">*</span></label>
                        <input type="text" id="pname" name="pname" class="form-input" required maxlength="100" value="<?php echo old('pname'); ?>">
                        <div class="form-error" id="pnameError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Birth <span class="required">*</span></label>
                        <input type="date" id="dob" name="dob" class="form-input" required value="<?php echo old('dob'); ?>">
                        <div class="form-error" id="dobError"></div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" class="form-input" required maxlength="10" placeholder="1234567890" value="<?php echo old('phone'); ?>">
                        <div class="phone-preview" id="phonePreview"></div>
                        <div class="form-error" id="phoneError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Caller Name (if different from patient)</label>
                        <input type="text" id="caller_name" name="caller_name" class="form-input" maxlength="100" value="<?php echo old('caller_name'); ?>">
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
                        <input type="date" id="last_seen" name="last_seen" class="form-input" required value="<?php echo old('last_seen'); ?>">
                        <div class="form-error" id="lastSeenError"></div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Next Appointment <span class="required">*</span></label>
                        <input type="date" id="upcoming" name="upcoming" class="form-input" required value="<?php echo old('upcoming'); ?>">
                        <div class="appointment-info" id="appointmentInfo"></div>
                        <div class="form-error" id="upcomingError"></div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Office Location <span class="required">*</span></label>
                    <select name="location" class="form-select" required>
                        <option value="">Select location</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?php echo htmlspecialchars($location); ?>" <?php echo old('location') == $location ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($location); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-error" id="locationError"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message Description <span class="required">*</span></label>
                    <textarea id="description" name="description" class="form-textarea" required maxlength="2000" placeholder="Provide detailed information about the patient's concern..."><?php echo old('description'); ?></textarea>
                    <div class="char-count" id="charCount">0 / 2000</div>
                    <div class="form-error" id="descriptionError"></div>
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
        <p>&copy; <?php echo date('Y'); ?> Greater Maryland Pain Management</p>
        <p><a href="<?php echo url('/'); ?>">Back to Portal</a></p>
    </div>
</div>
