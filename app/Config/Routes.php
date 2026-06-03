<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Tvoje stávající
$routes->get('/', 'Con1::index');
$routes->get('etapa/detail/(:num)', 'Con2::detail/$1');

// --- 3. STRÁNKA (JEDEN CHYTRÝ FORMULÁŘ) ---
// --- SPRÁVA ETAP ---
// Přidání
$routes->get('sprava/add', 'ConForm::add');
$routes->post('sprava/create', 'ConForm::create');

// Úprava
$routes->get('sprava/edit/(:num)', 'ConForm::edit/$1');
$routes->post('sprava/update/(:num)', 'ConForm::update/$1');

// Smazání
$routes->get('sprava/delete/(:num)', 'ConForm::delete/$1');