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