<?php

// El router ahora puede gestionar controladores.
// La sintaxis es 'ControladorController@metodo'.

// Ruta principal ahora gestionada por HomeController.
$router->get('/', 'HomeController@index');

// Página de servicios ahora gestionada por ServicesController.
$router->get('/servicios', 'ServicesController@index');

// Rutas para agendar cita.
$router->get('/agendar-cita', 'AppointmentController@create');
$router->post('/agendar-cita', 'AppointmentController@store');

// Rutas de páginas legales.
$router->get('/aviso-privacidad', 'LegalController@privacyPolicy');
$router->get('/politica-cookies', 'LegalController@cookiePolicy');
$router->get('/terminos-condiciones', 'LegalController@termsAndConditions');

// Rutas para el formulario de contacto.
$router->get('/contacto', 'ContactController@create');
$router->post('/contacto', 'ContactController@store');