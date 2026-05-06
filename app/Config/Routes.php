<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Etapy::index');
$routes->get('etapa/detail/(:num)', 'Etapy::detail/$1');
