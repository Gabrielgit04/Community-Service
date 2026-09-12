<?php
$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';
require_once APP_PATH_CONTROLLER . '/dataFetchDb/fetchUsers.php';

if (!isset($_SESSION['nombre'])) {
    redirect('/views/login/index.php');
}
$_SESSION["id_user"] = $_SESSION['ci'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/style-menu.css">
    <script src="views/assets/public/transition.js"></script>
    <title>Menu</title>
<body>

    <main class="main-box">
        <nav class="box-sidebar">

            <figure class="figOne">
                <a href="views/main-menu/index.php" aria-disabled="true">
                    <img src="views/assets/imgs/icons/nav/home.svg" alt="Inicio">
                    <h4>HOME</h4>
                </a>
            </figure>
            <figure class="figTwo">
                <a href="views/register-civil/home-register.php">
                    <img src="views/assets/imgs/icons/nav/user-plus.svg" alt="Opción 2">
                    <h4>Registro Civil</h4>
                </a>
            </figure>

            <figure class="figThree">
                <a href="views/index.php">
                    <img src="views/assets/imgs/icons/nav/clipboard-plus.svg" alt="Opción 3">
                    <h4>Gestión de<br>Proyectos</h4>
                </a>
            </figure>

            <figure class="figFourt">
                <a href="views/contact/index.php">
                    <img src="views/assets/imgs/icons/nav/address-book.svg" alt="Opción 4">
                    <h4>Contacto</h4>
                </a>
            </figure>

            <figure class="figFive">
                <a href="controller/close-session/logOutController.php">
                    <img src="views/assets/imgs/icons/nav/logout-2.svg" alt="Opción 5">
                    <h4>Salir</h4>
                </a>
            </figure>
        </nav>

        <section class="sect-hd-dash">

            <section class="box-dashboard">
                <header class="title">
                    <div class="avatar">
                        <img src="views/assets/imgs/man-1835_256.gif" alt="man-setup">
                    </div>
                    <div class="welcome-hd">
                        <h1>Bienvenido, <?php echo $_SESSION['nombre'] ?></h1>
                        <h5>Te damos la bienvenida <?php echo $_SESSION['nombre'] ?>, aprovecha este sistema para agilizar tus procesos administrativos.</h5>
                    </div>
                </header>

                <div class="list-option">
                    <article class="info_admin">
                        <figure>
                            <img src="views/assets/imgs/logo-unidos.webp" alt="bandera de venezuela" class="img-bandera">
                            <article><p>Venezuela - Estado Falcón</p></article>
                        </figure>
                        <ul>
                            <li><strong>Cedula:</strong> <?php echo $_SESSION['ci'] ?></li>
                            <li><strong>Comunidad:</strong> Consejo Comunal Las Margaritas, Unidos En Victoria Siempre Venceremos</li>
                            <li><strong>Correo:</strong> <?php echo $_SESSION['correo'] ?></li>
                            <li><strong>Rol:</strong> Administrador</li>
                        </ul>
                        <article class="link-pass"><a href="views/change-password/index.php">Cambiar contraseña</a></article>
                    </article>
                    <figure class="carrusel"></figure>
                </div>

                <div class="dashboard">
                    <article class='count-admins-box'>
                        <div class="stat-head"><span class="stat-dot"></span><h4>Administradores</h4></div>
                        <h2 class="count"><?php echo $_SESSION['userRegisterTotal']; ?></h2>
                        <small><b>Última conexión:</b><br><?php echo htmlspecialchars($_SESSION['lastVisited'] ?? '—') ?></small>
                    </article>
                    <article class='count-civil-box'>
                        <div class="stat-head"><span class="stat-dot green"></span><h4>Civiles registrados</h4></div>
                        <h2 class="count"><?php echo $_SESSION['peopleRegisterTotal'] ?></h2>
                        <small><b>Registro Civil</b></small>
                    </article>
                    <article class='count-projects-box'>
                        <div class="stat-head"><span class="stat-dot yellow"></span><h4>Proyectos registrados</h4></div>
                        <section class="box-projects">
                            <div class="proj-total">
                                <h5>Total proyectos:</h5>
                                <h3 style="font-size: 1.6em;"><?php echo $_SESSION['projectsRegisterTotal'] ?></h3>
                            </div>
                            <div class="proj-states">
                                <b>Estado:</b>
                                <ul>
                                    <li>Planificando<h3><?php echo $_SESSION['projects_state']['Planificando']?></h3></li>
                                    <li>En Proceso<h3><?php echo $_SESSION['projects_state']['En_Proceso'] ?></h3></li>
                                    <li>Incompleto<h3><?php echo $_SESSION['projects_state']['Incompleto'] ?></h3></li>
                                    <li>Finalizado<h3><?php echo $_SESSION['projects_state']['Finalizado']?></h3></li>
                                </ul>
                            </div>
                        </section>
                    </article>
                </div>

            </section>
        </section>
    </main>

</body>
</html>
