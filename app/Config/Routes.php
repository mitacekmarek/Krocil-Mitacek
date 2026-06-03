<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', 'StageCont::index');
$routes->get('etapa/detail/(:num)', 'StageDetailCont::index/$1');

$routes->group('sprava', function($routes) {
    $routes->get('add', 'FormCont::add');
    $routes->post('create', 'FormCont::create');
    $routes->get('edit/(:num)', 'FormCont::edit/$1');
    $routes->post('update/(:num)', 'FormCont::update/$1');
    $routes->get('delete/(:num)', 'FormCont::delete/$1');
});