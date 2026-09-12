<?php 

$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

requireLogin();

$proyectos = [];
$dbError = null;

try {
    $pdo = conexionDB();
    $stmt = $pdo->prepare("SELECT id_project, name_project FROM projects");
    $stmt->execute();
    $proyectos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $dbError = "Error al cargar proyectos: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/sistem project.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Listado de Proyectos</title>
</head>
<body>

    <header class="proj-header">
        <a href="views/main-menu/index.php" class="volver" title="Volver a HOME">
            <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="Volver">
        </a>
        <div class="header-title">
            <img src="views/assets/imgs/icons8-project-96.png" alt="Proyecto" class="img_proyecto">
            <div>
                <h1 class="texto_titulo">Gestión de Proyectos</h1>
                <p>Administra los proyectos comunitarios del consejo comunal</p>
            </div>
        </div>
    </header>

    <main class="proj-main">

        <div class="proj-toolbar">
            <a class="btn btn-primary" href="views/Create Project/index.php">Crear Nuevo Proyecto</a>
            <a class="btn btn-outline" href="views/index.php">⟳ Recargar Lista</a>
        </div>

        <div class="proj-table-wrap">
            <table>
                <thead>
                    <tr class="columnas1">
                        <th>Nombre del Proyecto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody class="columnas2">
                    <?php if (!empty($proyectos)): ?>
                        <?php foreach ($proyectos as $proyecto): ?>
                            <tr>
                                <td class="columna_name"><?php echo htmlspecialchars($proyecto['name_project']); ?></td>
                                <td class="columna_boton">
                                    <a href="views/Visualize Project/index.php?id=<?php echo htmlspecialchars($proyecto['id_project']); ?>" class="btn-accion btn-ver">Ver</a>
                                    <a href="views/Modify Project/index.php?id=<?php echo htmlspecialchars($proyecto['id_project']); ?>" class="btn-accion btn-editar">Editar</a>
                                    <form action="<?php echo base_url('/controller/system-project/eliminate project.php') ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="id_project" value="<?php echo htmlspecialchars($proyecto['id_project']); ?>">
                                        <button type="submit"
                                            onclick="return confirm('¿Está seguro de que desea eliminar el proyecto \'<?php echo htmlspecialchars($proyecto['name_project']); ?>\'? Esto es irreversible.');" class="btn-accion btn-eliminar">Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2">No hay Proyectos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

    <?php if (!empty($dbError)): ?>
        <script>Toast.error(<?php echo json_encode($dbError); ?>);</script>
    <?php endif; ?>

</body>
</html>
