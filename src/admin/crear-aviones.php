<?php
include "../../conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/admin/aviones.php');
    exit;
}
$nombre = $_POST['nombreAvion'] ?? '';
$idModelo = $_POST['idModeloA'] ?? 0;
$idAerolinea = $_POST['idAerolinea'] ?? 0;
if ($nombre === '') die('Nombre requerido');
$sql = "INSERT INTO aviones (nombreAvion, idModeloA, idAerolinea) VALUES (?, ?, ?)";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'sii', $nombre, $idModelo, $idAerolinea);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/aviones.php');
    exit;
} else die('Error');
