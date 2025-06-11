// Phone Note Routes
$router->get('/phone-note', 'PhoneNoteController@showForm');
$router->post('/api/phone-notes/submit', 'PhoneNoteController@submit');
$router->get('/admin/phone-notes', 'PhoneNoteController@listNotes');
$router->get('/admin/phone-notes/view/{id}', 'PhoneNoteController@viewNote');
$router->get('/admin/phone-notes/print/{id}', 'PhoneNoteController@printNote');
$router->get('/admin/phone-notes/pdf/{id}', 'PhoneNoteController@generatePDF');
$router->post('/api/phone-notes/status/{id}', 'PhoneNoteController@updateStatus');
// User ticket viewing
$router->get('/view-tickets', 'PortalController@viewTickets');
