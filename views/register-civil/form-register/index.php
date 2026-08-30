<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
?>
<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    redirect('/views/login/index.php');
}
$fieldErrors = isset($_SESSION['errores']) ? $_SESSION['errores'] : [];
unset($_SESSION['errores']);
$toastError = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
$mensaje = '';
if (isset($_SESSION['mensaje']) && $_SESSION['mensaje'] == true) {
    $mensaje = "Registro exitoso";
    unset($_SESSION['mensaje']);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/register-civil-style.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/field-errors.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Formulario de Registro</title>
</head>

<body>
    <section class="form-all-cont">

        <div class="cont-header">
            <a href="views/register-civil/home-register.html"><img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="exit" class="exit"></a>
            <header>
                <h2>Registra a las personas de tu comunidad</h2>
            </header>
            <article>
                <p>
                    Esta sección está diseñada para que puedas registrar a los individuos que forman parte de tu comunidad.
                    El objetivo es recopilar información esencial que permita un mejor seguimiento y apoyo a cada miembro.
                </p>
                <p>
                    Completa el siguiente formulario con los datos personales de cada individuo.
                    Asegúrate de ingresar la información correctamente para mantener un registro preciso y actualizado.
                </p>
                <?php if (!empty($mensaje)): ?>
                    <script>Toast.success(<?php echo json_encode($mensaje); ?>);</script>
                <?php endif; ?>

                <?php if (!empty($fieldErrors)): foreach ($fieldErrors as $field => $msg): ?>
                    <script>
                        <?php if (is_string($field) && !is_numeric($field)): ?>
                            FieldErrors.show(<?php echo json_encode($field); ?>, <?php echo json_encode((string)$msg); ?>);
                        <?php else: ?>
                            Toast.error(<?php echo json_encode((string)$msg); ?>);
                        <?php endif; ?>
                    </script>
                <?php endforeach; endif; ?>

                <?php if (!empty($toastError)): ?>
                    <script>Toast.error(<?php echo json_encode($toastError); ?>);</script>
                <?php endif; ?>
            </article>
        </div>

        <div class="form-container">
            <h2 style="text-transform: uppercase;">Ingresa sus datos</h2>
            <form action="<?php echo base_url('/controller/register-civil/insert.php') ?>" method="post" autocomplete="off">
                <section>
                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/id.svg" alt="icon" class="icon">
                        <input type="text" name="cedula" class="entry" placeholder="Cédula" required pattern="^[0-9]{6,10}$" title="Ingrese 6 a 10 dígitos numéricos">
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/label.svg" alt="icon" class="icon">
                        <input type="text" name="nombre" class="entry" placeholder="Nombres" required pattern="^[A-Za-zñÑáéíóúÁÉÍÓÚ\s\-]{2,50}$" title="Solo letras, espacios y guiones (2-50 caracteres)" style="text-transform: capitalize;">
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/label.svg" alt="icon" class="icon">
                        <input type="text" name="apellido" class="entry" placeholder="Apellidos" required pattern="^[A-Za-zñÑáéíóúÁÉÍÓÚ\s\-]{2,50}$" title="Solo letras, espacios y guiones (2-50 caracteres)" style="text-transform: capitalize;">
                    </div>

                    <select name="sexo" required>
                        <option selected disabled>Selecciona tu género</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>

                    <div class="field">
                        <img src="views/assets/imgs/icons/nav/address-book.svg" alt="icon" class="icon">
                        <input type="tel" name="telefono" class="entry" placeholder="Teléfono" required pattern="^[0-9+\-\s]{7,20}$" title="Número de teléfono: 7 a 20 dígitos, puede incluir +, espacios o guiones">
                    </div>

                    <select id="comite" name="comite" required>
                        <optgroup label="Seleccione un comité">
                            <option value="alimentacion">Alimentación</option>
                            <option value="economia_comunal">Economía comunal</option>
                            <option value="empleo">Empleo</option>
                            <option value="deporte_juventud">Deporte y juventud</option>
                            <option value="energia_gas">Mesa técnica de energía y gas</option>
                            <option value="agua">Mesa técnica de agua</option>
                            <option value="educacion_cultura">Educación, cultura y formación ciudadana</option>
                            <option value="habitat_tierra">Hábitat, vivienda y tierra</option>
                            <option value="medios_alternativos">Medios alternativos</option>
                            <option value="seguridad_defensa">Seguridad y defensa</option>
                            <option value="proteccion_nna">Protección de niños, niñas y adolescentes</option>
                            <option value="salud">Salud</option>
                            <option value="planificacion">Planificación</option>
                            <option value="parlamento">Parlamento</option>
                        </optgroup>
                    </select>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/home-question.svg" alt="icon" class="icon">
                        <input type="text" name="direccion" class="entry" placeholder="Dirección" required pattern="^.{5,150}$" title="Ingresa al menos 5 caracteres para la dirección">
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/calendar-week.svg" alt="icon" class="icon">
                        <input type="date" name="fecha_nacimiento" class="entry" required>
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/at.svg" alt="icon" class="icon">
                        <input type="email" name="correo" class="entry" style="text-transform:none;" placeholder="Correo electrónico" required pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" title="Ingresa un correo válido">
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/key.svg" alt="icon" class="icon">
                        <input type="text" name="codigo_carnet" class="entry" placeholder="Código del carnet de la patria" required pattern="^[A-Za-z0-9\-]{3,30}$" title="Código alfanumérico (3-30 caracteres)">
                    </div>

                    <div class="field">
                        <img src="views/assets/imgs/icons/fields/hash.svg" alt="icon" class="icon">
                        <input type="text" name="serial_carnet" class="entry" placeholder="Serial del carnet de la patria" required pattern="^[A-Za-z0-9\-]{3,30}$" title="Serial alfanumérico (3-30 caracteres)">
                    </div>

                    <select name="centro_votacion" required>
                        <option selected disabled>Selecciona el centro de votación</option>
                        <option value="Liceo Bolivariano Maestro Gallegos">Liceo Bolivariano Maestro Gallegos</option>
                        <option value="Caipa">Caipa</option>
                        <option value="Alicia Tremont de Medina">Alicia Tremont de Medina</option>
                        <option value="Inces">Inces</option>
                    </select>

                    <select name="tipo_voto" required>
                        <option selected disabled>Selecciona el tipo de voto</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Asistido">Asistido</option>
                    </select>
                </section>

                <button type="submit" class="submit-btn">Registrar</button>
            </form>
        </div>
    </section>
</body>
</html>
