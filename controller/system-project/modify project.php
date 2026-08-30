<?php
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/index.php');
    exit();
}

$id = isset($_POST['Id']) ? trim($_POST['Id']) : '';
$name_project = isset($_POST['Titulo']) ? ucfirst(trim($_POST['Titulo'])) : '';
$fecha_inicio = isset($_POST['Fecha_inicio']) ? $_POST['Fecha_inicio'] : '';
$fecha_culminacion = isset($_POST['Fecha_culminacion']) ? $_POST['Fecha_culminacion'] : '';
$estado = isset($_POST['Estado']) ? $_POST['Estado'] : '';

$detalles_existentes = $_POST['detalles_existentes'] ?? [];
$detalles_nuevos = $_POST['detalles_nuevos'] ?? [];
$detalles_a_eliminar = $_POST['detalles_a_eliminar'] ?? '';

if (empty($id)) {
    redirect('/views/index.php?error=' . urlencode('ID de proyecto no especificado.'));
    exit();
}

if (strlen($name_project) < 10) {
    redirect('/views/Modify Project/index.php?id=' . urlencode($id) . '&error=' . urlencode('El título del proyecto debe tener al menos 10 caracteres.'));
    exit();
}

$presupuesto = 0.00;

function normalize_price($price_str) {
    $precio_limpio = str_replace(',', '.', $price_str);
    return floatval($precio_limpio);
}

foreach ($detalles_existentes as $detalle) {
    if (isset($detalle['descripcion']) && !empty(trim($detalle['descripcion']))) {
        $presupuesto += normalize_price($detalle['precio'] ?? '0.00');
    }
}

foreach ($detalles_nuevos as $detalle) {
    if (isset($detalle['descripcion']) && !empty(trim($detalle['descripcion']))) {
        $presupuesto += normalize_price($detalle['precio'] ?? '0.00');
    }
}

$pdo = conexionDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->beginTransaction();

try {

    $query_update_project = $pdo->prepare("UPDATE projects SET
        name_project = :namep,
        fecha_inicio_project = :inicio,
        fecha_final_project = :final,
        estado_project = :estado,
        presupuesto_project = :presupuesto
        WHERE id_project = :id");

    $query_update_project->bindParam(":id", $id);
    $query_update_project->bindParam(":namep", $name_project);
    $query_update_project->bindParam(":inicio", $fecha_inicio);
    $query_update_project->bindParam(":final", $fecha_culminacion);
    $query_update_project->bindParam(":estado", $estado);
    $query_update_project->bindParam(":presupuesto", $presupuesto);

    $query_update_project->execute();

    if (!empty($detalles_a_eliminar)) {
        $ids_eliminar = array_filter(array_map('intval', explode(',', $detalles_a_eliminar)));

        if (count($ids_eliminar) > 0) {
            $placeholders = implode(',', array_fill(0, count($ids_eliminar), '?'));

            $query_delete_details = $pdo->prepare("DELETE FROM date_projects WHERE id_date_project IN ($placeholders) AND id_project = ?");

            $params = array_merge($ids_eliminar, [$id]);

            $query_delete_details->execute($params);
        }
    }

    $query_update_details = $pdo->prepare("UPDATE date_projects SET
        gasto_project = ?,
        gasto_num_project = ?
        WHERE id_date_project = ? AND id_project = ?");

    foreach ($detalles_existentes as $detalle) {
        if (isset($detalle['descripcion']) && !empty(trim($detalle['descripcion']))) {
            $id_detalle = $detalle['id'] ?? null;
            $descripcion = $detalle['descripcion'];
            $precio = normalize_price($detalle['precio'] ?? '0.00');

            if ($id_detalle) {
                $query_update_details->execute([$descripcion, $precio, $id_detalle, $id]);
            }
        }
    }

    $query_insert_details = $pdo->prepare("INSERT INTO date_projects (id_project, gasto_project, gasto_num_project) VALUES (?, ?, ?)");

    foreach ($detalles_nuevos as $detalle) {
        if (isset($detalle['descripcion']) && !empty(trim($detalle['descripcion']))) {
            $descripcion = $detalle['descripcion'];
            $precio = normalize_price($detalle['precio'] ?? '0.00');

            $query_insert_details->execute([$id, $descripcion, $precio]);
        }
    }

    $pdo->commit();

    redirect('/views/index.php?Ejecutado=' . urlencode('El proyecto "' . $name_project . '" se actualizó correctamente.'));
    exit();

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log('Modify project error: ' . $e->getMessage());
    redirect('/views/index.php?Error=' . urlencode('Error al guardar las modificaciones del proyecto.'));
    exit();
}