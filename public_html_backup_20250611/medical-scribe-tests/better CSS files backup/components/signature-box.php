<?php
// PHP code remains the same
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['signature'])) {
    $_SESSION['signature'] = $_POST['signature'];
    
    $signaturesDir = '../signatures';
    if (!file_exists($signaturesDir)) {
        mkdir($signaturesDir, 0777, true);
    }
    
    $dataURL = $_POST['signature'];
    $data = explode(',', $dataURL)[1];
    $data = base64_decode($data);
    
    $filePath = $signaturesDir . '/signature_' . time() . '.png';
    file_put_contents($filePath, $data);
    
    echo "<div class='success-message'>Signature saved successfully!</div>";
    echo "<img src='" . $filePath . "' alt='Signature' style='max-width: 400px; margin-top: 10px;'>";
}

if (isset($_POST['clear_signature'])) {
    unset($_SESSION['signature']);
    echo "<div class='success-message'>Signature cleared!</div>";
}
?>

<div class="signature-box">
    <canvas id="signatureCanvas"></canvas>
    <div class="button-group">
        <button type="button" id="clearSignatureCanvas">Clear Signature</button>
        <button type="button" id="saveSignatureCanvas">Save Signature</button>
    </div>
    
    <form id="signatureForm" method="POST">
        <input type="hidden" name="signature" id="signatureInput">
    </form>
    
    <form method="POST" class="clear-signature-form">
        <button type="submit" name="clear_signature" class="clear-saved-btn">Clear Saved Signature</button>
    </form>
</div>

<style>
.signature-box {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
    max-width: 500px;
}

#signatureCanvas {
    width: 400px;
    height: 200px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    background-color: #fff;
    cursor: crosshair;
    touch-action: none;
}

.button-group {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

button {
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s;
}

#clearSignatureCanvas {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

#saveSignatureCanvas {
    background-color: #0d6efd;
    border: 1px solid #0a58ca;
    color: white;
}

.success-message {
    color: #198754;
    background-color: #d1e7dd;
    padding: 10px;
    border-radius: 4px;
    margin: 10px 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');
    const form = document.getElementById('signatureForm');
    const signatureInput = document.getElementById('signatureInput');
    const clearButton = document.getElementById('clearSignatureCanvas');
    const saveButton = document.getElementById('saveSignatureCanvas');

    // Set canvas size
    canvas.width = 400;
    canvas.height = 200;

    // Set drawing style
    ctx.strokeStyle = '#000000';
    ctx.lineWidth = 2;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';

    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    function getPosition(event) {
        const rect = canvas.getBoundingClientRect();
        if (event.type.includes('touch')) {
            return {
                x: event.touches[0].clientX - rect.left,
                y: event.touches[0].clientY - rect.top
            };
        }
        return {
            x: event.clientX - rect.left,
            y: event.clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        const pos = getPosition(e);
        lastX = pos.x;
        lastY = pos.y;
        e.preventDefault();
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();

        const pos = getPosition(e);
        
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        
        lastX = pos.x;
        lastY = pos.y;
    }

    function stopDrawing() {
        isDrawing = false;
    }

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        signatureInput.value = '';
    }

    function saveSignature() {
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
        const isEmpty = !imageData.some(channel => channel !== 0);
        
        if (isEmpty) {
            alert('Please sign before saving.');
            return;
        }

        signatureInput.value = canvas.toDataURL();
        form.submit();
    }

    // Mouse events
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Touch events
    canvas.addEventListener('touchstart', startDrawing, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchcancel', stopDrawing);

    // Button events
    clearButton.addEventListener('click', clearCanvas);
    saveButton.addEventListener('click', saveSignature);
});
</script>