<?php
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/auth-identification/index.php');
    exit();
}

csrf_check();

$idCi = isset($_POST['ci_quest']) ? preg_replace('/\D/', '', $_POST['ci_quest']) : '';

if ($idCi === '' || !preg_match('/^[0-9]{6,10}$/', $idCi)) {
    $_SESSION['errors'] = ['ci_quest' => 'Cédula inválida, debe contener entre 6 y 10 dígitos.'];
    redirect('/views/auth-identification/index.php');
    exit();
}

$db = conexionDB();

try {
    $sqlQueryId = $db->prepare('SELECT * FROM secure_questions WHERE id_user = :idDni');
    $sqlQueryId->bindParam(':idDni', $idCi, PDO::PARAM_INT);
    $sqlQueryId->execute();

    $resultId = $sqlQueryId->fetch(PDO::FETCH_ASSOC);

    if ($resultId) {
        $_SESSION['id'] = $idCi;
        $_SESSION['q1'] = $resultId['question1'];
        $_SESSION['q2'] = $resultId['question2'];
        redirect('/views/recover-password/index.php');
        exit();
    } else {
        $_SESSION['errors'] = ['ci_quest' => 'No se encontró la cédula.'];
        redirect('/views/auth-identification/index.php');
        exit();
    }

} catch (PDOException $e) {
    error_log('AuthId error: ' . $e->getMessage());
    $_SESSION['error'] = 'Error en el servidor.';
    redirect('/views/auth-identification/index.php');
    exit();
}
?>