<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phone Note - <?php echo htmlspecialchars($note['patient_name']); ?></title>
    <style>
            @page {
                margin: 0.5in;
                size: letter;
            }
            
            @media print {
                @page {
                    margin: 0.25in;
                }
                
                body { 
                    margin: 0;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
                
                .no-print { 
                    display: none !important; 
                }
                
                /* Remove any margins that might interfere */
                html, body {
                    height: 100%;
                    margin: 0 !important;
                    padding: 0 !important;
                }
            }
            
            body {
                font-family: Arial, sans-serif;
                line-height: 1.4;
                color: #000;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .header {
                text-align: center;
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .info-row {
                display: flex;
                margin-bottom: 10px;
            }
            .info-label {
                font-weight: bold;
                width: 150px;
            }
            .message-box {
                border: 1px solid #000;
                padding: 15px;
                margin: 20px 0;
                min-height: 100px;
            }
            .footer {
                margin-top: 50px;
                border-top: 1px solid #000;
                padding-top: 20px;
            }
            .print-button {
                background: #f26522;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin: 20px;
            }
        </style>
</head>
<body>
    <div class="no-print" style="text-align: center;">
        <p style="font-size: 14px; color: #666;">💡 Tip: In print dialog, uncheck "Headers and footers" for best results</p>
        <button id="printButton" class="print-button">🖨️ Print This Note</button>
        <button id="closeButton" class="print-button">Close Window</button>
    </div>
    
    <div class="header">
        <h1>Greater Maryland Pain Management</h1>
        <h2>Phone Note</h2>
    </div>
    
    <div class="info-row">
        <span class="info-label">Date/Time:</span>
        <span><?php echo date('m/d/Y g:i A', strtotime($note['created_at'])); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Patient Name:</span>
        <span><?php echo htmlspecialchars($note['patient_name']); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Date of Birth:</span>
        <span><?php echo date('m/d/Y', strtotime($note['dob'])); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Phone Number:</span>
        <span><?php echo sprintf('(%s) %s-%s', 
            substr($note['phone'], 0, 3),
            substr($note['phone'], 3, 3),
            substr($note['phone'], 6, 4)
        ); ?></span>
    </div>
    
    <?php if (!empty($note['caller_name'])): ?>
    <div class="info-row">
        <span class="info-label">Caller:</span>
        <span><?php echo htmlspecialchars($note['caller_name']); ?> (HIPAA Authorization Required)</span>
    </div>
    <?php endif; ?>
    
    <div class="info-row">
        <span class="info-label">Location:</span>
        <span><?php echo htmlspecialchars($note['location']); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">For Provider:</span>
        <span><?php echo htmlspecialchars($note['provider']); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Last Seen:</span>
        <span><?php echo date('m/d/Y', strtotime($note['last_seen'])); ?></span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Next Appointment:</span>
        <span><?php echo date('m/d/Y', strtotime($note['upcoming_appointment'])); ?></span>
    </div>
    
    <h3>Message:</h3>
    <div class="message-box">
        <?php echo nl2br(htmlspecialchars($note['description'])); ?>
    </div>
    
    <div class="footer">
        <p>Taken by: <?php echo htmlspecialchars($note['created_by']); ?></p>
        <p>Provider Signature: _________________________________ Date: _____________</p>
        <p>Follow-up Notes:</p>
        <div style="border: 1px solid #000; height: 100px; margin-top: 10px;"></div>
    </div>

    <script src="/assets/js/phone-note-print.js"></script>

</body>
</html>