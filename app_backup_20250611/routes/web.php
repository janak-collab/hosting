<?php
/**
 * Web Routes
 */

return function ($router) {
    // Portal/Home
    $router->get('/', 'PortalController@index');
    $router->get('/index.php', 'PortalController@index');

    // Phone Note routes
    $router->get('/phone-note', 'PhoneNoteController@showForm');
    $router->post('/phone-note/submit', 'PhoneNoteController@submit');

    // IT Support routes
    $router->get('/it-support', 'ITSupportController@showForm');
    $router->post('/it-support/submit', 'ITSupportController@handleSubmission');

    // Admin routes - Use ITSupportController instead of AdminController
    $router->get('/admin', 'ITSupportController@adminRedirect');
    $router->get('/admin/login', 'ITSupportController@handleAdminLogin');
    $router->post('/admin/login', 'ITSupportController@handleAdminLogin');
    $router->get('/admin/logout', 'ITSupportController@handleAdminLogout');

    // Admin - IT Tickets
    $router->get('/admin/tickets', 'ITSupportController@showAdminPanel');
    $router->post('/admin/tickets/update', 'ITSupportController@updateTicket');

    // Admin - Phone Notes
    $router->get('/admin/phone-notes', 'PhoneNoteController@listNotes');
    $router->get('/admin/phone-notes/view/{id}', 'PhoneNoteController@viewNote');
    $router->get('/admin/phone-notes/print/{id}', 'PhoneNoteController@printNote');

    // Secure Admin routes
    $router->get('/secure-admin', function() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: /admin/login');
            exit;
        }
        
        $indexFile = APP_PATH . '/secure-admin/index.php';
        if (file_exists($indexFile)) {
            require $indexFile;
        } else {
            header('Location: /secure-admin/ip-address-manager');
        }
    });
    
    $router->get('/secure-admin/ip-address-manager', function() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: /admin/login');
            exit;
        }
        
        $ipManagerFile = APP_PATH . '/secure-admin/ip-address-manager.php';
        if (file_exists($ipManagerFile)) {
            require $ipManagerFile;
        } else {
            http_response_code(404);
            echo "IP Manager not found at: " . $ipManagerFile;
        }
    });
    
    $router->post('/secure-admin/ip-address-manager', function() {
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: /admin/login');
            exit;
        }
        
        $ipManagerFile = APP_PATH . '/secure-admin/ip-address-manager.php';
        if (file_exists($ipManagerFile)) {
            require $ipManagerFile;
        } else {
            http_response_code(404);
            echo "IP Manager not found";
        }
    });

    // Status page
    $router->get('/status', 'PortalController@status');

    // View tickets (authenticated users)
    $router->get('/view-tickets', 'PortalController@viewTickets');
};
