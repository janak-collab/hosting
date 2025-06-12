<?php
namespace App;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;

class Router {
    private $dispatcher;

    public function __construct() {
        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) {
            // Dashboard
            $r->addRoute('GET', '/', 'DashboardController@index');
            $r->addRoute('GET', '/status', 'PortalController@status');
            $r->addRoute('GET', '/dashboard', 'DashboardController@index');

            // Phone Note Routes
            $r->addRoute('GET', '/phone-note', 'PhoneNoteController@showForm');
            $r->addRoute('POST', '/api/phone-notes/submit', 'PhoneNoteController@submit');
            $r->addRoute('GET', '/admin/phone-notes', 'PhoneNoteController@listNotes');
            $r->addRoute('GET', '/admin/phone-notes/view/{id:\d+}', 'PhoneNoteController@viewNote');
            $r->addRoute('GET', '/admin/phone-notes/print/{id:\d+}', 'PhoneNoteController@printNote');
            $r->addRoute('POST', '/api/phone-notes/status/{id:\d+}', 'PhoneNoteController@updateStatus');

            // IT Support Routes
            $r->addRoute('GET', '/it-support', 'ITSupportController@showForm');
            $r->addRoute('POST', '/api/it-support/submit', 'ITSupportController@handleSubmission');
            $r->addRoute('GET', '/admin/tickets', 'ITSupportController@showAdminPanel');
            $r->addRoute('POST', '/admin/tickets', 'ITSupportController@showAdminPanel');

            // Dictation Routes
            $r->addRoute('GET', '/dictation', 'DictationController@showForm');
            $r->addRoute('POST', '/api/dictation/start', 'DictationController@startSession');
            $r->addRoute('POST', '/api/dictation/pause', 'DictationController@pauseSession');
            $r->addRoute('POST', '/api/dictation/save', 'DictationController@saveSession');
            $r->addRoute('GET', '/api/dictation/get-templates', 'DictationController@getTemplates');
            $r->addRoute('GET', '/admin/dictations', 'DictationController@listDictations');

            // User Management Routes (Super Admin only)
            $r->addRoute('GET', '/admin/users', 'UserManagementController@index');
            $r->addRoute('GET', '/admin/users/create', 'UserManagementController@create');
            $r->addRoute('POST', '/admin/users/store', 'UserManagementController@store');
            $r->addRoute('GET', '/admin/users/edit/{id:\d+}', 'UserManagementController@edit');
            $r->addRoute('POST', '/admin/users/update/{id:\d+}', 'UserManagementController@update');
            $r->addRoute('POST', '/admin/users/delete/{id:\d+}', 'UserManagementController@delete');
            $r->addRoute('GET', '/admin/users/activity/{id:\d+}', 'UserManagementController@activity');

            // User Management API Routes
            $r->addRoute('GET', '/api/users/check-username', 'UserManagementController@checkUsername');
            $r->addRoute('POST', '/api/users/unlock/{id:\d+}', 'UserManagementController@unlock');
            $r->addRoute('POST', '/api/users/sync-htpasswd', 'UserManagementController@syncHtpasswd');

            // IP Address Manager Routes
            $r->addRoute('GET', '/ip-address-manager', 'IpManagerController@showForm');
            $r->addRoute('POST', '/ip-address-manager', 'IpManagerController@update');

            // View Tickets Route
            $r->addRoute('GET', '/view-tickets', 'ITSupportController@showAdminPanel');

            // Admin area general route
            $r->addRoute('GET', '/admin', 'ITSupportController@adminRedirect');

            // API status check
            $r->addRoute('GET', '/api/public/status', function() {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'ok', 'timestamp' => date('Y-m-d H:i:s')]);
            });
        });
    }

    public function dispatch($method, $uri) {
        $routeInfo = $this->dispatcher->dispatch($method, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);
                if (file_exists(APP_PATH . '/templates/views/errors/404.php')) {
                    require APP_PATH . '/templates/views/errors/404.php';
                } else {
                    echo "404 - Not Found";
                }
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                http_response_code(405);
                echo "405 - Method Not Allowed";
                break;

            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // Handle closures/callables
                if (is_callable($handler)) {
                    call_user_func($handler, $vars);
                } 
                // Handle controller@method format
                else if (strpos($handler, '@') !== false) {
                    list($controller, $method) = explode('@', $handler);
                    $controllerClass = "\\App\\Controllers\\{$controller}";

                    if (class_exists($controllerClass)) {
                        $instance = new $controllerClass();
                        if (method_exists($instance, $method)) {
                            call_user_func_array([$instance, $method], [$vars]);
                        } else {
                            http_response_code(500);
                            echo "Method {$method} not found in controller {$controller}";
                        }
                    } else {
                        http_response_code(500);
                        echo "Controller {$controller} not found";
                    }
                }
                break;
        }
    }

    public function getDispatcher() {
        return $this->dispatcher;
    }
}
