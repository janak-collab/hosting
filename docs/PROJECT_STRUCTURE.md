# GMPM Project Structure & Architecture

## Overview
This is an MVC (Model-View-Controller) PHP application with proper separation of concerns. **NEVER modify index.php to include HTML directly or use basic includes.**

## Directory Structure
```
/home/gmpmus/
├── public_html/          # Web root (public access)
│   ├── index.php        # Main router - DO NOT add HTML here!
│   ├── assets/          # CSS, JS, images
│   ├── api/             # API endpoints
│   └── errors/          # Error pages
├── app/                 # Application files (outside web root)
│   ├── src/
│   │   ├── Controllers/ # MVC Controllers
│   │   ├── Models/      # Database models
│   │   ├── Services/    # Business logic
│   │   └── Database/    # DB connection
│   ├── templates/       # View templates
│   │   └── views/       # HTML templates
│   ├── public-endpoints/# Public route handlers
│   ├── vendor/          # Composer packages
│   └── .env            # Environment config
└── storage/            # File storage
```

## Architecture Rules

### 1. Routing
- **index.php** is ONLY a router that includes endpoint files
- Routes map to files in `app/public-endpoints/`
- Example: `/phone-note` → `app/public-endpoints/phone-note.php`

### 2. MVC Pattern
- **Controllers** (`app/src/Controllers/`) handle business logic
- **Models** (`app/src/Models/`) handle database operations
- **Views** (`app/templates/views/`) contain HTML templates
- Controllers load views using `require_once`

### 3. Existing Controllers
- `PhoneNoteController` - Phone note forms
- `ITSupportController` - IT tickets & admin
- `AuthService` - Authentication

### 4. Database
- Uses PDO with singleton pattern
- Connection: `App\Database\Connection::getInstance()`
- Models extend base model functionality

### 5. Adding New Features
When adding features:
1. Create controller in `app/src/Controllers/`
2. Create model in `app/src/Models/`
3. Create view templates in `app/templates/views/`
4. Create endpoint file in `app/public-endpoints/`
5. Add route case to `index.php`

## Example: Adding a Feature

```php
// 1. Create endpoint: app/public-endpoints/new-feature.php
<?php
require_once APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/src/bootstrap.php';

use App\Controllers\NewFeatureController;

$controller = new NewFeatureController();
$controller->showForm();

// 2. Add to index.php routing:
case $requestUri === '/new-feature':
    require APP_PATH . '/public-endpoints/new-feature.php';
    break;
```

## Common Commands

Show directory structure:
```bash
ls -la ~/app/src/Controllers/
ls -la ~/app/public-endpoints/
ls -la ~/app/templates/views/
```

Check routing:
```bash
grep -n "case" ~/public_html/index.php
```

Find controllers:
```bash
find ~/app/src/Controllers -name "*.php"
```

## Important Files
- Main router: `~/public_html/index.php`
- Bootstrap: `~/app/src/bootstrap.php`
- Database config: `~/app/.env`
- Composer autoload: `~/app/vendor/autoload.php`
