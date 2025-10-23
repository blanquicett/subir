<?php
include "../../conexion.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../pages/admin/disponibilidad.php');
    exit;
}
$fecha = $_POST['fecha'] ?? '';
$asiento = $_POST['asiento'] ?? '';
$origen = $_POST['origen'] ?? '';
$destino = $_POST['destino'] ?? '';
$horaSalida = $_POST['horaSalida'] ?? '';
$horaLlegada = $_POST['horaLlegada'] ?? '';
$idAvion = $_POST['idAvion'] ?? 0;
if ($fecha === '' || $origen === '' || $destino === '') die('Campos requeridos');
$sql = "INSERT INTO disponibilidad (fecha, asiento, origen, destino, horaSalida, horaLlegada, idAvion) VALUES (?, ?, ?, ?, ?, ?, ?)";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'ssssssi', $fecha, $asiento, $origen, $destino, $horaSalida, $horaLlegada, $idAvion);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/disponibilidad.php');
    exit;
} else die('Error');
