<?php
require_once dirname(__DIR__, 2) . '/config.php';

if (!isset($_POST['id_project']) || empty($_POST['id_project'])) {
    redirect('/views/index.php?error=' . urlencode('No se proporcionó el ID del proyecto.'));
    exit();
}

$id_project = htmlspecialchars($_POST['id_project']);
$pdo = conexionDB();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
$pdo->beginTransaction();

try {
    $query_delete_details = $pdo->prepare("DELETE FROM date_projects WHERE id_project = ?");
    $query_delete_details->execute([$id_project]);
    $detalles_eliminados = $query_delete_details->rowCount();

    $query_delete_project = $pdo->prepare("DELETE FROM projects WHERE id_project = ?");
    $query_delete_project->execute([$id_project]);
    $proyectos_eliminados = $query_delete_project->rowCount();

    if ($proyectos_eliminados > 0) {
        $pdo->commit(); 
        $mensaje = 'El proyecto fue eliminado correctamente, junto con ' . $detalles_eliminados . ' detalle(s).';
        redirect('/views/index.php?success=' . urlencode($mensaje));
    } else {
        $pdo->rollBack();
        $mensaje = 'El proyecto con ID ' . $id_project . ' no fue encontrado para eliminar.';
        redirect('/views/index.php?error=' . urlencode($mensaje));
    }


} catch (PDOException $e) {
    $pdo->rollBack(); 
    $mensaje = 'Error al eliminar el proyecto.';
    redirect('/views/index.php?error=' . urlencode($mensaje));
}

exit();