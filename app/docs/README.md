# GMPM Application Documentation

## Overview
Greater Maryland Pain Management internal portal system with phone notes, IT support ticketing, and administrative features.

## Architecture
- **Framework**: Custom MVC with FastRoute
- **PHP Version**: 8.2+
- **Database**: MySQL/MariaDB
- **Server**: LiteSpeed

## Directory Structure
/home/gmpmus/
├── app/                    # Application code
│   ├── src/               # Source code
│   │   ├── Controllers/   # Business logic
│   │   ├── Models/        # Database models
│   │   ├── Services/      # Shared services
│   │   └── Router.php     # FastRoute implementation
│   ├── templates/         # View templates
│   ├── config/            # Configuration files
│   └── vendor/            # Composer packages
├── public_html/           # Web root
│   ├── index.php         # Entry point
│   └── assets/           # CSS, JS, images
└── storage/              # Logs, uploads, cache
## Features
1. **Phone Note System** - Record patient phone messages
2. **IT Support Tickets** - Submit and track technical issues
3. **Admin Panel** - Manage tickets and users
4. **IP Management** - Control access by IP address
5. **User Management** - Manage system users

## Routes
- `/` - Main portal
- `/phone-note` - Phone note form
- `/it-support` - IT support form
- `/admin/login` - Admin login
- `/admin/tickets` - Ticket management
- `/admin/users` - User management
- `/secure-admin/ip-address-manager` - IP management

## Configuration
Environment variables are stored in `/app/.env`:
- Database credentials
- Email settings
- Session configuration

## Security
- HTTP Basic Authentication (managed via .htaccess)
- IP whitelisting for access control
- CSRF protection on all forms
- Session-based admin authentication

## Logging
Logs are stored in `/app/storage/logs/`:
- `app-YYYY-MM-DD.log` - General application logs
- `error-YYYY-MM-DD.log` - Error logs
- `security-YYYY-MM-DD.log` - Security events

## Maintenance Commands
Use the `gmpm` utility:
```bash
gmpm status    # Check system status
gmpm backup    # Backup database and files
gmpm sync      # Sync to GitHub
gmpm logs      # View recent error logs
gmpm routes    # List all routes
Deployment

Upload files to server
Set proper permissions (644 for files, 755 for directories)
Configure .env file
Run database migrations
Test all routes

Support
For issues, contact IT department or check error logs.
