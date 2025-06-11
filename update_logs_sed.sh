#!/bin/bash
# Auto-generated sed commands to update error_log calls

# Update app/src/Controllers/ITSupportController.php
sed -i.bak 's/error_log(/\/\/error_log(/g' app/src/Controllers/ITSupportController.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' app/src/Controllers/ITSupportController.php || sed -i '/^namespace/a\use App\\Services\\Logger;' app/src/Controllers/ITSupportController.php

# Update app/src/Services/EmailService.php
sed -i.bak 's/error_log(/\/\/error_log(/g' app/src/Services/EmailService.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' app/src/Services/EmailService.php || sed -i '/^namespace/a\use App\\Services\\Logger;' app/src/Services/EmailService.php

# Update app/src/Services/Logger.php
sed -i.bak 's/error_log(/\/\/error_log(/g' app/src/Services/Logger.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' app/src/Services/Logger.php || sed -i '/^namespace/a\use App\\Services\\Logger;' app/src/Services/Logger.php

# Update app/src/Models/PhoneNote.php
sed -i.bak 's/error_log(/\/\/error_log(/g' app/src/Models/PhoneNote.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' app/src/Models/PhoneNote.php || sed -i '/^namespace/a\use App\\Services\\Logger;' app/src/Models/PhoneNote.php

# Update public_html/api/public/summary.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/api/public/summary.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/api/public/summary.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/api/public/summary.php

# Update public_html/app/src/Controllers/ITSupportController.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/app/src/Controllers/ITSupportController.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/app/src/Controllers/ITSupportController.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/app/src/Controllers/ITSupportController.php

# Update public_html/app/src/Services/EmailService.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/app/src/Services/EmailService.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/app/src/Services/EmailService.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/app/src/Services/EmailService.php

# Update public_html/app/src/Models/PhoneNote.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/app/src/Models/PhoneNote.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/app/src/Models/PhoneNote.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/app/src/Models/PhoneNote.php

# Update public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/includes/config.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/includes/config.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/includes/config.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/includes/config.php

# Update public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/components/patient-info.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/components/patient-info.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/components/patient-info.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/components/patient-info.php

# Update public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/radiology-order.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/radiology-order.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/radiology-order.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/test, after 2024-12-09 changes, work on making medication dose and frequency rounded and space at top and bottom of med list/radiology-order.php

# Update public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/includes/config.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/includes/config.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/includes/config.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/includes/config.php

# Update public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/components/patient-info.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/components/patient-info.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/components/patient-info.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/components/patient-info.php

# Update public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/radiology-order.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/radiology-order.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/radiology-order.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/2024-12-11 EOD, got qutenza at least appearing/radiology-order.php

# Update public_html/medical-scribe-tests/current test, backup/includes/config.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/current test, backup/includes/config.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/includes/config.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/includes/config.php

# Update public_html/medical-scribe-tests/current test, backup/components/patient-info.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/current test, backup/components/patient-info.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/components/patient-info.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/components/patient-info.php

# Update public_html/medical-scribe-tests/current test, backup/radiology-order.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/current test, backup/radiology-order.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/radiology-order.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/current test, backup/radiology-order.php

# Update public_html/medical-scribe-tests/better CSS files backup/includes/security.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/better CSS files backup/includes/security.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/includes/security.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/includes/security.php

# Update public_html/medical-scribe-tests/better CSS files backup/includes/config.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/better CSS files backup/includes/config.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/includes/config.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/includes/config.php

# Update public_html/medical-scribe-tests/better CSS files backup/process.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/better CSS files backup/process.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/process.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/process.php

# Update public_html/medical-scribe-tests/better CSS files backup/components/patient-info.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/better CSS files backup/components/patient-info.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/components/patient-info.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/components/patient-info.php

# Update public_html/medical-scribe-tests/better CSS files backup/radiology-order.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/better CSS files backup/radiology-order.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/radiology-order.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/better CSS files backup/radiology-order.php

# Update public_html/medical-scribe-tests/original/includes/config.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/original/includes/config.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/original/includes/config.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/original/includes/config.php

# Update public_html/medical-scribe-tests/original/components/patient-info.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/original/components/patient-info.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/original/components/patient-info.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/original/components/patient-info.php

# Update public_html/medical-scribe-tests/original/radiology-order.php
sed -i.bak 's/error_log(/\/\/error_log(/g' public_html/medical-scribe-tests/original/radiology-order.php
# Add Logger use statement if not present
grep -q 'use App\\Services\\Logger;' public_html/medical-scribe-tests/original/radiology-order.php || sed -i '/^namespace/a\use App\\Services\\Logger;' public_html/medical-scribe-tests/original/radiology-order.php

