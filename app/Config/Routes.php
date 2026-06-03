<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Tvoje stávající
$routes->get('/', 'Con1::index');
$routes->get('etapa/detail/(:num)', 'Con2::detail/$1');
$routes->group('sprava', function($routes) {
    $routes->get('/', 'ConForm::index');
    $routes->get('add', 'ConForm::add');
    $routes->post('create', 'ConForm::create');
    $routes->get('edit/(:num)', 'ConForm::edit/$1');
    $routes->post('update/(:num)', 'ConForm::update/$1');
    $routes->get('delete/(:num)', 'ConForm::delete/$1');
});
