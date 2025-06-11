<?php
$imaging_types = [
    'shoulder' => 'Shoulder',
    'hip' => 'Hip',
    'SIJ' => 'SIJ',
    'knee' => 'Knee'
];

$spine_imaging = [
    'cervical' => 'Cervical',
    'thoracic' => 'Thoracic',
    'lumbar' => 'Lumbar'
];
?>

<section class="diagnostics">
    <h2>Diagnostics</h2>
    
    <!-- Updated button to match other treatment buttons -->
    <button type="button" id="toggleImaging" class="radio-option toggle-imaging-btn">
        Add Imaging
    </button>
    
    <div class="imaging-grid collapsed" id="imagingContent">
        <?php foreach ($imaging_types as $key => $label): ?>
            <div class="imaging-type">
                <div class="imaging-buttons">
                    <label class="radio-option">
                        <input type="radio" 
                               id="<?php echo $key; ?>_xray_select" 
                               name="<?php echo $key; ?>_type" 
                               value="xray">
                        <?php echo htmlspecialchars($label); ?> X-Ray
                    </label>
                    <label class="radio-option">
                        <input type="radio" 
                               id="<?php echo $key; ?>_mri_select" 
                               name="<?php echo $key; ?>_type" 
                               value="mri">
                        <?php echo htmlspecialchars($label); ?> MRI
                    </label>
                </div>
                
                <div class="side-buttons">
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo $key; ?>_side" 
                               value="right"
                               disabled>
                        Right
                    </label>
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo $key; ?>_side" 
                               value="left"
                               disabled>
                        Left
                    </label>
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo $key; ?>_side" 
                               value="bilateral"
                               disabled>
                        Bilateral
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
        
        <?php foreach ($spine_imaging as $key => $label): ?>
            <div class="imaging-type">
                <div class="imaging-buttons">
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo $key; ?>_spine" 
                               value="<?php echo $label; ?> XR">
                        <?php echo htmlspecialchars($label); ?> X-Ray
                    </label>
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo $key; ?>_spine" 
                               value="<?php echo $label; ?> MRI">
                        <?php echo htmlspecialchars($label); ?> MRI
                    </label>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Pass PHP variables to JavaScript if needed -->
<script>
    window.IMAGING_CONFIG = {
        types: <?php echo json_encode($imaging_types); ?>,
        spine: <?php echo json_encode($spine_imaging); ?>
    };
</script>