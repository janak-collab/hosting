-- Insert billing codes for procedures with correct IDs
INSERT INTO ASC_procedures_billing_codes (procedure_id, cpt_code, description) VALUES
(7, '62322', 'Injection, single interlaminar epidural, lumbar'),
(7, '77003', 'Fluoroscopic guidance for spine injection'),
(8, '62320', 'Injection, single interlaminar epidural, cervical'),
(8, '77003', 'Fluoroscopic guidance for spine injection'),
(9, '20610', 'Arthrocentesis, major joint'),
(10, '20552', 'Injection, single or multiple trigger points, 1-2 muscles'),
(11, '27096', 'Injection procedure for sacroiliac joint, anesthetic/steroid'),
(11, '77003', 'Fluoroscopic guidance for spine injection');

-- Associate provider 2 (Carley Morris) with procedures
INSERT INTO ASC_provider_procedures (provider_id, procedure_id, can_perform, is_favorite) VALUES
(2, 7, 1, 1),  -- LESI - favorite
(2, 8, 1, 0),  -- CESI
(2, 9, 1, 1),  -- Knee - favorite
(2, 10, 1, 0), -- TPI
(2, 11, 1, 0); -- SIJI
