#!/bin/bash

echo "GMPM System Monitor - $(date)"
echo "================================"

# Check web services
echo -e "\n📡 Web Service Status:"
curl -s -o /dev/null -w "  - Main site: %{http_code}\n" https://gmpm.us/
curl -s -o /dev/null -w "  - IT Support: %{http_code}\n" https://gmpm.us/it-support
curl -s -o /dev/null -w "  - Phone Note: %{http_code}\n" https://gmpm.us/phone-note
curl -s -o /dev/null -w "  - Admin: %{http_code}\n" https://gmpm.us/admin/login

# Check database
echo -e "\n💾 Database Status:"
php -r '
require_once "app/vendor/autoload.php";
require_once "app/src/bootstrap.php";
try {
    $db = \App\Database\Connection::getInstance()->getConnection();
    echo "  - Connection: ✓ Active\n";
    
    $stmt = $db->query("SELECT 
        (SELECT COUNT(*) FROM it_support_tickets WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)) as tickets_24h,
        (SELECT COUNT(*) FROM phone_notes WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)) as notes_24h");
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "  - IT tickets (24h): " . $stats["tickets_24h"] . "\n";
    echo "  - Phone notes (24h): " . $stats["notes_24h"] . "\n";
} catch (Exception $e) {
    echo "  - Connection: ✗ Error\n";
}
'

# Check disk space
echo -e "\n💿 Disk Usage:"
df -h | grep -E "Filesystem|/home" | awk '{printf "  - %-20s %s used of %s (%s)\n", $1, $3, $2, $5}'

# Check mail queue
echo -e "\n📧 Mail Queue:"
queue_count=$(exim -bpc 2>/dev/null || echo "0")
echo "  - Messages in queue: $queue_count"

echo -e "\n✅ Monitor complete"
