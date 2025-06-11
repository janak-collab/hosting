# GMPM Portal v2.0 - Deployment Information

## Deployment Date
- **Completed**: $(date +"%Y-%m-%d %H:%M UTC")
- **Version**: 2.0.0
- **Environment**: Production

## Architecture
- **Framework**: Custom MVC with FastRoute
- **PHP Version**: 8.2.28
- **Server**: LiteSpeed
- **Database**: MySQL (gmpmus_gmpm)

## Key Features
1. **Modern Routing**: FastRoute with clean URLs
2. **Security**: IP whitelist + HTTP Basic Auth
3. **Forms**: Phone Note & IT Support ticketing
4. **Admin Panel**: Full ticket management system
5. **API**: RESTful endpoints for status/health

## Directory Structure
/home/gmpmus/
├── app/                    # Application root
│   ├── src/               # Source code
│   │   ├── Controllers/   # MVC Controllers
│   │   ├── Models/        # Database models
│   │   └── Services/      # Business logic
│   ├── templates/         # View templates
│   ├── vendor/            # Composer packages
│   └── .env              # Configuration
├── public_html/           # Web root
│   ├── index.php         # Front controller
│   ├── .htaccess         # Routing rules
│   └── assets/           # CSS/JS/Images
└── storage/              # Application storage
## Maintenance Commands
- **Check Status**: `gmpm status`
- **View Routes**: `php ~/app/route-list.php`
- **System Info**: `php ~/app/dashboard-final.php`
- **Backup**: `gmpm backup`
- **Monitor**: `gmpm monitor`
- **Sync to Git**: `gmpm sync`

## Support Contacts
- **IT Email**: IT.request@greatermarylandpainmanagement.com
- **Server**: A2 Hosting (LiteSpeed)
