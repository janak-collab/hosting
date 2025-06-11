#!/bin/bash
# Get the current date in EDT/EST
LOG_DATE=$(TZ='America/New_York' date +%Y-%m-%d)
echo "Checking logs for: $LOG_DATE (America/New_York)"
echo "=================================="
for log in ~/app/storage/logs/*-$LOG_DATE.log; do
    if [ -f "$log" ]; then
        echo -e "\n📄 $(basename $log)"
        tail -3 "$log"
    fi
done
