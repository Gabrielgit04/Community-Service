<?php
session_start();
require_once dirname(__DIR__, 2) . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $_SESSION['error'] = 'Método de envío incorrecto.';
    redirect('/views/register-civil/read/index.php');
    exit();
}


$mapFields = [
    "FirstName" => "FirstName",
    "LastName" => "LastName",
    "ID_CI" => "ID_CI",
    "Sex" => "Sex",
    "Phone_Number" => "Phone_Number",
    "Committee_Name" => "Committee_Name",
    "Address_Civil" => "Address_Civil",
    "Birth_Date" => "Birth_Date",
    "Age" => "Age",
    "Email_Address" => "Email_Address",
    "Patria_Card_Code" => "Patria_Card_Code",
    "Patria_Card_Serial" => "Patria_Card_Serial",
    "Voting_Center" => "Voting_Center",
    "Vote_Type" => "Vote_Type",
];



// Datos recibidos
$idCivil = isset($_GET['cedula']) ? filter_var($_GET['cedula'], FILTER_SANITIZE_NUMBER_INT) : '';
$choiceOption = isset($_GET['choiceUpdate']) ? $_GET['choiceUpdate'] : '';
$updateField = isset($_GET['UPDATE_FIELD']) ? ucwords($_GET['UPDATE_FIELD']) : '';

if ($idCivil === '' || !isset($mapFields[$choiceOption])) {
    $_SESSION['error'] = 'Parámetros de actualización inválidos.';
    redirect('/views/register-civil/read/index.php');
    exit();
}
if ($updateField === '') {
    $_SESSION['error'] = 'El campo a actualizar no puede estar vacío.';
    redirect('/views/register-civil/read/index.php');
    exit();
}

$db = conexionDB();
try {

    $campoBD = $mapFields[$choiceOption];

    $update = $db->prepare("UPDATE People_Data SET $campoBD = :fieldUpdate  WHERE ID_CI = :idCivil");
    $update->bindParam(':fieldUpdate', $updateField);
    $update->bindParam(':idCivil', $idCivil, PDO::PARAM_INT);
    $update->execute();

    $_SESSION['mensaje_update'] = true;
    redirect('/views/register-civil/read/index.php');
    exit();
} catch (PDOException $e) {
    error_log('Update error: ' . $e->getMessage());
    $_SESSION['error'] = 'Error al actualizar el registro.';
    redirect('/views/register-civil/read/index.php');
    exit();
}