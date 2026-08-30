<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
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
    <script src="views/assets/public/transition.js"></script>
    <title>Preguntas de seguridad</title>
</head>
<body>
    <section class="question-box">


        
        <form action="<?php echo base_url('/controller/authAdmin/validateSession.php') ?>" method="post" autocomplete="off" class="recover-form">
            <header class="header-box"><h2>Protege tu cuenta</h2></header>

            <select id="security-question" name="security-question" required>
                <option value="" selected disabled>-- Selecciona una pregunta --</option>
                <option value="¿Cuáll es tu cargo en el consejo comunal?">¿Cuál es tu cargo en el consejo comunal?</option>
                <option value="¿Cuál fue tu primer trabajo?">¿Cuál fue tu primer trabajo?</option>
                <option value="¿Cuál era el nombre de tu maestro favorito?">
                    ¿Cuál era el nombre de tu maestro favorito?</option>
                <option value="¿Nombre de tu Mamá?">¿Nombre de tu Mamá?</option>
                <option value="¿Nombre de tu Hijo/a?">¿Nombre de tu Hijo/a?</option>
            </select>


            <div class="input_area">
                <input type="text" name="quest1" id="user" class="entry" placeholder="Respuesta" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
                <div class="labelline"><span><img src="views/assets/imgs/icons/misc/clipboard.svg" alt="icon"
                            class="icon_user"></span></div>
            </div>

            <select id="security-question" name="security-question-2" required>
                <option value="" selected disabled>-- Selecciona una pregunta --</option>
                <option value="¿Cuáll es tu cargo en el consejo comunal?">¿Cuál es tu cargo en el consejo comunal?</option>
                <option value="¿Cuál fue tu primer trabajo?">¿Cuál fue tu primer trabajo?</option>
                <option value="¿Cuál era el nombre de tu maestro favorito?">
                    ¿Cuál era el nombre de tu maestro favorito?</option>
                <option value="¿Nombre de tu Mamá?">¿Nombre de tu Mamá?</option>
                <option value="¿Nombre de tu Hijo/a?">¿Nombre de tu Hijo/a?</option>
            </select>


            <div class="input_area">
                <input type="text" name="quest2" id="user" class="entry" placeholder="Respuesta" minlength="3" maxlength="30"  title="Se permiten letras, numeros y guines bajos, y la longitud debe ser de 3 a 30 caracteres" required>
                <div class="labelline"><span><img src="views/assets/imgs/icons/misc/clipboard.svg" alt="icon"
                            class="icon_user"></span></div>
            </div>



            <button class="btn-recover">Enviar</button>
        </form>

    </section>
</body>
</html>