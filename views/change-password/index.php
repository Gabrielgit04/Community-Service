<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

session_start();
$idUsuario = isset($_SESSION["id_user"]) ? $_SESSION["id_user"] : '';
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
$toastError = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/style-recover.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/field-errors.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Cambia tu contraseña</title>
</head>
<body>
    <main class="main-box-change">


        <form action="controller/recover/newPasswordController.php" method="POST" class="form-change">
            <h1>Cambia tu contraseña</h1>
        <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($idUsuario); ?>">
        <div class="input_area">
            <input type="password" name="new_password" id="user" class="entry" placeholder="Nueva contraseña" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
            <div class="labelline"><span><img src="views/assets/imgs/icons/fields/icons8-lock-48.png" alt="icon"
                        class="icon_lock_new"></span></div>
        </div>

        <div class="input_area">
            <input type="password" name="rep_password" id="user" class="entry" placeholder="Repite la nueva contraseña" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
            <div class="labelline"><span><img src="views/assets/imgs/icons/fields/icons8-lock-48.png" alt="icon"
                        class="icon_lock_rep"></span></div>
        </div>
        <?php if (!empty($errors)): foreach ($errors as $field => $msg): ?>
            <script>FieldErrors.show(<?php echo json_encode((string)$field); ?>, <?php echo json_encode((string)$msg); ?>);</script>
        <?php endforeach; endif ?>
        <?php if (!empty($toastError)): ?>
            <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
        <?php endif ?>

        <button type="submit" class="send-new">Cambiar contraseña</button>
    </form>
</main>
</body>
</html>
