<?php 
require_once dirname(__DIR__, 2) . '/config.php';
$db = conexionDB();

// obteniendo total de civiles registrados
try{
    $sqlQueryPeople = $db->prepare("SELECT count(*) FROM People_Data");
    $sqlQueryPeople->execute();
    $resultPeople = $sqlQueryPeople->fetch(PDO::FETCH_ASSOC);
    $_SESSION['peopleRegisterTotal'] = $resultPeople['count(*)'];


}catch(PDOException $e){
    echo $e;
}

// obteniendo total de administradores registrados
try{
    $sqlQuery = $db->prepare("SELECT count(*) FROM users_admins");
    $sqlQuery->execute();
    $result = $sqlQuery->fetch(PDO::FETCH_ASSOC);

    $_SESSION['userRegisterTotal'] = $result['count(*)'];
}catch(PDOException $e){
    echo $e;
}
// obteniendo datos de los proyectos
try{
    $sqlQueryProjects = $db->prepare("SELECT count(*) FROM projects");
    $sqlQueryProjects->execute();
    $resultProjects = $sqlQueryProjects->fetch(PDO::FETCH_ASSOC);
    $_SESSION['projectsRegisterTotal'] = $resultProjects['count(*)'];


}catch(PDOException $e){
    echo $e;
}

// obteniendo estados de los proyectos


try {
    $sqlQueryProjectsState = $db->prepare("
        SELECT estado_project, COUNT(*) AS totalstate 
        FROM projects 
        GROUP BY estado_project
    ");
    $sqlQueryProjectsState->execute();
    
    $stateArray = [
        'Planificando' => 0,
        'En_Proceso'   => 0,
        'Incompleto'   => 0,
        'Finalizado'   => 0
    ];

    // Rellenamos el arreglo con los resultados de la consulta
    while ($row = $sqlQueryProjectsState->fetch(PDO::FETCH_ASSOC)) {
        $estado = $row['estado_project'];
        $total  = $row['totalstate'];

        if (array_key_exists($estado, $stateArray)) {
            $stateArray[$estado] = $total;
        }
    }

    // Guardamos los resultados en variables de sesión
    $_SESSION['projects_state'] = $stateArray;

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>