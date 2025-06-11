<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($procedure['name']); ?> - 
Dictation</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <style>
        .dictation-preview {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            padding: 2rem;
            margin-top: 2rem;
            min-height: 400px;
            font-family: 'Times New Roman', serif;
            line-height: 1.6;
            display: none;
        }
        
        .dictation-preview.active {
            display: block;
        }
        
        .action-buttons {
            position: sticky;
            top: 20px;
            background: white;
            padding: 1rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            margin-top: 2rem;
        }
        
        .no-save-notice {
            background: #e6f7ff;
            border: 1px solid #91d5ff;
            color: #0050b3;
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .dictation-preview {
                border: none;
                padding: 0;
            }
        }
        
        .copy-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--success-color);
            color: white;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card no-print">
            <div class="form-header">
                <h1><?php echo htmlspecialchars($procedure['name']); ?></h1>
                <p>Client-Side Dictation Generator</p>
            </div>
            
            <div class="form-content">
                <div class="no-save-notice">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 
0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 
0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 
100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Privacy Notice:</strong> No patient data is 
saved on the server. 
                        All information stays in your browser only.
                    </div>
                </div>
                
                <form id="dictationForm" onsubmit="return false;">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Patient Name <span 
class="required">*</span></label>
                            <input type="text" id="patientName" 
class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Date of Birth <span 
class="required">*</span></label>
                            <input type="date" id="dob" class="form-input" 
required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">MRN</label>
                            <input type="text" id="mrn" class="form-input">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Date of Service <span 
class="required">*</span></label>
                            <input type="date" id="dos" class="form-input" 
required value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Location <span 
class="required">*</span></label>
                        <select id="location" class="form-select" required>
                            <option value="">Select location</option>
                            <option value="LSC">LSC - Laurel Same Day Surgery 
Center</option>
                            <option value="CSC">CSC - Columbia Surgery 
Center</option>
                        </select>
                    </div>
                    
                    <!-- Procedure-specific fields would go here -->
                    <div id="procedureFields">
                        <!-- These would be dynamically generated based on the 
procedure -->
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" onclick="generateDictation()" 
class="btn btn-primary">
                            Generate Dictation
                        </button>
                        <a href="/dictation" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Preview Section -->
        <div id="dictationPreview" class="dictation-preview">
            <!-- Generated content appears here -->
        </div>
        
        <!-- Action Buttons (shown after generation) -->
        <div id="actionButtons" class="action-buttons no-print" style="display: 
none;">
            <h3>Actions:</h3>
            <div class="form-actions">
                <button onclick="printDictation()" class="btn 
btn-primary">🖨️ Print</button>
                <button onclick="copyToClipboard()" class="btn 
btn-secondary">📋 Copy to Clipboard</button>
                <button onclick="resetForm()" class="btn btn-secondary">🔄 
New Dictation</button>
            </div>
        </div>
    </div>
    
    <div id="copySuccess" class="copy-success">
        ✓ Copied to clipboard!
    </div>
    
    <script>
        // Template data from server (no PHI)
        const procedureTemplate = <?php echo $procedure['template_json']; ?>;
        const providerName = "<?php echo 
htmlspecialchars($provider['full_name']); ?>";
        const providerTitle = "<?php echo htmlspecialchars($provider['title']); 
?>";
        const billingCodes = <?php echo json_encode($billingCodes); ?>;
        
        function generateDictation() {
            // Get form values (all client-side)
            const patientName = document.getElementById('patientName').value;
            const dob = document.getElementById('dob').value;
            const mrn = document.getElementById('mrn').value;
            const dos = document.getElementById('dos').value;
            const location = document.getElementById('location').value;
            
            if (!patientName || !dob || !dos || !location) {
                alert('Please fill in all required fields');
                return;
            }
            
            // Format dates
            const dobFormatted = new Date(dob).toLocaleDateString('en-US');
            const dosFormatted = new Date(dos).toLocaleDateString('en-US');
            
            // Build dictation from template
            let dictation = procedureTemplate;
            
            // Replace placeholders
            dictation = dictation.replace(/{patient_name}/g, patientName);
            dictation = dictation.replace(/{dob}/g, dobFormatted);
            dictation = dictation.replace(/{mrn}/g, mrn || 'N/A');
            dictation = dictation.replace(/{dos}/g, dosFormatted);
            dictation = dictation.replace(/{location}/g, location);
            dictation = dictation.replace(/{provider_name}/g, providerName);
            dictation = dictation.replace(/{provider_title}/g, providerTitle);
            dictation = dictation.replace(/{date}/g, new 
Date().toLocaleDateString('en-US'));
            
            // Add header
            const header = `
                <div style="text-align: center; margin-bottom: 30px;">
                    <h2>Greater Maryland Pain Management</h2>
                    <p>${location === 'LSC' ? 'Laurel Same Day Surgery Center' 
: 'Columbia Surgery Center'}</p>
                    <p>Procedure Note</p>
                </div>
                <hr>
            `;
            
            // Add patient info
            const patientInfo = `
                <p><strong>Patient:</strong> ${patientName}<br>
                <strong>DOB:</strong> ${dobFormatted}<br>
                <strong>MRN:</strong> ${mrn || 'N/A'}<br>
                <strong>DOS:</strong> ${dosFormatted}<br>
                <strong>Location:</strong> ${location}</p>
                <hr>
            `;
            
            // Add footer with signature
            const footer = `
                <div style="margin-top: 50px;">
                    <p>Electronically signed by:</p>
                    <p><strong>${providerName}, ${providerTitle}</strong><br>
                    ${new Date().toLocaleString()}</p>
                </div>
            `;
            
            // Combine all parts
            const fullDictation = header + patientInfo + dictation + footer;
            
            // Display preview
            document.getElementById('dictationPreview').innerHTML = 
fullDictation;
            
document.getElementById('dictationPreview').classList.add('active');
            document.getElementById('actionButtons').style.display = 'block';
            
            // Scroll to preview
            document.getElementById('dictationPreview').scrollIntoView({ 
behavior: 'smooth' });
        }
        
        function printDictation() {
            window.print();
        }
        
        function copyToClipboard() {
            const preview = document.getElementById('dictationPreview');
            const text = preview.innerText || preview.textContent;
            
            navigator.clipboard.writeText(text).then(() => {
                const success = document.getElementById('copySuccess');
                success.style.display = 'block';
                setTimeout(() => {
                    success.style.display = 'none';
                }, 3000);
            }).catch(err => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const success = document.getElementById('copySuccess');
                success.style.display = 'block';
                setTimeout(() => {
                    success.style.display = 'none';
                }, 3000);
            });
        }
        
        function resetForm() {
            if (confirm('Start a new dictation? Current data will be 
cleared.')) {
                document.getElementById('dictationForm').reset();
                
document.getElementById('dictationPreview').classList.remove('active');
                document.getElementById('actionButtons').style.display = 
'none';
                window.scrollTo(0, 0);
            }
        }
        
        // Auto-save to localStorage (browser only)
        document.getElementById('dictationForm').addEventListener('input', 
function() {
            const formData = {
                patientName: document.getElementById('patientName').value,
                dob: document.getElementById('dob').value,
                mrn: document.getElementById('mrn').value,
                dos: document.getElementById('dos').value,
                location: document.getElementById('location').value
            };
            
            // Save to browser's localStorage (never sent to server)
            localStorage.setItem('dictationDraft', JSON.stringify(formData));
        });
        
        // Load from localStorage on page load
        window.addEventListener('load', function() {
            const saved = localStorage.getItem('dictationDraft');
            if (saved) {
                const data = JSON.parse(saved);
                if (confirm('Restore previous draft?')) {
                    document.getElementById('patientName').value = 
data.patientName || '';
                    document.getElementById('dob').value = data.dob || '';
                    document.getElementById('mrn').value = data.mrn || '';
                    document.getElementById('dos').value = data.dos || '';
                    document.getElementById('location').value = data.location 
|| '';
                }
            }
        });
    </script>
</body>
</html>
