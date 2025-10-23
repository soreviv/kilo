<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController {
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm() {
        $status = $_SESSION['status'] ?? null;
        unset($_SESSION['status']);

        $data = [
            'pageTitle' => 'Iniciar Sesión - Administración',
            'status' => $status
        ];

        echo $this->renderView('admin/login', $data);
    }

    /**
     * Procesa el formulario de inicio de sesión.
     */
    public function login() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel();
        $user = $userModel->findByUsername($username);

        // password_verify es seguro contra ataques de temporización.
        if ($user && password_verify($password, $user['password_hash'])) {
            // Regenerar el ID de sesión para prevenir ataques de fijación de sesión.
            session_regenerate_id(true);

            // Guardar datos del usuario en la sesión.
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'rol' => $user['rol']
            ];

            // Redirigir al dashboard.
            header('Location: /admin/dashboard');
            exit;
        }

        // Si las credenciales son incorrectas, redirigir de vuelta con un error.
        $_SESSION['status'] = ['type' => 'error', 'message' => 'Usuario o contraseña incorrectos.'];
        header('Location: /admin/login');
        exit;
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        // Destruir todos los datos de la sesión.
        $_SESSION = [];
        session_destroy();

        // Redirigir a la página de inicio de sesión.
        header('Location: /admin/login');
        exit;
    }
}