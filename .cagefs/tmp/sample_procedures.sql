-- Insert sample ASC procedures
INSERT INTO ASC_procedures (name, code, template_content, category, active) VALUES
('Lumbar Epidural Steroid Injection', 'LESI', 
'PROCEDURE: Lumbar Epidural Steroid Injection
DATE: [PROCEDURE_DATE]
PATIENT: [PATIENT_NAME]
DOB: [DOB]
PROVIDER: [PROVIDER]
LOCATION: [LOCATION]

INDICATION: Lumbar radiculopathy with lower extremity pain.

PROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The lumbar area was prepped and draped in a sterile fashion. Under fluoroscopic guidance, the epidural space was accessed at the L[LEVEL] level using a [GAUGE]-gauge Tuohy needle with loss of resistance technique. Correct needle placement was confirmed with contrast injection showing appropriate epidural spread. [STEROID] mg of steroid with [VOLUME] mL of preservative-free saline was injected. The needle was removed and a bandage was applied.

The patient tolerated the procedure well without complications.

POST-PROCEDURE: The patient was monitored for 30 minutes and discharged in stable condition with follow-up instructions.', 
'Spine Injections', 1),

('Cervical Epidural Steroid Injection', 'CESI',
'PROCEDURE: Cervical Epidural Steroid Injection
DATE: [PROCEDURE_DATE]
PATIENT: [PATIENT_NAME]
DOB: [DOB]
PROVIDER: [PROVIDER]
LOCATION: [LOCATION]

INDICATION: Cervical radiculopathy with upper extremity pain.

PROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The cervical area was prepped and draped in a sterile fashion. Under fluoroscopic guidance, the epidural space was accessed at the C[LEVEL] level using a [GAUGE]-gauge Tuohy needle. Correct needle placement was confirmed with contrast injection. [STEROID] mg of steroid with [VOLUME] mL of preservative-free saline was injected. The needle was removed and a bandage was applied.

The patient tolerated the procedure well without complications.

POST-PROCEDURE: The patient was monitored for 30 minutes and discharged in stable condition.',
'Spine Injections', 1),

('Knee Joint Injection', 'KNEE',
'PROCEDURE: Knee Joint Injection
DATE: [PROCEDURE_DATE]
PATIENT: [PATIENT_NAME]
DOB: [DOB]
PROVIDER: [PROVIDER]
LOCATION: [LOCATION]

INDICATION: Knee osteoarthritis with pain and functional limitation.

PROCEDURE: After informed consent was obtained, the patient was positioned supine with the knee slightly flexed. The [SIDE] knee was prepped and draped in a sterile fashion. Using a [APPROACH] approach, a [GAUGE]-gauge needle was advanced into the joint space. Aspiration revealed [FLUID]. [MEDICATION] was injected into the joint space. The needle was removed and a bandage was applied.

The patient tolerated the procedure well without complications.',
'Joint Injections', 1),

('Trigger Point Injection', 'TPI',
'PROCEDURE: Trigger Point Injection
DATE: [PROCEDURE_DATE]
PATIENT: [PATIENT_NAME]
DOB: [DOB]
PROVIDER: [PROVIDER]
LOCATION: [LOCATION]

INDICATION: Myofascial pain syndrome with trigger points.

PROCEDURE: After informed consent was obtained, the patient was positioned comfortably. The trigger points were identified by palpation in the [MUSCLES] muscles. The area was prepped with alcohol. Using a [GAUGE]-gauge needle, [MEDICATION] was injected into each trigger point. A total of [NUMBER] trigger points were injected.

The patient tolerated the procedure well without complications.',
'Soft Tissue Injections', 1),

('Sacroiliac Joint Injection', 'SIJI',
'PROCEDURE: Sacroiliac Joint Injection
DATE: [PROCEDURE_DATE]
PATIENT: [PATIENT_NAME]
DOB: [DOB]
PROVIDER: [PROVIDER]
LOCATION: [LOCATION]

INDICATION: Sacroiliac joint dysfunction with lower back and buttock pain.

PROCEDURE: After informed consent was obtained, the patient was placed in the prone position. The [SIDE] sacroiliac joint region was prepped and draped in a sterile fashion. Under fluoroscopic guidance, a [GAUGE]-gauge spinal needle was advanced into the sacroiliac joint. Correct placement was confirmed with contrast injection showing intra-articular spread. [MEDICATION] was injected. The needle was removed and a bandage was applied.

The patient tolerated the procedure well without complications.',
'Spine Injections', 1);

-- Insert billing codes for procedures
INSERT INTO ASC_procedures_billing_codes (procedure_id, cpt_code, description) VALUES
(1, '62322', 'Injection, single interlaminar epidural, lumbar'),
(1, '77003', 'Fluoroscopic guidance for spine injection'),
(2, '62320', 'Injection, single interlaminar epidural, cervical'),
(2, '77003', 'Fluoroscopic guidance for spine injection'),
(3, '20610', 'Arthrocentesis, major joint'),
(4, '20552', 'Injection, single or multiple trigger points, 1-2 muscles'),
(5, '27096', 'Injection procedure for sacroiliac joint, anesthetic/steroid'),
(5, '77003', 'Fluoroscopic guidance for spine injection');

-- Associate provider 2 (Carley Morris) with some procedures
INSERT INTO ASC_provider_procedures (provider_id, procedure_id, can_perform, is_favorite) VALUES
(2, 1, 1, 1), -- LESI - favorite
(2, 2, 1, 0), -- CESI
(2, 3, 1, 1), -- Knee - favorite
(2, 4, 1, 0), -- TPI
(2, 5, 1, 0); -- SIJI
