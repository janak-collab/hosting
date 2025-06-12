<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation - <?php echo htmlspecialchars($_GET['patient_name'] ?? 'Patient'); ?></title>
<link rel="stylesheet" href="/assets/css/modules/dictation.css">
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <button onclick="window.print()" class="print-button">🖨️ Print This Dictation</button>
        <button onclick="window.close()" class="print-button">Close Window</button>
    </div>
    
    <div class="header">
        <h1>Greater Maryland Pain Management</h1>
        <h2>Ambulatory Surgery Center</h2>
        <h2>Procedure Dictation</h2>
    </div>
    
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Date Created:</span>
            <span id="printDate"><?php echo date('F j, Y'); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Procedure Date:</span>
            <span id="printProcedureDate"></span>
        </div>
        <div class="info-row">
            <span class="info-label">Provider:</span>
            <span id="printProvider"></span>
        </div>
        <div class="info-row">
            <span class="info-label">Location:</span>
            <span id="printLocation"></span>
        </div>
    </div>
    
    <div class="dictation-content" id="dictationContent">
        <!-- Content populated by JavaScript -->
    </div>
    
    <div class="billing-section">
        <h3>Billing Codes:</h3>
        <div class="billing-codes" id="printBillingCodes">
            <!-- Codes populated by JavaScript -->
        </div>
    </div>
    
    <div class="signature-line">
        <div class="signature-field">
            Provider Signature
        </div>
        <div class="signature-field">
            Date
        </div>
    </div>
    
    <div class="footer">
        <p>Generated on <?php echo date('F j, Y \a\t g:i A'); ?></p>
        <p>This document contains confidential medical information. Handle according to HIPAA guidelines.</p>
    </div>
    
    <script>
        // Auto-print when window loads
        window.addEventListener('load', function() {
            // Populate content from URL parameters or parent window
            const urlParams = new URLSearchParams(window.location.search);
            
            // Set content if passed via URL
            if (urlParams.has('content')) {
                document.getElementById('dictationContent').textContent = decodeURIComponent(urlParams.get('content'));
            }
            if (urlParams.has('provider')) {
                document.getElementById('printProvider').textContent = decodeURIComponent(urlParams.get('provider'));
            }
            if (urlParams.has('location')) {
                document.getElementById('printLocation').textContent = decodeURIComponent(urlParams.get('location'));
            }
            if (urlParams.has('procedure_date')) {
                document.getElementById('printProcedureDate').textContent = decodeURIComponent(urlParams.get('procedure_date'));
            }
            if (urlParams.has('billing_codes')) {
                const codes = decodeURIComponent(urlParams.get('billing_codes')).split(',');
                const container = document.getElementById('printBillingCodes');
                codes.forEach(code => {
                    const span = document.createElement('span');
                    span.className = 'code-item';
                    span.textContent = code;
                    container.appendChild(span);
                });
            }
            
            // Delay auto-print slightly to ensure content is loaded
            setTimeout(function() {
                window.print();
            }, 500);
        });
        
        // Handle after print
        window.addEventListener('afterprint', function() {
            // If opened in a new window/tab, offer to close
            if (window.opener) {
                window.close();
            }
        });
    </script>
</body>
</html>
