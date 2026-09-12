<?php
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/index.php');
    exit();
}

requireLogin();
csrf_check();

$id = htmlspecialchars($_POST["Id"]);
$name_project = htmlspecialchars(ucfirst($_POST["Titulo"]));
$fecha_inicio = $_POST["Fecha_inicio"];
$fecha_culminacion = $_POST["Fecha_culminacion"];
$estado = $_POST["Estado"];
$descripciones = isset($_POST['descripciones']) ? $_POST['descripciones'] : [];
$precios = isset($_POST['precios']) ? $_POST['precios'] : [];

$registrosGuardados = 0;
$presupuesto = 0.00;

if (is_array($descripciones)) {
    foreach ($descripciones as $indice => $descripcion) {
        $precio_str = isset($precios[$indice]) ? $precios[$indice] : '0.00';
        $precio_limpio = str_replace(',', '.', $precio_str);
        $precio_item = floatval($precio_limpio);
        if (!empty(trim($descripcion))) {
            $presupuesto += $precio_item;
        }
    }
}

if (is_array($descripciones) && count($descripciones) > 0) {

    try {
        $pdo = conexionDB();
        $pdo->beginTransaction();

        $query_details_sql = "INSERT INTO date_projects (id_project, gasto_project, gasto_num_project) VALUES (?, ?, ?)";
        $stmt_details = $pdo->prepare($query_details_sql);

        $query_project = $pdo->prepare("INSERT INTO projects (id_project, name_project, fecha_inicio_project, fecha_final_project, estado_project, presupuesto_project) VALUES (:id, :namep, :inicio, :final, :estado, :presupuesto)");

        $query_project->bindParam(":id", $id);
        $query_project->bindParam(":namep", $name_project);
        $query_project->bindParam(":inicio", $fecha_inicio);
        $query_project->bindParam(":final", $fecha_culminacion);
        $query_project->bindParam(":estado", $estado);
        $query_project->bindParam(":presupuesto", $presupuesto);

        $query_project->execute();

        foreach ($descripciones as $indice => $descripcion) {

            $precio_str = isset($precios[$indice]) ? $precios[$indice] : '0.00';
            $precio_limpio = str_replace(',', '.', $precio_str);
            $precio_item = floatval($precio_limpio);

            if (!empty(trim($descripcion))) {
                $stmt_details->execute([$id, $descripcion, $precio_item]);
                $registrosGuardados++;
            }
        }

        $pdo->commit();

        redirect('/views/index.php?Ejecutado=' . urlencode('El proyecto "' . $name_project . '" se ha guardado correctamente (' . $registrosGuardados . ' detalle(s)).'));

    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log('Create project error: ' . $e->getMessage());
        redirect('/views/index.php?Error=' . urlencode('Error al guardar datos. Intente nuevamente.'));
    }

} else {
    redirect('/views/index.php?Error=' . urlencode('No se recibieron datos válidos para procesar.'));
}