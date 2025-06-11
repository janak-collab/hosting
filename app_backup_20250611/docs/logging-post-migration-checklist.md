# Post-Migration Checklist

## Daily Monitoring
- [ ] Check /logging-status.php for any unusual activity
- [ ] Monitor error logs for critical issues: tail -f app/storage/logs/error-$(date +%Y-%m-%d).log
- [ ] Review security logs for failed login attempts

## Weekly Tasks
- [ ] Review log file sizes to ensure rotation is working
- [ ] Check for any performance issues in SQL logs
- [ ] Verify old logs are being cleaned up (30+ days)

## Quick Commands
Monitor real-time logs:
- tail -f ~/app/storage/logs/app-$(date +%Y-%m-%d).log
- tail -f ~/app/storage/logs/error-$(date +%Y-%m-%d).log
- tail -f ~/app/storage/logs/security-$(date +%Y-%m-%d).log

Search logs:
- grep "ERROR" ~/app/storage/logs/app-$(date +%Y-%m-%d).log
- grep "login" ~/app/storage/logs/security-$(date +%Y-%m-%d).log

## Important URLs
- Logging Status: https://gmpm.us/logging-status.php
- Portal: https://gmpm.us/

## Support
If you see any issues in the logs, check:
1. Error log for stack traces
2. Security log for access issues
3. App log for general application flow
