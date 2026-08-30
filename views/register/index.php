<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

session_start();
if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
$toastError = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
$mensaje = '';
if (isset($_SESSION['success']) && $_SESSION['success'] == true) {
    $mensaje = 'Usuario registrado exitosamente.';
    unset($_SESSION['success']);
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/styles.css">
    <link rel="icon" type="image/x-icon" href="views/assets/imgs/logo-unidos.ico">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/field-errors.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Registrate</title>
</head>
<body>
    <section class="container">

        <section class="back">
            <figure><img src="views/assets/imgs/logo-unidos.webp" alt="logo-unidos" class="unidos_logo"></figure>
        </section>

        <section class="informacion">

            <form action="<?php echo base_url('/controller/authAdmin/registerController.php') ?>" autocomplete="off" method="post">

                <header class="hd-box-title"><h2>Crea tu usuario</h2></header>

                <section class="inputs">

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/id.svg" alt="icon" class="icon">
                        <input type="text" name="id" class="entry" placeholder="Cedula" minlength="3" maxlength="15" required>
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/label.svg" alt="icon" class="icon">
                        <input type="text" name="nombre" class="entry" placeholder="Nombre completo" style="text-transform: capitalize;" required>
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/at.svg" alt="icon" class="icon">
                        <input type="email" class="entry_pass" name="email" placeholder="Correo" minlength="8" maxlength="30" required>
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/icons8-lock-48.png" alt="icon" class="icon">
                        <input type="password" class="entry_pass" name="passw" placeholder="Contraseña | Max 15 caracteres" title="Ingrese minimo 8 caracteres y maximo 15" minlength="8" maxlength="15" required>
                    </div>

                    <select name="rol" id="rol">
                        <option selected disabled>Selecciona un rol</option>
                        <option value="Administrador">Administrador</option>
                    </select>

                    <?php if (!empty($errors)): foreach ($errors as $field => $msg): ?>
                        <script>FieldErrors.show(<?php echo json_encode((string)$field); ?>, <?php echo json_encode((string)$msg); ?>);</script>
                    <?php endforeach; endif ?>
                    <?php if (!empty($toastError)): ?>
                        <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
                    <?php endif ?>
                    <?php if (!empty($mensaje)): ?>
                        <script>
                            Toast.success(<?php echo json_encode($mensaje); ?>);
                            setTimeout(() => {
                                window.location.href = "<?php echo base_url('/views/secure-questions/index.php'); ?>";
                            }, 2200);
                        </script>
                    <?php endif ?>

                    <button class="btn btn-primary btn-block">Registrar</button>
                </section>
            </form>
            <article class="registro-link">
                <a href="views/login/index.php">¿Ya tienes una cuenta? Inicia sesión.</a>
            </article>

        </section>

    </section>
</body>
</html>
