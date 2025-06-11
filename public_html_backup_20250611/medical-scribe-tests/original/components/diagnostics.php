<?php
$imaging_types = [
    'shoulder' => 'Shoulder',
    'hip' => 'Hip',
    'SIJ' => 'SIJ',
    'knee' => 'Knee'
];

$spine_imaging = [
    'cervical' => 'Cervical spine',
    'thoracic' => 'Thoracic spine',
    'lumbar' => 'Lumbar spine'
];

function formatLabel($key, $label) {
    return $key === 'SIJ' ? 'SIJ' : strtolower($label);
}
?>

<section class="diagnostics">
    <h2>Diagnostics</h2>
    
    <div class="diagnostics-controls">
        <button type="button" id="expandImaging" class="radio-option">
            Add Imaging
        </button>
    </div>

    <div class="imaging-grid collapsed">
        <div class="imaging-content">
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
    </div>
</section>

<!-- Pass PHP variables to JavaScript if needed -->
<script>
    window.IMAGING_CONFIG = {
        types: <?php echo json_encode($imaging_types); ?>,
        spine: <?php echo json_encode($spine_imaging); ?>
    };
</script>