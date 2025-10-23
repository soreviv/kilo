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

// Otras páginas (se migraran a controladores en los siguientes pasos).
$router->get('/contacto', 'contacto.php');
$router->get('/aviso-privacidad', 'aviso-privacidad.php');
$router->get('/politica-cookies', 'politica-cookies.php');
$router->get('/terminos-condiciones', 'terminos-condiciones.php');

// Rutas POST para el manejo de formularios (se migraran a controladores).
$router->post('/contacto', 'contacto.php');