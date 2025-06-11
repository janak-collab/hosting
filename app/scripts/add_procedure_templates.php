<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

$db = Connection::getInstance()->getConnection();

// Sample procedure templates (customize these)
$procedures = [
    [
        'name' => 'Lumbar Epidural Steroid Injection',
        'code' => 'lumbar_esi',
        'category' => 'Injections',
        'template_content' => '
<h3>Procedure: Lumbar Epidural Steroid Injection</h3>

<p><strong>Indication:</strong> {indication}</p>

<p><strong>Procedure:</strong><br>
After obtaining informed consent, the patient was placed in the prone position. 
The lumbar spine was prepped and draped in a sterile fashion. Using 
fluoroscopic guidance, the {level} interspace was identified. The skin was 
anesthetized with 1% lidocaine. A {needle_gauge} gauge Tuohy needle was 
advanced using a {approach} approach under intermittent fluoroscopy.</p>

<p>Loss of resistance to saline was achieved at {depth} cm. Negative aspiration 
for blood and CSF was confirmed. {contrast_amount} mL of {contrast_type} 
contrast was injected, confirming appropriate epidural spread. Subsequently, 
{steroid_amount} mg of {steroid_type} mixed with {anesthetic_amount} mL of 
{anesthetic_type} was slowly injected.</p>

<p>The needle was removed and a bandage was applied. The patient tolerated the 
procedure well with no immediate complications.</p>

<p><strong>Post-Procedure:</strong> The patient was monitored for 30 minutes 
with stable vital signs. Discharge instructions were provided.</p>
',
        'billing_codes' => [
            ['cpt' => '62321', 'desc' => 'Interlaminar epidural injection, 
lumbar'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance']
        ]
    ],
    [
        'name' => 'Cervical Epidural Steroid Injection',
        'code' => 'cervical_esi',
        'category' => 'Injections',
        'template_content' => '
<h3>Procedure: Cervical Epidural Steroid Injection</h3>

<p><strong>Indication:</strong> {indication}</p>

<p><strong>Procedure:</strong><br>
After obtaining informed consent, the patient was placed in the {position} 
position. The cervical spine was prepped and draped in a sterile fashion. Using 
fluoroscopic guidance, the {level} interspace was identified. The skin was 
anesthetized with 1% lidocaine.</p>

<p>A {needle_gauge} gauge Tuohy needle was carefully advanced under continuous 
fluoroscopic guidance. Loss of resistance was achieved at {depth} cm. After 
negative aspiration, {contrast_amount} mL of {contrast_type} was injected, 
confirming epidural spread.</p>

<p>{steroid_amount} mg of {steroid_type} was then injected. The needle was 
removed and the patient was monitored.</p>

<p><strong>Post-Procedure:</strong> No complications. Discharge instructions 
provided.</p>
',
        'billing_codes' => [
            ['cpt' => '62320', 'desc' => 'Interlaminar epidural injection, 
cervical'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance']
        ]
    ],
    [
        'name' => 'Facet Joint Injection',
        'code' => 'facet_injection',
        'category' => 'Injections',
        'template_content' => '
<h3>Procedure: Facet Joint Injection</h3>

<p><strong>Levels:</strong> {levels}</p>
<p><strong>Side:</strong> {side}</p>

<p><strong>Procedure:</strong><br>
The patient was positioned prone and the area was prepped and draped in sterile 
fashion. Under fluoroscopic guidance, the facet joints were identified. Local 
anesthetic was administered. Using a {needle_gauge} gauge needle, the facet 
joints were entered under fluoroscopic guidance.</p>

<p>After negative aspiration, {anesthetic_amount} mL of {anesthetic_type} and 
{steroid_amount} mg of {steroid_type} were injected at each level.</p>

<p>The patient tolerated the procedure well.</p>
',
        'billing_codes' => [
            ['cpt' => '64493', 'desc' => 'Injection, facet joint, 
lumbar/sacral, 1st level'],
            ['cpt' => '64494', 'desc' => 'Injection, facet joint, 
lumbar/sacral, 2nd level'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance']
        ]
    ]
];

// Insert procedures
foreach ($procedures as $proc) {
    try {
        // Check if procedure exists
        $stmt = $db->prepare("SELECT id FROM procedures WHERE code = :code");
        $stmt->execute(['code' => $proc['code']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo "Procedure {$proc['name']} already exists, skipping...\n";
            continue;
        }
        
        // Insert procedure
        $stmt = $db->prepare("
            INSERT INTO procedures (name, code, category, template_content, 
active) 
            VALUES (:name, :code, :category, :template, 1)
        ");
        $stmt->execute([
            'name' => $proc['name'],
            'code' => $proc['code'],
            'category' => $proc['category'],
            'template' => $proc['template_content']
        ]);
        $procedureId = $db->lastInsertId();
        
        // Insert billing codes
        $codeStmt = $db->prepare("
            INSERT INTO billing_codes (procedure_id, cpt_code, description) 
            VALUES (:proc_id, :cpt, :desc)
        ");
        
        foreach ($proc['billing_codes'] as $code) {
            $codeStmt->execute([
                'proc_id' => $procedureId,
                'cpt' => $code['cpt'],
                'desc' => $code['desc']
            ]);
        }
        
        echo "✓ Added procedure: {$proc['name']}\n";
        
    } catch (Exception $e) {
        echo "✗ Error adding {$proc['name']}: " . $e->getMessage() . "\n";
    }
}

echo "\nDone! Procedures are now available in the dictation system.\n";
echo "Remember: No patient data will be saved - everything stays in the 
browser.\n";
