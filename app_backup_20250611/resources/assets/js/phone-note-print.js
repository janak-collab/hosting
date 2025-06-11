// phone-note-print.js
document.addEventListener('DOMContentLoaded', function() {
    // Get print button
    const printButton = document.getElementById('printButton');
    const closeButton = document.getElementById('closeButton');
    
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    if (closeButton) {
        closeButton.addEventListener('click', function() {
            window.close();
        });
    }
    
    // Auto-print after page loads
    setTimeout(function() {
        window.print();
    }, 500);
});
