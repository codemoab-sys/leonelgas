<?php

require_once __DIR__ . '/../config/helpers.php';

class AuthController {

    public function login(): void {
        session_start();
        if (isset($_SESSION['auth'])) {
            header('Location: ' . baseUrl() . '/index.php');
            exit;
        }
        require_once __DIR__ . '/../views/login.php';
    }

    public function entrar(): void {
        session_start();
        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($usuario === 'prueba' && $password === 'prueba123') {
            $_SESSION['auth'] = [
                'usuario' => 'prueba',
                'nombre' => 'Usuario de Prueba',
            ];
            jsonResponse(['success' => true]);
        } else {
            jsonError('Usuario o contraseña incorrectos');
        }
    }

    public function salir(): void {
        session_start();
        session_destroy();
        header('Location: ' . baseUrl() . '/index.php?action=auth.login');
        exit;
    }
}
