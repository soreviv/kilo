<?php
session_start();

// Si no hay una sesión de usuario activa, redirigir a la página de login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Opcional: Reforzar la seguridad regenerando el ID de sesión periódicamente
if (time() - $_SESSION['last_login'] > 900) { // 15 minutos de inactividad
    session_regenerate_id(true);
    $_SESSION['last_login'] = time();
}
?>
