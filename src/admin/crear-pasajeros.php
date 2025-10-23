<?php
include "../../conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/admin/pasajeros.php');
    exit;
}
$nombres = $_POST['nombres'] ?? '';
$primerApellido = $_POST['primerApellido'] ?? '';
$segundoApellido = $_POST['segundoApellido'] ?? '';
$fechNacimiento = $_POST['fechNacimiento'] ?? '';
$genero = $_POST['genero'] ?? '';
$tipoDocumento = $_POST['tipoDocumento'] ?? '';
$documento = $_POST['documento'] ?? '';
$celular = $_POST['celular'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$idrRol = $_POST['idrRol'] ?? 0;
if ($nombres === '' || $email === '') die('Campos requeridos');
$sql = "INSERT INTO pasajeros (nombres, primerApellido, segundoApellido, fechNacimiento, genero, tipoDocumento, documento, celular, email, password, idrRol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssssssssssi', $nombres, $primerApellido, $segundoApellido, $fechNacimiento, $genero, $tipoDocumento, $documento, $celular, $email, $password, $idrRol);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/pasajeros.php');
    exit;
} else die('Error');
