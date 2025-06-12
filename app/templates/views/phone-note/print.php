<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Phone Note - <?php echo htmlspecialchars($note['patient_name']); ?></title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="theme-color" content="#f26522">
    <link rel="stylesheet" href="/assets/css/modules/phone-notes.css">
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

    <script src="/assets/js/app.js"></script>

</body>
</html>
