/**
 * Validate all form inputs
 * Checks patient info, dates, medications, and imaging selections
 * 
 * @returns {string[]} Array of error messages, empty if all valid
 */
const validateForm = () => {
    const form = document.getElementById('radiologyForm');
    const errors = [];
    
    // Patient name validation
    const patientName = form.patient_name.value.trim();
    if (!patientName) {
        errors.push('Patient name is required');
    } else if (!/^[a-zA-Z. -]{2,50}$/.test(patientName)) {
        errors.push('Invalid patient name format');
    }

    // DOB validation
    if (!form.dob.value) {
        errors.push('Date of birth is required');
    } else {
        const dob = new Date(form.dob.value);
        dob.setHours(0, 0, 0, 0);  // Set to midnight
        const today = new Date();
        today.setHours(0, 0, 0, 0);  // Set to midnight
        
        const year = dob.getFullYear();
        if (year < 1900 || year > 2050) {
            errors.push('Date of birth must be between 1900 and 2050');
        } else if (dob > today) {
            errors.push('Date of birth cannot be in the future');
        }
    }
    
    // DOS validation
    if (!form.dos.value) {
        errors.push('Date of service is required');
    } else {
        const dos = new Date(form.dos.value);
        dos.setHours(0, 0, 0, 0);  // Set to midnight
        const today = new Date();
        today.setHours(0, 0, 0, 0);  // Set to midnight
        
        const ninetyDaysAgo = new Date(today);
        ninetyDaysAgo.setDate(ninetyDaysAgo.getDate() - 90);
        ninetyDaysAgo.setHours(0, 0, 0, 0);
        
        if (dos > today) {
            errors.push('Date of service cannot be in the future');
        } else if (dos < ninetyDaysAgo) {
            errors.push('Date of service cannot be more than 90 days in the past');
        }
    }
    
    // Provider validation
    const providerSelected = form.querySelector('input[name="provider_name"]:checked');
    if (!providerSelected) {
        errors.push('Provider selection is required');
    }
    
    // Medication dose validation
    const flexeril = form.querySelector('input[value="Flexeril"]:checked');
    if (flexeril && !form.flexeril_dose.value) {
        errors.push('Please select Flexeril dose');
    }
    
    const methocarbamol = form.querySelector('input[value="Methocarbamol"]:checked');
    if (methocarbamol && !form.methocarbamol_dose.value) {
        errors.push('Please select Methocarbamol dose');
    }
    
// Validate at least one selection in each selected imaging group
const imagingGroups = ['shoulder', 'hip', 'SIJ', 'knee'];
imagingGroups.forEach(group => {
    const xrayChecked = form.querySelector(`input[name="${group}_xray"]:checked`);
    const mriChecked = form.querySelector(`input[name="${group}_mri"]:checked`);
    if (xrayChecked && mriChecked) {
        errors.push(`Cannot select both X-ray and MRI for ${group}`);
    }
});

// Spine imaging validation
const spineGroups = ['cervical', 'thoracic', 'lumbar'];
spineGroups.forEach(group => {
    const spineSelection = form.querySelector(`input[name="${group}_spine"]:checked`);
    if (spineSelection) {
        const value = spineSelection.value;
        const hasXray = value.includes('XR');
        const hasMRI = value.includes('MRI');
        if (hasXray && hasMRI) {
            errors.push(`Cannot select both X-ray and MRI for ${group} spine`);
        }
    }
});
    return errors;
};