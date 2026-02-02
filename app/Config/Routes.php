<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rutas de autenticación
$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Rutas que requieren autenticación
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->post('push/subscribe', 'PushController::subscribe');
    $routes->get('push/test', 'PushController::test');
    
    $routes->get('dashboard', 'Home::index');
    $routes->get('/', 'Home::index');
    $routes->resource('usuarios', ['controller' => 'Usuarios']);
    $routes->resource('contactos');
    $routes->resource('tiposticket', ['controller' => 'TiposTicket']);
    $routes->resource('estadosticket', ['controller' => 'EstadosTicket']);
    $routes->resource('prioridadesticket', ['controller' => 'PrioridadesTicket']);
    //$routes->resource('ticketmovimientos', ['controller' => 'TicketMovimientos']);

    // Rutas personalizadas para Clientes
    $routes->get('clientes', 'Clientes::index');
    $routes->get('clientes/crear', 'Clientes::create');
    $routes->post('clientes/insertar', 'Clientes::store');
    $routes->get('clientes/editar/(:num)', 'Clientes::edit/$1');
    $routes->post('clientes/actualizar/(:num)', 'Clientes::update/$1');
    $routes->get('clientes/eliminar/(:num)', 'Clientes::delete/$1');

    // Rutas personalizadas para Tickets
    $routes->get('tickets', 'Tickets::index');
    $routes->get('tickets/cerrados', 'Tickets::closed');
    $routes->get('tickets/programados', 'Tickets::scheduled');
    $routes->get('tickets/crear', 'Tickets::create');
    $routes->post('tickets/insertar', 'Tickets::store');
    $routes->get('tickets/edit/(:num)', 'Tickets::edit/$1');
    $routes->post('tickets/actualizar/(:num)', 'Tickets::update/$1');
    $routes->get('tickets/eliminar/(:num)', 'Tickets::delete/$1');
    $routes->get('tickets/detail/(:num)', 'Tickets::detail/$1');
    $routes->get('tickets/history/(:num)', 'Tickets::history/$1');
    $routes->get('tickets/cerrar/(:num)', 'Tickets::close/$1');

    // Rutas personalizadas para Tickets Recurrentes
    $routes->get('recurrentes', 'TicketsRecurrentes::index');
    $routes->get('recurrentes/crear', 'TicketsRecurrentes::create');
    $routes->post('recurrentes/insertar', 'TicketsRecurrentes::store');
    $routes->get('recurrentes/editar/(:num)', 'TicketsRecurrentes::edit/$1');
    $routes->post('recurrentes/actualizar/(:num)', 'TicketsRecurrentes::update/$1');
    $routes->get('recurrentes/eliminar/(:num)', 'TicketsRecurrentes::delete/$1');


    // Ruta personalizada para insertar movimientos de ticket
    $routes->post('ticketmovimientos/insertar', 'TicketMovimientos::create');

    // Rutas para Notificaciones
    $routes->get('notifications', 'Notifications::index');
    $routes->get('notifications/list', 'Notifications::list');
    $routes->get('notifications/markRead/(:num)', 'Notifications::markRead/$1');
    $routes->get('notifications/markAllRead', 'Notifications::markAllRead');
    
    // Ruta de Perfil
    $routes->get('profile', 'Profile::index');
    $routes->post('profile/update', 'Profile::update');
    $routes->post('profile/updatePassword', 'Profile::updatePassword');
    $routes->post('profile/updateAvatar', 'Profile::updateAvatar');
    $routes->post('profile/updateEscenarios', 'Profile::updateEscenarios');
});

// API Routes
$routes->group('api', function($routes) {
    $routes->get('clientes/list', 'Api::listClients');
    $routes->post('clientes/create', 'Api::createClient');
    
    $routes->get('tickets/list', 'Api::listTickets');
    $routes->post('tickets/create', 'Api::createTicket');
    $routes->get('tickets/detail/(:num)', 'Api::getTicket/$1');
    $routes->post('tickets/movements/add', 'Api::addMovement');
    
    $routes->get('metadata', 'Api::metadata');
});
