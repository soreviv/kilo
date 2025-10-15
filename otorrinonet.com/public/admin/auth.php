<?php
session_start();
require_once __DIR__ . '/../../vendor/autoload.php';

// Cargar variables de entorno
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die('No se pudo encontrar el archivo .env');
}

// Verificar que la solicitud sea POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

// Obtener y sanitizar datos del formulario
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    header('Location: login.php?error=Usuario y contraseña son requeridos');
    exit;
}

try {
    // Conectar a la base de datos
    $db = \App\Core\Database::getInstance()->getConnection();

    // Buscar al usuario en la base de datos
    $stmt = $db->prepare("SELECT id, username, password_hash, rol FROM users WHERE username = :username AND activo = TRUE");
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    // Verificar si el usuario existe y la contraseña es correcta
    if ($user && password_verify($password, $user['password_hash'])) {
        // La contraseña es correcta, iniciar sesión
        session_regenerate_id(true); // Previene la fijación de sesiones
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_rol'] = $user['rol'];
        $_SESSION['last_login'] = time();

        // Redirigir al dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        // Credenciales incorrectas
        header('Location: login.php?error=Usuario o contraseña incorrectos');
        exit;
    }

} catch (PDOException $e) {
    // En producción, esto debería registrarse en un log
    error_log("Error de autenticación: " . $e->getMessage());
    header('Location: login.php?error=Ocurrió un error en el servidor. Intente más tarde.');
    exit;
}
