<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation - <?php echo htmlspecialchars($_GET['patient_name'] ?? 'Patient'); ?></title>
    <style>
        @page {
            margin: 0.75in;
            size: letter;
        }
        
        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #000;
        }
        
        .header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .header h2 {
            margin: 0.5rem 0 0 0;
            font-size: 1.25rem;
            font-weight: normal;
        }
        
        .info-section {
            margin-bottom: 1.5rem;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.5rem;
        }
        
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        
        .dictation-content {
            margin: 2rem 0;
            padding: 1rem;
            border: 1px solid #ccc;
            background: #f9f9f9;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        
        .billing-section {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #ccc;
        }
        
        .billing-section h3 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .billing-codes {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .code-item {
            background: #e0e0e0;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
        }
        
        .footer {
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #ccc;
            font-size: 0.875rem;
            color: #666;
        }
        
        .signature-line {
            margin-top: 3rem;
            display: flex;
            align-items: flex-end;
            gap: 2rem;
        }
        
        .signature-field {
            flex: 1;
            border-bottom: 1px solid #000;
            padding-bottom: 0.25rem;
        }
        
        .print-button {
            background: #f26522;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            margin: 20px;
        }
        
        .print-button:hover {
            background: #d4541d;
        }
    </style>
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
