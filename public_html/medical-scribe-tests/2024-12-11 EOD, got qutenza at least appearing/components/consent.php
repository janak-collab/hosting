<?php
session_start();

$agreed = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['agree']) && $_POST['agree'] === 'yes') {
        $agreed = true;
        $_SESSION['consent_agreed'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Consent Form</title>
</head>
<body>
    <h1>Consent Form</h1>
    
    <div class="consent-text">
        <h2>Terms and Conditions</h2>
        <p>By signing this form, I acknowledge and agree to the following:</p>
        <ol>
            <li>I have read and understood the terms presented in this document.</li>
            <li>I voluntarily agree to participate and comply with all stated requirements.</li>
            <li>I understand that I can withdraw my consent at any time.</li>
            <li>I confirm that I am legally capable of giving consent.</li>
            <li>I agree to the collection and processing of my personal information as described.</li>
        </ol>
    </div>

    <?php if (!$agreed && !isset($_SESSION['consent_agreed'])) { ?>
        <form method="POST" id="consentForm">
            <div class="checkbox-container">
                <label>
                    <input type="checkbox" name="agree" value="yes" 
                           onchange="document.getElementById('submitConsent').disabled = !this.checked">
                    I have read and agree to the terms and conditions
                </label>
            </div>
            
            <button type="submit" id="submitConsent" disabled>Continue to Signature</button>
        </form>
    <?php } ?>

    <div class="signature-section" id="signatureSection">
        <h2>Please Sign Below</h2>
        <?php 
        if ($agreed || isset($_SESSION['consent_agreed'])) {
            include 'signature-box.php';
        }
        ?>
    </div>
</body>
</html>