<?php

$__root = dirname(__DIR__);
while (!is_file($__root . '/config.php')) { $__root = dirname($__root); }
require_once $__root . '/config.php';

requireLogin();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No se proporcionó ID de proyecto para visualizar.");
}

$id_project = htmlspecialchars($_GET['id']);
$proyecto = null;
$detalles = [];

try {
    $pdo = conexionDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt_project = $pdo->prepare("SELECT * FROM projects WHERE id_project = ?");
    $stmt_project->execute([$id_project]);
    $proyecto = $stmt_project->fetch(PDO::FETCH_ASSOC);

    if (!$proyecto) {
        die("Error: Proyecto con ID '$id_project' no encontrado.");
    }

    $stmt_details = $pdo->prepare("SELECT * FROM date_projects WHERE id_project = ?");
    $stmt_details->execute([$id_project]);
    $detalles = $stmt_details->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de Base de Datos al cargar datos: " . $e->getMessage());
}

function format_date($date) {
    return date('d/m/Y', strtotime($date));
}

$max_length = 150;
$project_name = $proyecto['name_project'];

$short_name = (mb_strlen($project_name, 'UTF-8') > $max_length)
    ? mb_substr($project_name, 0, $max_length, 'UTF-8') . '...'
    : $project_name;

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?php echo base_url('/'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="views/assets/css/base.css">
    <link rel="stylesheet" href="views/assets/css/visualize project.css">
    <script src="views/assets/public/toasts.js"></script>
    <script src="views/assets/public/transition.js"></script>
    <title>Detalles del Proyecto: <?php echo $proyecto['name_project']; ?></title>
</head>
<body>

    <header class="viz-header">
        <a href="views/index.php" class="volver" title="Volver">
            <img src="views/assets/imgs/icons/nav/arrow-left.svg" alt="Volver">
        </a>
        <h1><?php echo htmlspecialchars($short_name); ?></h1>
    </header>

    <main class="viz-main">

        <div class="info-box">
            <div class="info-item"><span>Estado Actual</span><strong><?php echo str_replace('_', ' ', $proyecto['estado_project']); ?></strong></div>
            <div class="info-item"><span>Fecha de Inicio</span><strong><?php echo format_date($proyecto['fecha_inicio_project']); ?></strong></div>
            <div class="info-item"><span>Fecha de Culminación</span><strong><?php echo format_date($proyecto['fecha_final_project']); ?></strong></div>
        </div>

        <h2 class="viz-section-title">Lista de Gastos</h2>

        <?php if (count($detalles) > 0): ?>
            <div class="viz-table-wrap">
                <table class="detalle-tabla">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Descripción del Gasto</th>
                            <th>Monto (Presupuesto)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($detalles as $detalle): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($detalle['gasto_project']); ?></td>
                                <td><?php echo number_format($detalle['gasto_num_project'], 2, '.', ','); ?> Bs</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="total-box">
                <span class="total">Total Presupuestado: <strong><?php echo number_format($proyecto['presupuesto_project'], 2, '.', ','); ?> Bs</strong></span>
            </div>

        <?php else: ?>
            <p class="viz-empty">El proyecto no tiene gastos o entradas de presupuesto registradas.</p>
        <?php endif; ?>

    </main>

</body>
</html>
