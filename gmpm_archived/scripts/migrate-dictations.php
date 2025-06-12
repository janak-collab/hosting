<?php
// app/scripts/migrate-dictations.php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/bootstrap.php';

use App\Database\Connection;

echo "Starting dictation module migration...\n\n";

$db = Connection::getInstance()->getConnection();

// 1. Import common procedures
echo "Importing procedures...\n";

$procedures = [
    // Epidural Injections
    [
        'name' => 'Lumbar Epidural Steroid Injection',
        'code' => 'lumbar_esi',
        'category' => 'Epidural Injections',
        'template_content' => '
<h2>PROCEDURE NOTE</h2>
<p><strong>PATIENT:</strong> {patient_name}<br>
<strong>DATE OF BIRTH:</strong> {dob}<br>
<strong>MRN:</strong> {mrn}<br>
<strong>DATE OF SERVICE:</strong> {dos}<br>
<strong>LOCATION:</strong> {location}</p>

<p><strong>PROCEDURE:</strong> Lumbar Epidural Steroid Injection</p>

<p><strong>INDICATION:</strong> The patient has lumbar radiculopathy with associated pain.</p>

<p><strong>PROCEDURE DESCRIPTION:</strong><br>
After informed consent was obtained, the patient was placed in the prone position. The lumbar spine was prepped and draped in a sterile manner. Under fluoroscopic guidance, the epidural space was accessed at the {side_level} level using a {needle_size} needle via {approach} approach. Loss of resistance technique was used to confirm epidural placement. Contrast medium was injected to confirm appropriate spread. The following medications were injected: {medications}.</p>

<p><strong>COMPLICATIONS:</strong> None</p>

<p><strong>POST-PROCEDURE:</strong> The patient tolerated the procedure well and was discharged in stable condition with appropriate follow-up instructions.</p>

<p><strong>PROVIDER:</strong> {provider_name}, {provider_title}</p>
',
        'billing_codes' => [
            ['cpt' => '62321', 'desc' => 'Interlaminar epidural injection, lumbar', 'laterality' => 'n/a'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance', 'laterality' => 'n/a']
        ]
    ],
    [
        'name' => 'Cervical Epidural Steroid Injection',
        'code' => 'cervical_esi',
        'category' => 'Epidural Injections',
        'template_content' => '
<h2>PROCEDURE NOTE</h2>
<p><strong>PATIENT:</strong> {patient_name}<br>
<strong>DATE OF BIRTH:</strong> {dob}<br>
<strong>MRN:</strong> {mrn}<br>
<strong>DATE OF SERVICE:</strong> {dos}<br>
<strong>LOCATION:</strong> {location}</p>

<p><strong>PROCEDURE:</strong> Cervical Epidural Steroid Injection</p>

<p><strong>INDICATION:</strong> The patient has cervical radiculopathy with associated pain.</p>

<p><strong>PROCEDURE DESCRIPTION:</strong><br>
After informed consent was obtained, the patient was placed in the prone position. The cervical spine was prepped and draped in a sterile manner. Under fluoroscopic guidance, the epidural space was accessed at the {side_level} level using a {needle_size} needle via {approach} approach. Loss of resistance technique was used to confirm epidural placement. Contrast medium was injected to confirm appropriate spread. The following medications were injected: {medications}.</p>

<p><strong>COMPLICATIONS:</strong> None</p>

<p><strong>POST-PROCEDURE:</strong> The patient tolerated the procedure well and was monitored in recovery. Discharged in stable condition.</p>

<p><strong>PROVIDER:</strong> {provider_name}, {provider_title}</p>
',
        'billing_codes' => [
            ['cpt' => '62320', 'desc' => 'Interlaminar epidural injection, cervical', 'laterality' => 'n/a'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance', 'laterality' => 'n/a']
        ]
    ],
    
    // Facet Injections
    [
        'name' => 'Lumbar Facet Joint Injection',
        'code' => 'lumbar_facet',
        'category' => 'Facet Injections',
        'template_content' => '
<h2>PROCEDURE NOTE</h2>
<p><strong>PATIENT:</strong> {patient_name}<br>
<strong>DATE OF BIRTH:</strong> {dob}<br>
<strong>MRN:</strong> {mrn}<br>
<strong>DATE OF SERVICE:</strong> {dos}<br>
<strong>LOCATION:</strong> {location}</p>

<p><strong>PROCEDURE:</strong> Lumbar Facet Joint Injection</p>

<p><strong>INDICATION:</strong> The patient has lumbar facet arthropathy with associated pain.</p>

<p><strong>PROCEDURE DESCRIPTION:</strong><br>
After informed consent was obtained, the patient was placed in the prone position. The lumbar spine was prepped and draped in a sterile manner. Under fluoroscopic guidance, a {needle_size} needle was advanced to the {side_level} facet joint. Appropriate needle placement was confirmed with contrast. The following medications were injected: {medications}.</p>

<p><strong>COMPLICATIONS:</strong> None</p>

<p><strong>POST-PROCEDURE:</strong> The patient tolerated the procedure well and was discharged in stable condition.</p>

<p><strong>PROVIDER:</strong> {provider_name}, {provider_title}</p>
',
        'billing_codes' => [
            ['cpt' => '64493', 'desc' => 'Injection, diagnostic or therapeutic, lumbar facet L3-L4', 'laterality' => 'bilateral'],
            ['cpt' => '64494', 'desc' => 'Second level lumbar facet', 'laterality' => 'bilateral'],
            ['cpt' => '64495', 'desc' => 'Third and additional levels', 'laterality' => 'bilateral'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance', 'laterality' => 'n/a']
        ]
    ],
    
    // Medial Branch Blocks
    [
        'name' => 'Lumbar Medial Branch Block',
        'code' => 'lumbar_mbb',
        'category' => 'Nerve Blocks',
        'template_content' => '
<h2>PROCEDURE NOTE</h2>
<p><strong>PATIENT:</strong> {patient_name}<br>
<strong>DATE OF BIRTH:</strong> {dob}<br>
<strong>MRN:</strong> {mrn}<br>
<strong>DATE OF SERVICE:</strong> {dos}<br>
<strong>LOCATION:</strong> {location}</p>

<p><strong>PROCEDURE:</strong> Lumbar Medial Branch Block</p>

<p><strong>INDICATION:</strong> The patient has lumbar facet syndrome with associated pain.</p>

<p><strong>PROCEDURE DESCRIPTION:</strong><br>
After informed consent was obtained, the patient was placed in the prone position. The lumbar spine was prepped and draped in a sterile manner. Under fluoroscopic guidance, {needle_size} needles were placed at the junction of the transverse process and superior articular process at levels {side_level}. Appropriate placement was confirmed. The following medications were injected at each level: {medications}.</p>

<p><strong>COMPLICATIONS:</strong> None</p>

<p><strong>POST-PROCEDURE:</strong> The patient tolerated the procedure well and was discharged in stable condition.</p>

<p><strong>PROVIDER:</strong> {provider_name}, {provider_title}</p>
',
        'billing_codes' => [
            ['cpt' => '64493', 'desc' => 'Injection, diagnostic or therapeutic, lumbar medial branch L3-L4', 'laterality' => 'bilateral'],
            ['cpt' => '64494', 'desc' => 'Second level lumbar medial branch', 'laterality' => 'bilateral'],
            ['cpt' => '64495', 'desc' => 'Third and additional levels', 'laterality' => 'bilateral']
        ]
    ],
    
    // SI Joint
    [
        'name' => 'Sacroiliac Joint Injection',
        'code' => 'si_joint',
        'category' => 'Joint Injections',
        'template_content' => '
<h2>PROCEDURE NOTE</h2>
<p><strong>PATIENT:</strong> {patient_name}<br>
<strong>DATE OF BIRTH:</strong> {dob}<br>
<strong>MRN:</strong> {mrn}<br>
<strong>DATE OF SERVICE:</strong> {dos}<br>
<strong>LOCATION:</strong> {location}</p>

<p><strong>PROCEDURE:</strong> Sacroiliac Joint Injection</p>

<p><strong>INDICATION:</strong> The patient has sacroiliac joint dysfunction with associated pain.</p>

<p><strong>PROCEDURE DESCRIPTION:</strong><br>
After informed consent was obtained, the patient was placed in the prone position. The sacroiliac joint region was prepped and draped in a sterile manner. Under fluoroscopic guidance, a {needle_size} needle was advanced into the {side_level} sacroiliac joint. Appropriate intra-articular placement was confirmed with contrast. The following medications were injected: {medications}.</p>

<p><strong>COMPLICATIONS:</strong> None</p>

<p><strong>POST-PROCEDURE:</strong> The patient tolerated the procedure well and was discharged in stable condition.</p>

<p><strong>PROVIDER:</strong> {provider_name}, {provider_title}</p>
',
        'billing_codes' => [
            ['cpt' => '27096', 'desc' => 'Injection procedure for sacroiliac joint', 'laterality' => 'bilateral'],
            ['cpt' => '77003', 'desc' => 'Fluoroscopic guidance', 'laterality' => 'n/a']
        ]
    ]
];

$procedureCount = 0;
foreach ($procedures as $proc) {
    try {
        // Check if procedure already exists
        $stmt = $db->prepare("SELECT id FROM procedures WHERE code = :code");
        $stmt->execute(['code' => $proc['code']]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            echo "  - Skipping {$proc['name']} (already exists)\n";
            continue;
        }
        
        // Insert procedure
        $stmt = $db->prepare("INSERT INTO procedures (name, code, category, template_content) 
                             VALUES (:name, :code, :category, :template)");
        $stmt->execute([
            'name' => $proc['name'],
            'code' => $proc['code'],
            'category' => $proc['category'],
            'template' => $proc['template_content']
        ]);
        $procedureId = $db->lastInsertId();
        
        // Insert billing codes
        foreach ($proc['billing_codes'] as $code) {
            $stmt = $db->prepare("INSERT INTO billing_codes (procedure_id, cpt_code, description, laterality) 
                                 VALUES (:proc_id, :cpt, :desc, :laterality)");
            $stmt->execute([
                'proc_id' => $procedureId,
                'cpt' => $code['cpt'],
                'desc' => $code['desc'],
                'laterality' => $code['laterality'] ?? 'n/a'
            ]);
        }
        
        echo "  ✓ Imported: {$proc['name']}\n";
        $procedureCount++;
        
    } catch (Exception $e) {
        echo "  ✗ Error importing {$proc['name']}: " . $e->getMessage() . "\n";
    }
}

echo "\nImported $procedureCount procedures.\n\n";

// 2. Create sample providers (if they don't exist)
echo "Setting up providers...\n";

$providers = [
    ['full_name' => 'Cyril Ezidiegwu, MD', 'title' => 'MD', 'username' => 'cezidiegwu', 'locations' => ['LSC', 'CSC']],
    ['full_name' => 'Amol Vidyarthi, MD', 'title' => 'MD', 'username' => 'avidyarthi', 'locations' => ['LSC', 'CSC']],
    ['full_name' => 'Test Provider', 'title' => 'MD', 'username' => 'testprovider', 'locations' => ['LSC']]
];

$providerCount = 0;
foreach ($providers as $prov) {
    try {
        // Check if user exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $prov['username']]);
        $user = $stmt->fetch();
        
        if (!$user) {
            // Create user
            $stmt = $db->prepare("INSERT INTO users (username, password_hash, role, active) 
                                 VALUES (:username, :password, 'provider', 1)");
            $stmt->execute([
                'username' => $prov['username'],
                'password' => password_hash('changeme123', PASSWORD_DEFAULT)
            ]);
            $userId = $db->lastInsertId();
            echo "  ✓ Created user: {$prov['username']}\n";
        } else {
            $userId = $user['id'];
            
            // Update role to provider if needed
            $stmt = $db->prepare("UPDATE users SET role = 'provider' WHERE id = :id AND role != 'provider'");
            $stmt->execute(['id' => $userId]);
        }
        
        // Check if provider profile exists
        $stmt = $db->prepare("SELECT id FROM providers WHERE user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        $provider = $stmt->fetch();
        
        if (!$provider) {
            // Create provider profile
            $stmt = $db->prepare("INSERT INTO providers (user_id, full_name, title) 
                                 VALUES (:user_id, :full_name, :title)");
            $stmt->execute([
                'user_id' => $userId,
                'full_name' => $prov['full_name'],
                'title' => $prov['title']
            ]);
            $providerId = $db->lastInsertId();
            
            // Add locations
            foreach ($prov['locations'] as $index => $location) {
                $stmt = $db->prepare("INSERT INTO provider_locations (provider_id, location, is_primary) 
                                     VALUES (:provider_id, :location, :is_primary)
                                     ON DUPLICATE KEY UPDATE is_primary = :is_primary");
                $stmt->execute([
                    'provider_id' => $providerId,
                    'location' => $location,
                    'is_primary' => $index === 0 ? 1 : 0
                ]);
            }
            
            echo "  ✓ Created provider: {$prov['full_name']}\n";
            $providerCount++;
        } else {
            echo "  - Provider {$prov['full_name']} already exists\n";
        }
        
    } catch (Exception $e) {
        echo "  ✗ Error creating provider {$prov['full_name']}: " . $e->getMessage() . "\n";
    }
}

echo "\nCreated $providerCount new providers.\n";

// 3. Create dictations storage directory
$storageDir = dirname(dirname(__DIR__)) . '/storage/dictations';
if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
    echo "\n✓ Created dictations storage directory: $storageDir\n";
} else {
    echo "\n- Dictations storage directory already exists\n";
}

echo "\n✅ Migration complete!\n";
echo "\nDefault credentials for providers:\n";
echo "  Username: [firstname][lastname]\n";
echo "  Password: changeme123\n";
echo "\nPlease have providers change their passwords on first login.\n";
