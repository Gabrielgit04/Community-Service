<?php

require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/register/index.php');
    exit();
}

csrf_check();

$errors = [];

$raw = array_map(function($v){ return is_string($v) ? trim($v) : $v; }, $_POST);

$id = isset($raw['id']) ? preg_replace('/\D/', '', $raw['id']) : '';
$name = isset($raw['nombre']) ? ucwords(htmlspecialchars($raw['nombre'])) : '';
$correo = isset($raw['email']) ? filter_var($raw['email'], FILTER_SANITIZE_EMAIL) : '';
$passw = isset($raw['passw']) ? $raw['passw'] : '';
$rol = isset($raw['rol']) ? preg_replace('/[^A-Za-z0-9_\-]/', '', $raw['rol']) : '';

// Validaciones básicas
$errors = [];
if ($id === '' || !preg_match('/^[0-9]{6,9}$/', $id)) {
    $errors['id'] = 'Cédula inválida, debe tener entre 6 y 9 dígitos.';
}
if ($name === '' || !preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s\-]{2,80}$/', $name)) {
    $errors['nombre'] = 'Nombre inválido, solo letras y espacios.';
}
if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Correo inválido.';
}
if ($passw === '' || strlen($passw) < 8) {
    $errors['passw'] = 'La contraseña debe tener al menos 8 caracteres.';
}
if ($rol === '') {
    $errors['rol'] = 'Seleccione un rol.';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('/views/register/index.php');
    exit();
}

$password_hash = password_hash($passw, PASSWORD_BCRYPT);

$db = conexionDB();

try {
    $query = $db->prepare("INSERT INTO users_admins(ci,nombre_completo,correo,contrasena,rol) VALUES(:id,:name_user,:correo,:passw,:rol)");
    $query->bindParam(':id', $id);
    $query->bindParam(':name_user', $name);
    $query->bindParam(':correo', $correo);
    $query->bindParam(':passw', $password_hash);
    $query->bindParam(':rol', $rol);

    $query->execute();
    if ($query->rowCount() === 0) {
        $_SESSION['error'] = 'No se pudo registrar el usuario. Intente nuevamente.';
        redirect('/views/register/index.php');
        exit();
        }else{
            $_SESSION['success'] = true;
        }
    $_SESSION['idGlobal'] = $id;
    redirect('/views/register/index.php');
    exit();
} catch (PDOException $e) {
    error_log('Register error: ' . $e->getMessage());
    $_SESSION['error'] = 'No se pudo registrar el usuario.';
    redirect('/views/register/index.php');
    exit();
}




?>