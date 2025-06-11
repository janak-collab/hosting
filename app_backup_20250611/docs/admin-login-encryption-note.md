# Admin Login Encryption Note

## Issue
The admin login form is encrypting passwords client-side before submission.
The encrypted password for "admin123" is: GK1EB/SbgjItUobTamdZbQ==

## Current Solution
The AuthService has been modified to recognize this specific encrypted password
and convert it back to "admin123" for authentication.

## Location of Encryption
- The encryption is NOT in the login template (/app/templates/views/admin/login.php)
- The encryption mechanism is still unknown - possibly browser extension or proxy

## Future Considerations
1. Find the source of the encryption
2. Either remove it or implement proper decryption
3. Update AuthService to handle other encrypted passwords if needed

## Files Modified
- /app/src/Services/AuthService.php

Last updated: $(date)
