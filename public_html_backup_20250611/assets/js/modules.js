// Module loader for individual components
// This will load modules as needed based on page

document.addEventListener('DOMContentLoaded', function() {
    // Detect which module to load based on page elements
    if (document.getElementById('phoneNoteForm')) {
        loadScript('/assets/js/phone-note-form.js');
    }
    
    if (document.getElementById('supportForm')) {
        loadScript('/assets/js/it-support-form.js');
    }
    
    if (document.getElementById('ipForm')) {
        loadScript('/assets/js/ip-address-manager.js');
    }
    
    if (document.querySelector('.print-container')) {
        loadScript('/assets/js/phone-note-print.js');
    }
});

function loadScript(src) {
    const script = document.createElement('script');
    script.src = src;
    document.body.appendChild(script);
}
