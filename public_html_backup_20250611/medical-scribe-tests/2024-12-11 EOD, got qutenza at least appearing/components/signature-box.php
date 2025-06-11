<?php
// signature-box.php
// Only start session if it hasn't been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Process signature submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['signature'])) {
    // Save the Base64-encoded signature in the session
    $_SESSION['signature'] = $_POST['signature'];
    
    // Decode the Base64 encoded image
    $dataURL = $_POST['signature'];
    $data = explode(',', $dataURL)[1];
    $data = base64_decode($data);
    
    // Save the image as a PNG file
    $filePath = 'signature.png';
    file_put_contents($filePath, $data);
    
    echo "<p>Signature saved successfully!</p>";
    echo "<img src='$filePath' alt='Signature'>";
}

// Clear the signature from the session
if (isset($_POST['clear_signature'])) {
    unset($_SESSION['signature']);
    echo "<p>Signature cleared!</p>";
}
?>

<!-- Just the signature box components without any HTML/HEAD tags -->
<div class="signature-box">
    <canvas id="signatureCanvas" width="400" height="200" style="border: 1px solid #000;"></canvas>
    <br>
    <button type="button" id="clearSignatureCanvas">Clear</button>
    <button type="button" id="saveSignatureCanvas">Save</button>
    
    <form id="signatureForm" method="POST">
        <input type="hidden" name="signature" id="signatureInput">
    </form>
    
    <form method="POST">
        <button type="submit" name="clear_signature">Clear Signature from Session</button>
    </form>
</div>

<!-- Pass any PHP variables to JavaScript if needed -->
<script>
    window.SIGNATURE_CONFIG = {
        hasExistingSignature: <?php echo isset($_SESSION['signature']) ? 'true' : 'false'; ?>
    };
</script>