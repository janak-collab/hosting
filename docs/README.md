# GMPM Production Setup

## Directory Structure
- `/home/gmpmus/public_html/` - Public web root
- `/home/gmpmus/app/` - Application files (protected)
- `/home/gmpmus/storage/` - Storage files (protected)

## Main Entry Points
- `index.php` - Main router entry point
- `portal.php` - Portal page (legacy)
- `status.php` - System status check

## Routes
- `/` - Main portal
- `/phone-note` - Phone note form
- `/it-support` - IT support ticket form
- `/view-tickets` - View user tickets
- `/admin/*` - Admin area

## Important Files
- `/app/.env` - Environment configuration
- `/public_html/.htaccess` - Security and routing rules

## Maintenance
- Error logs: `/home/gmpmus/public_html/error_log`
- All test files have been removed for security

Last updated: $(date)
