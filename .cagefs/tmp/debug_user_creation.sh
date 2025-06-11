#!/bin/bash

# Debug script to find the issue with user creation

echo "=== UserService createUser method signature ==="
grep -B2 -A10 "function createUser" /home/gmpmus/app/src/Services/UserService.php

echo -e "\n=== Checking error logs ==="
tail -30 /home/gmpmus/public_html/error_log | grep -i "user\|error"

echo -e "\n=== Checking database for test users ==="
mysql -u "gmpmus_gmpmuser" -p"]2$K4d9h%D+1Uu7bv$" "gmpmus_gmpm" -e "SELECT id, username, full_name, email, role, is_active, created_at FROM users WHERE username LIKE '%test%' ORDER BY created_at DESC LIMIT 5;"

echo -e "\n=== Checking htpasswd for test users ==="
grep "test" /home/gmpmus/.htpasswds/passwd | cut -d: -f1

echo -e "\n=== Response content (errors) ==="
if [ -f /tmp/response.html ]; then
    grep -B2 -A2 -i "error\|alert\|flash" /tmp/response.html | head -20
fi
