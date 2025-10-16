<?php

// Here we define all the application routes.
// The router instance is passed from index.php.

// Home Page
$router->get('/', 'home.php');

// Other pages (we will create these views in the next steps)
$router->get('/servicios', 'servicios.php');
$router->get('/agendar-cita', 'agendar-cita.php');
$router->get('/contacto', 'contacto.php');
$router->get('/aviso-privacidad', 'aviso-privacidad.php');
$router->get('/politica-cookies', 'politica-cookies.php');
$router->get('/terminos-condiciones', 'terminos-condiciones.php');

// Rutas POST para el manejo de formularios
$router->post('/agendar-cita', 'agendar-cita.php');
$router->post('/contacto', 'contacto.php');