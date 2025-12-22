<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('login', 'AuthController::loginForm');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Rutas que requieren autenticación
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('/', 'Home::index');
    //$routes->resource('clientes');
    //$routes->resource('contactos');
    $routes->resource('tiposticket');
    $routes->resource('estadosticket');
    $routes->resource('prioridadesticket');
    //$routes->resource('tickets');
    //$routes->resource('ticketmovimientos');
});
