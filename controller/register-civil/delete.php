<?php

require_once dirname(__DIR__, 2) . '/config.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Método de envío incorrecto.';
    redirect('/views/register-civil/read/index.php');
    exit();
}

csrf_check();

if (!isset($_POST['cedula']) || trim($_POST['cedula']) === '') {
    $_SESSION['error'] = 'No se pudo obtener la cédula.';
    redirect('/views/register-civil/read/index.php');
    exit();
}

$idUserEliminated = filter_var($_POST['cedula'], FILTER_SANITIZE_NUMBER_INT);

if ($idUserEliminated === '') {
    $_SESSION['error'] = 'No se pudo obtener la cédula.';
    redirect('/views/register-civil/read/index.php');
    exit();
}

$db = conexionDB();

try {
    $query = $db->prepare('DELETE FROM People_Data WHERE ID_CI = :idUser');
    $query->bindParam(':idUser', $idUserEliminated, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        $_SESSION['delete'] = true;
    } else {
        $_SESSION['error'] = 'No se encontró un ciudadano con esa cédula.';
    }
    redirect('/views/register-civil/read/index.php');
    exit();
} catch (PDOException $e) {
    error_log('Delete error: ' . $e->getMessage());
    $_SESSION['error'] = 'Error al eliminar el registro.';
    redirect('/views/register-civil/read/index.php');
    exit();
}