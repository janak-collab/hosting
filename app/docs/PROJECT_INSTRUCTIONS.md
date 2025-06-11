# GMPM Application - Project Instructions

## Architecture Overview

This application uses a modern MVC architecture with the following structure:
/home/gmpmus/
├── app/                       # Application code (outside web root for security)
│   ├── src/
│   │   ├── Controllers/       # Route controllers
│   │   ├── Middleware/        # Request middleware
│   │   └── bootstrap.php      # Application initialization
│   ├── templates/             # View templates
│   ├── Models/               # Data models
│   ├── config/               # Configuration files
│   ├── routes/               # Route definitions
│   │   └── web.php          # Web routes (using FastRoute)
│   ├── vendor/              # Composer dependencies
│   └── .env                 # Environment variables (never commit!)
└── public_html/             # Web root (public access)
├── index.php            # Single entry point
├── assets/              # Static assets (CSS, JS, images)
└── .htaccess           # Apache/LiteSpeed rewrite rules
## Routing System

This application uses **FastRoute** for routing. All routes are defined in `app/routes/web.php`.

### Adding a New Route

1. Open `app/routes/web.php`
2. Add your route using FastRoute syntax:

```php
// GET route
$router->get('/your-path', 'YourController@method');

// POST route  
$router->post('/your-path', 'YourController@method');

// Route with parameters
$router->get('/user/{id:\d+}', 'UserController@show');
