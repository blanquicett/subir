<?php
include "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
header('Location: ../../pages/admin/roles.php');    exit;
}

$nombre = $_POST['nombreRol'] ?? '';
if ($nombre === '') die('Nombre requerido');

$sql = "INSERT INTO roles (nombreRol) VALUES (?)";
if ($stmt = mysqli_prepare($conexion, $sql)){
    mysqli_stmt_bind_param($stmt, 's', $nombre);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
header('Location: ../../pages/admin/roles.php');    exit;
} else {
    die('Error al preparar inserción');
}
