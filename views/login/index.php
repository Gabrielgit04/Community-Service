<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>
<?php
$fieldErrors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
unset($_SESSION['errors']);
$toastError = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);

$logged = false;
if (isset($_SESSION['Logueado']) && $_SESSION['Logueado'] == true) {
    $logged = true;
    unset($_SESSION['Logueado']);
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
    <title>Inicia sesion</title>
</head>

<body>
    <main class="auth-wrap">

        <section class="auth-brand">
            <div class="brand-inner">
                <img src="views/assets/imgs/logo-unidos.webp" alt="Logo" class="brand-logo">
                <h1>Consejo Comunal<br>Las Margaritas</h1>
                <p>Sistema de gestión administrativa del consejo comunal "Unidos en Victoria, Siempre Venceremos".</p>
                <span class="brand-tag">Venezuela · Estado Falcón</span>
            </div>
        </section>

        <section class="auth-panel">

            <form action="<?php echo base_url('/controller/authAdmin/authController.php') ?>" autocomplete="off" method="post" class="auth-card">

                <?php echo csrf_field(); ?>

                <header class="auth-head">
                    <h2>Inicia sesión</h2>
                    <p>Accede a tu cuenta para continuar</p>
                </header>

                <div class="field">
                    <img src="views/assets/imgs/icons/fields/at.svg" alt="icon" class="icon">
                    <input type="email" name="email_log" id="user" class="entry" placeholder="Correo" minlength="3" maxlength="30" title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
                </div>

                <div class="field">
                    <img src="views/assets/imgs/icons/fields/icons8-lock-48.png" alt="icon" class="icon">
                    <input type="password" id="password" class="entry_pass" name="passw_log" placeholder="Contraseña | Max 15 caracteres" minlength="8" maxlength="15" title="La contraseña debe contener: Al menos un letra minuscula, al menos un numero, al menos un caracter especial y de 8 a 15 caracteres." required>
                </div>

                <div class="auth-links">
                    <a href="views/auth-identification/index.php">¿Olvidó su contraseña?</a>
                </div>

                <?php if (!empty($fieldErrors)): foreach ($fieldErrors as $field => $msg): ?>
                    <script>FieldErrors.show(<?php echo json_encode((string)$field); ?>, <?php echo json_encode((string)$msg); ?>);</script>
                <?php endforeach; endif ?>
                <?php if ($logged): ?>
                    <script>
                        Toast.success('Has iniciado sesión correctamente.');
                        setTimeout(() => { window.location.href = "<?php echo base_url('/views/main-menu/index.php'); ?>"; }, 1800);
                    </script>
                <?php endif ?>
                <?php if (!empty($toastError)): ?>
                    <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
                <?php endif ?>

                <button class="btn btn-primary btn-block" id="send">Ingresar</button>

                <p class="auth-meta">
                    <a href="views/register/index.php">¿No tienes cuenta? Regístrate.</a>
                </p>

            </form>

        </section>

    </main>

</body>

</html>
