<?php
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/change-password/index.php');
    exit();
}

csrf_check();

$newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
$repeatPassword = isset($_POST['rep_password']) ? trim($_POST['rep_password']) : '';

$errors = [];
if ($newPassword === '') {
    $errors['new_password'] = 'Ingrese la nueva contraseña.';
}
if ($repeatPassword === '') {
    $errors['rep_password'] = 'Repita la nueva contraseña.';
}

if ($newPassword !== $repeatPassword) {
    $errors['rep_password'] = 'Las contraseñas no coinciden.';
}

if (strlen($newPassword) < 8) {
    $errors['new_password'] = 'La contraseña debe tener al menos 8 caracteres.';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('/views/change-password/index.php');
    exit();
}

$userID = isset($_SESSION['id_user']) ? $_SESSION['id_user'] : null;
if (!$userID) {
    $_SESSION['error'] = 'Usuario no identificado. Inicie el proceso de recuperación otra vez.';
    redirect('/views/auth-identification/index.php');
    exit();
}



try {
    $dbConnect = conexionDB();
    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);

    $sql = $dbConnect->prepare('UPDATE users_admins SET contrasena = :hashNewPassword WHERE ci = :id');
    $sql->bindParam(':hashNewPassword', $newPasswordHash);
    $sql->bindParam(':id', $userID, PDO::PARAM_INT);
    $sql->execute();

    // limpiar sesión de recuperación
    unset($_SESSION['id_user']);
    $_SESSION['mensaje'] = 'Contraseña actualizada con éxito.';

    redirect('/views/change-password/successfull.php');
    exit();
} catch (PDOException $e) {
    error_log('NewPassword error: ' . $e->getMessage());
    $_SESSION['error'] = 'No se pudo actualizar la contraseña. Intente más tarde.';
    redirect('/views/change-password/index.php');
    exit();
}

?>