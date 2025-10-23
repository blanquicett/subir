<?php
include "../../conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/admin/modeloAviones.php');
    exit;
}
$modelo = $_POST['modelo'] ?? '';
$capacidad = $_POST['capacidad'] ?? 0;
if ($modelo === '') die('Modelo requerido');
$sql = "INSERT INTO modeloaviones (modelo, capacidad) VALUES (?, ?)";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'si', $modelo, $capacidad);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/modeloAviones.php');
    exit;
} else die('Error');
