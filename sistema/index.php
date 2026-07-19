<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/config/helpers.php';
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET['action'] ?? 'clientes.index';

$publicActions = ['auth.login', 'auth.entrar'];

if (!isset($_SESSION['auth']) && !in_array($action, $publicActions)) {
    header('Location: ' . baseUrl() . '/?action=auth.login');
    exit;
}

try {
    $parts = explode('.', $action);
    $controllerName = ucfirst($parts[0]) . 'Controller';
    $methodName = $parts[1] ?? 'index';

    $controllerFile = __DIR__ . "/controllers/{$controllerName}.php";

    if (!file_exists($controllerFile)) {
        http_response_code(404);
        die('Controlador no encontrado');
    }

    require_once $controllerFile;

    if (!class_exists($controllerName)) {
        http_response_code(404);
        die('Clase no encontrada');
    }

    $controller = new $controllerName();

    if (!method_exists($controller, $methodName)) {
        http_response_code(404);
        die('Método no encontrado');
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

    if ($id !== null) {
        $controller->$methodName($id);
    } else {
        $controller->$methodName();
    }
} catch (PDOException $e) {
    http_response_code(500);
    die('Error de base de datos. Verifique la conexión.');
} catch (Throwable $e) {
    http_response_code(500);
    die('Error interno del servidor');
}
