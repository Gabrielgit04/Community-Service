<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>
<?php
session_start();
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
    <title>Preguntas de seguridad</title>
</head>
<body>
    <section class="question-box">

        <a href="views/login/index.php"><img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="exit" class="exit"></a>

        
        <form action="<?php echo base_url('/controller/recover/recoverController.php') ?>" method="post" autocomplete="off">
            <header class="header-box"><h2>Recupera tu cuenta</h2></header>

            <h5 class="text"><?php echo $_SESSION['q1'] ?></h5>


            <div class="input_area">
                <input type="text" name="answer" id="user" class="entry" placeholder="Respuesta" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
                <div class="labelline"><span><img src="views/assets/imgs/icons/misc/clipboard.svg" alt="icon"
                            class="icon_user"></span></div>
            </div>

            <h5 class="text"><?php echo $_SESSION['q2'] ?></h5>
            

            <div class="input_area">
                <input type="text" name="answer-2" id="user" class="entry" placeholder="Respuesta" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
                <div class="labelline"><span><img src="views/assets/imgs/icons/misc/clipboard.svg" alt="icon"
                            class="icon_user"></span></div>
            </div>


            <?php if (!empty($errors)): foreach ($errors as $field => $msg): ?>
            <script>FieldErrors.show(<?php echo json_encode((string)$field); ?>, <?php echo json_encode((string)$msg); ?>);</script>
        <?php endforeach; endif ?>
        <?php if (!empty($toastError)): ?>
            <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
        <?php endif ?>

            <button class="btn-secure">Enviar</button>
        </form>

    </section>
</body>
</html>