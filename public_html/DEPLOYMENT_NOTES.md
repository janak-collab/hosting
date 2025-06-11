# GMPM Deployment Notes

## Directory Structure
- `/home/gmpmus/public_html/` - Public web root
- `/home/gmpmus/app/` - Application files (outside web root)
- `/home/gmpmus/storage/` - Storage files (outside web root)
- `/home/gmpmus/.htpasswds/` - Authentication files

## Key Files
- `public_html/index.php` - Main router/entry point
- `app/.env` - Environment configuration
- `public_html/.htaccess` - Security and routing rules

## Allowed IPs
- 98.233.204.84 - Catonsville Office
- 173.67.39.5 - Edgewater Office
- 68.134.39.4 - Elkridge Office
- 71.244.156.161 - Glen Burnie Office
- 68.134.31.125 - Home
- 24.245.103.202 - Leonardtown Office
- 72.81.228.74 - Odenton Office
- 73.39.186.209 - Prince Frederick Office
- 65.181.111.128 - Server (for internal requests)

## Authentication
- Basic HTTP authentication required for all pages except:
  - /assets/* (CSS, JS, images)
  - /api/public/* (if exists)

## Testing
- Visit https://gmpm.us/ from an allowed IP
- Use valid HTTP Basic Auth credentials
- Should redirect to /phone-note form

## Backup Created
- Location: ~/gmpm_backup_[timestamp].tar.gz
