<?php
include "../../conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/admin/reservas.php');
    exit;
}
$condicionInfante = $_POST['condicionInfante'] ?? 0;
$iva = $_POST['iva'] ?? 0;
$descuento = $_POST['descuento'] ?? 0;
$subtotal = $_POST['subtotal'] ?? 0;
$idDisponibilidad = $_POST['idDisponibilidad'] ?? 0;
$idPasajeros = $_POST['idPasajeros'] ?? 0;
$sql = "INSERT INTO reservas (condicionInfante, iva, descuento, subtotal, idDisponibilidad, idPasajeros) VALUES (?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ddddii', $condicionInfante, $iva, $descuento, $subtotal, $idDisponibilidad, $idPasajeros);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/reservas.php');
    exit;
} else die('Error');
