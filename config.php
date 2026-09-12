<?php

// Rutas absolutas del sistema (sin depender de rutas relativas ni del CWD)
define('APP_ROOT', dirname(__FILE__));
define('BASE_URL', 'http://localhost/communitary-service');

define('APP_PATH_VIEWS', APP_ROOT . '/views');
define('APP_PATH_CONTROLLER', APP_ROOT . '/controller');
define('APP_PATH_MODELS', APP_ROOT . '/models');

// Construye una URL absoluta dentro de la aplicación.
// Ej: base_url('/views/login/index.php') => http://localhost/communitary-service/views/login/index.php
function base_url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

// Redirige a una ruta de la aplicación y termina la ejecución.
function redirect(string $path = ''): void
{
    header('Location: ' . base_url($path));
    exit;
}

// Conexión a la base de datos siempre disponible
require_once APP_ROOT . '/models/conexion.php';

// Arranque de sesión centralizado e idempotente (único lugar donde se inicia).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Bloquea el acceso a páginas internas si no hay un administrador autenticado.
function requireLogin(string $path = '/views/login/index.php'): void
{
    if (empty($_SESSION['nombre'])) {
        redirect($path);
    }
}

// Devuelve (y crea si hace falta) el token CSRF de la sesión.
function csrf_token(): string
{
    if (empty($_SESSION['_token'])) {
        $_SESSION['_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_token'];
}

// Campo oculto listo para insertar en los <form>.
function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

function csrf_verify(): bool
{
    $token = $_POST['_token'] ?? '';
    return is_string($token) && $token !== '' && hash_equals($_SESSION['_token'] ?? '', $token);
}

// Valida el token CSRF en peticiones POST que cambian estado.
function csrf_check(): void
{
    if (!csrf_verify()) {
        http_response_code(419);
        exit('Token de sesión inválido. Recarga la página e inténtalo de nuevo.');
    }
}