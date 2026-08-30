<?php
session_start();
require_once dirname(__DIR__, 2) . '/config.php';

$questOne = isset($_POST['security-question']) ? htmlspecialchars($_POST['security-question']) : '';
$questTwo = isset($_POST['security-question-2']) ? htmlspecialchars($_POST['security-question-2']) : '';
$answerOne = isset($_POST['quest1']) ? htmlspecialchars($_POST['quest1']) : '';
$answerTwo = isset($_POST['quest2']) ? htmlspecialchars($_POST['quest2']) : '';

if ($questOne === '' || $questTwo === '' || $answerOne === '' || $answerTwo === '') {
    redirect('/views/secure-questions/index.php?error=Seleccione las preguntas y responda ambas.');
    exit;
}

$answerOneHash = password_hash($answerOne, PASSWORD_BCRYPT);
$answerTwoHash = password_hash($answerTwo, PASSWORD_BCRYPT);

$db = conexionDB();

try {
    // Usar directamente el ID de la sesión
    $idUser = $_SESSION['idGlobal'];


    $sql = $db->prepare("INSERT INTO secure_questions(id_user, question1, answer1, question2, answer2)
                    VALUES(:idUserQuest, :oneQuest, :oneAnswer, :twoQuest, :twoAnswer)");

    $sql->bindParam(':idUserQuest', $idUser, PDO::PARAM_INT);
    $sql->bindParam(':oneQuest', $questOne, PDO::PARAM_STR);
    $sql->bindParam(':oneAnswer', $answerOneHash, PDO::PARAM_STR);
    $sql->bindParam(':twoQuest', $questTwo, PDO::PARAM_STR);
    $sql->bindParam(':twoAnswer', $answerTwoHash, PDO::PARAM_STR);
    $sql->execute();

    $_SESSION['idRegistered'] = $idUser;


    redirect('/views/secure-questions/successfull.php?success=Preguntas de seguridad guardadas exitosamente.');
    exit;
} catch (PDOException $e) {
    redirect('/views/secure-questions/index.php?error=No se pudieron guardar las preguntas. Intente nuevamente.');
    exit;
}
?>
