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
    
    

    // Status page
    $router->get('/status', 'PortalController@status');

    // View tickets (authenticated users)
    $router->get('/view-tickets', 'PortalController@viewTickets');
};
