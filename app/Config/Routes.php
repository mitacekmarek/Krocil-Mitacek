<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Tvoje stávající
$routes->get('/', 'Con1::index');
$routes->get('etapa/detail/(:num)', 'Con2::detail/$1');

// --- 3. STRÁNKA (JEDEN CHYTRÝ FORMULÁŘ) ---
$routes->get('sprava', 'ConForm::edit'); // Otevře čistý formulář (Nová etapa)
$routes->get('sprava/edit/(:num)', 'ConForm::edit/$1'); // Otevře vyplněný formulář (Úprava)
$routes->post('sprava/save', 'ConForm::save'); // Zpracování uložení
$routes->get('sprava/delete/(:num)', 'ConForm::delete/$1'); // Zpracování smazání