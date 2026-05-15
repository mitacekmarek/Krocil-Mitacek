<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Con1::index');
$routes->get('etapa/detail/(:num)', 'Con2::detail/$1');