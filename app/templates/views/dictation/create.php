<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($procedure['name'] ?? 'Dictation'); ?> - 
GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
<link rel="stylesheet" href="/assets/css/modules/dictation.css">
</head>
<body>
    <div class="container no-print">
        <div class="form-card">
            <div class="form-header">
                <h1><?php echo htmlspecialchars($procedure['name'] ?? 
'Dictation'); ?></h1>
                <p>Enter patient information below</p>
            </div>
            
            <div class="form-content">
                <form id="dictationForm">
                    <input type="hidden" id="procedureId" value="<?php echo 
$procedure['id']; ?>">
                    
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
                        <label class="form-label">Provider Name <span 
class="required">*</span></label>
                        <input type="text" id="providerName" class="form-input" 
required 
                               value="<?php echo 
htmlspecialchars($_SESSION['provider_name'] ?? ''); ?>">
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
                    
                    <?php if (!empty($billingCodes)): ?>
                        <div class="billing-info">
                            <strong>Billing Codes:</strong>
                            <?php foreach ($billingCodes as $code): ?>
                                <div>• <?php echo 
htmlspecialchars($code['cpt_code']); ?> - <?php echo 
htmlspecialchars($code['description']); ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-actions">
                        <a href="/dictation" class="btn btn-secondary">← 
Back</a>
                        <button type="button" id="generateBtn" class="btn 
btn-primary">Generate Dictation</button>
                        <button type="button" id="printBtn" class="btn 
btn-primary" style="display: none;">Print</button>
                        <button type="button" id="copyBtn" class="btn 
btn-secondary" style="display: none;">Copy to Clipboard</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div id="generatedContent" class="template-content" style="display: 
none;"></div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('dictationForm');
            const generateBtn = document.getElementById('generateBtn');
            const printBtn = document.getElementById('printBtn');
            const copyBtn = document.getElementById('copyBtn');
            const generatedContent = 
document.getElementById('generatedContent');
            
            // Load template on page load
            let template = <?php echo 
json_encode($procedure['template_content'] ?? ''); ?>;
            
            generateBtn.addEventListener('click', function() {
                if (!form.reportValidity()) {
                    return;
                }
                
                // Get form values
                const data = {
                    patientName: document.getElementById('patientName').value,
                    dob: document.getElementById('dob').value,
                    mrn: document.getElementById('mrn').value,
                    dos: document.getElementById('dos').value,
                    providerName: 
document.getElementById('providerName').value,
                    location: document.getElementById('location').value
                };
                
                // Generate the dictation
                let generated = template;
                
                // Replace placeholders
                generated = generated.replace(/\{patient_name\}/g, 
data.patientName);
                generated = generated.replace(/\{dob\}/g, 
formatDate(data.dob));
                generated = generated.replace(/\{mrn\}/g, data.mrn || 'N/A');
                generated = generated.replace(/\{dos\}/g, 
formatDate(data.dos));
                generated = generated.replace(/\{provider_name\}/g, 
data.providerName);
                generated = generated.replace(/\{location\}/g, data.location);
                generated = generated.replace(/\{date\}/g, formatDate(new 
Date()));
                
                // Display the generated content
                generatedContent.innerHTML = generated;
                generatedContent.style.display = 'block';
                
                // Show action buttons
                printBtn.style.display = 'inline-block';
                copyBtn.style.display = 'inline-block';
                
                // Scroll to generated content
                generatedContent.scrollIntoView({ behavior: 'smooth' });
            });
            
            printBtn.addEventListener('click', function() {
                window.print();
            });
            
            copyBtn.addEventListener('click', function() {
                const text = generatedContent.innerText;
                navigator.clipboard.writeText(text).then(function() {
                    alert('Dictation copied to clipboard!');
                }).catch(function(err) {
                    console.error('Could not copy text: ', err);
                    // Fallback for older browsers
                    const textArea = document.createElement('textarea');
                    textArea.value = text;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    alert('Dictation copied to clipboard!');
                });
            });
            
            function formatDate(dateStr) {
                if (!dateStr) return '';
                const date = new Date(dateStr);
                return (date.getMonth() + 1) + '/' + date.getDate() + '/' + 
date.getFullYear();
            }
        });
    </script>
</body>
</html>
