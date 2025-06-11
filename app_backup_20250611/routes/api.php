<?php
/**
 * API Routes
 */

return function ($router) {
    // Phone Note API
    $router->post('/api/phone-notes/submit', 'PhoneNoteController@submit');
    $router->post('/api/phone-notes/status/{id}', 'PhoneNoteController@updateStatus');
    
    // IT Support API
    $router->post('/api/it-support/submit', 'ITSupportController@handleSubmission');
    $router->get('/api/it-support/ticket/{id}', 'ITSupportController@getTicket');
    $router->post('/api/it-support/comment', 'ITSupportController@addComment');
    
    // Public API endpoints
    $router->get('/api/public/status', 'ApiController@status');
    $router->get('/api/public/summary', 'ApiController@summary');
};
