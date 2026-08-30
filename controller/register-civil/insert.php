<?php

require_once dirname(__DIR__, 2) . '/config.php';
session_start();

// sólo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Método no permitido');
}

$errors = [];

// Sanitizar entradas

$idCivil       = filter_var($_POST["cedula"],FILTER_SANITIZE_NUMBER_INT);
$nameCivil     = ucwords($_POST['nombre']);
$surnameCivil  = ucwords($_POST['apellido']);
$gender        = ucfirst($_POST['sexo']);
$phone         = filter_var($_POST["telefono"],FILTER_SANITIZE_NUMBER_INT);
$comitted      = ucwords($_POST['comite']);
$address       = ucwords($_POST['direccion']);
$dateOfBirth   = $_POST['fecha_nacimiento'];
$email         = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$codeDni       = $_POST['codigo_carnet'];
$serialDni     = $_POST['serial_carnet'];
$centerVotation= ucwords($_POST['centro_votacion']);
$voteType      = ucwords($_POST['tipo_voto']);

// Validaciones por campo
if ($idCivil === '' || !preg_match('/^[0-9]{6,10}$/', $idCivil)) {
    $errors['cedula'] = 'Cédula inválida, debe tener entre 6 y 10 dígitos.';
}
if ($nameCivil === '' || !preg_match('/^[A-Za-zÑñÁÉÍÓÚáéíóú\s\-]{2,50}$/', $nameCivil)) {
    $errors['nombre'] = 'Nombre inválido, solo letras y espacios.';
}
if ($surnameCivil === '' || !preg_match('/^[A-Za-zÑñÁÉÍÓÚáéíóú\s\-]{2,50}$/', $surnameCivil)) {
    $errors['apellido'] = 'Apellido inválido, solo letras y espacios.';
}
$genders = ['Masculino' => 1, 'Femenino' => 1];
if ($gender === '' || !isset($genders[$gender])) {
    $errors['sexo'] = 'Seleccione un género.';
}
if ($phone === '' || !preg_match('/^[0-9]{7,20}$/', $phone)) {
    $errors['telefono'] = 'Número de teléfono inválido (7 a 20 dígitos).';
}
if ($comitted === '') {
    $errors['comite'] = 'Seleccione un comité.';
}
if ($address === '' || strlen($address) < 5) {
    $errors['direccion'] = 'La dirección debe tener al menos 5 caracteres.';
}
if ($dateOfBirth === '' || !strtotime($dateOfBirth)) {
    $errors['fecha_nacimiento'] = 'Seleccione una fecha de nacimiento válida.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['correo'] = 'Correo electrónico inválido.';
}
if (!preg_match('/^[A-Za-z0-9\-]{3,30}$/', $codeDni)) {
    $errors['codigo_carnet'] = 'Código alfanumérico inválido (3-30 caracteres).';
}
if (!preg_match('/^[A-Za-z0-9\-]{3,30}$/', $serialDni)) {
    $errors['serial_carnet'] = 'Serial alfanumérico inválido (3-30 caracteres).';
}
$centers = ['Liceo Bolivariano Maestro Gallegos' => 1, 'Caipa' => 1, 'Alicia Tremont de Medina' => 1, 'Inces' => 1];
if (!isset($centers[$centerVotation])) {
    $errors['centro_votacion'] = 'Seleccione un centro de votación.';
}
$votes = ['Presencial' => 1, 'Asistido' => 1];
if (!isset($votes[$voteType])) {
    $errors['tipo_voto'] = 'Seleccione un tipo de voto.';
}

if (!empty($errors)) {
    $_SESSION['errores'] = $errors;
    redirect('/views/register-civil/form-register/index.php');
    exit();
}

$createDate    = date('Y-m-d H:i:s');

function ageToday($dateOfBirth){
    $newAge = new DateTime($dateOfBirth);
    $today = new DateTime();
    
    $diff = $newAge->diff($today);
    return $diff->y;
}
$today = ageToday($dateOfBirth);
// DB e inserción
$db = conexionDB();
try {
    $insertQuery = $db->prepare("INSERT INTO People_Data (ID_CI, FirstName, LastName, Sex, Phone_Number, Committee_Name, Address_Civil, Birth_Date, Age, Email_Address, Patria_Card_Code, Patria_Card_Serial, Voting_Center, Vote_Type)
        VALUES (:idCivil, :nameCivil, :surnameCivil, :gender, :phone, :comitted, :addressCivil, :dateOfBirth, :age, :email, :codeDni, :serialDni, :centerVotation, :voteType)");

    $insertQuery->bindParam(':idCivil', $idCivil);
    $insertQuery->bindParam(':nameCivil', $nameCivil);
    $insertQuery->bindParam(':surnameCivil', $surnameCivil);
    $insertQuery->bindParam(':gender', $gender);
    $insertQuery->bindParam(':phone', $phone);
    $insertQuery->bindParam(':comitted', $comitted);
    $insertQuery->bindParam(':addressCivil', $address);
    $insertQuery->bindParam(':dateOfBirth', $dateOfBirth);
    $insertQuery->bindParam(':age', $today, PDO::PARAM_INT);
    $insertQuery->bindParam(':email', $email);
    $insertQuery->bindParam(':codeDni', $codeDni);
    $insertQuery->bindParam(':serialDni', $serialDni);
    $insertQuery->bindParam(':centerVotation', $centerVotation);
    $insertQuery->bindParam(':voteType', $voteType);
    $insertQuery->execute();

    $_SESSION['mensaje'] = true;
    redirect('/views/register-civil/form-register/index.php');
    exit();
} catch (PDOException $e) {
    error_log('Insert error: ' . $e->getMessage());
    if ((int)$e->getCode() === 23000) {
        $_SESSION['errores'] = ['cedula' => 'Ya existe una persona registrada con esa cédula.'];
    } else {
        $_SESSION['error'] = 'Error al insertar el registro.';
    }
    redirect('/views/register-civil/form-register/index.php');
    exit();
}
?>
