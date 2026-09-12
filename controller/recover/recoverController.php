<?php
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/views/recover-password/index.php');
    exit();
}

csrf_check();

$question = isset($_POST['answer']) ? trim($_POST['answer']) : '';
$questionTwo = isset($_POST['answer-2']) ? trim($_POST['answer-2']) : '';

$errors = [];
if ($question === '') {
    $errors['answer'] = 'Responda la primera pregunta.';
}
if ($questionTwo === '') {
    $errors['answer-2'] = 'Responda la segunda pregunta.';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    redirect('/views/recover-password/index.php');
    exit();
}

$dbConnect = conexionDB();

try {
    $idRecover = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    if (!$idRecover) {
        $_SESSION['error'] = 'Identificación no encontrada. Inicie el proceso nuevamente.';
        redirect('/views/auth-identification/index.php');
        exit();
    }

    $stmt = $dbConnect->prepare('SELECT * FROM secure_questions WHERE id_user = :ci');
    $stmt->bindParam(':ci', $idRecover, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['error'] = 'No se encontraron preguntas de seguridad para este usuario.';
        redirect('/views/auth-identification/index.php');
        exit();
    }

    $_SESSION['id_user'] = $user['id_user'];

    if (password_verify($question, $user['answer1']) && password_verify($questionTwo, $user['answer2'])) {
        redirect('/views/change-password/index.php');
        exit();
    } else {
        $_SESSION['error'] = 'Respuestas incorrectas. Inténtalo de nuevo.';
        redirect('/views/recover-password/index.php');
        exit();
    }

} catch (PDOException $e) {
    error_log('Recover error: ' . $e->getMessage());
    $_SESSION['error'] = 'Error en la consulta. Intente más tarde.';
    redirect('/views/recover-password/index.php');
    exit();
}

?>