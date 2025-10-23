<?php
include "../../conexion.php";
$id = $_GET['idReserva'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/reservas.php');
    exit;
}
$sql = "SELECT * FROM reservas WHERE idReserva = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else die('Error');

// obtener disponibilidades y pasajeros
$disps = [];
$dRes = mysqli_query($conexion, "SELECT idDisponibilidad, fecha, origen, destino FROM disponibilidad ORDER BY fecha");
if ($dRes && mysqli_num_rows($dRes) > 0) while ($d = mysqli_fetch_assoc($dRes)) $disps[] = $d;
$pasajeros = [];
$pRes = mysqli_query($conexion, "SELECT idPasajero, nombres, primerApellido FROM pasajeros ORDER BY nombres");
if ($pRes && mysqli_num_rows($pRes) > 0) while ($p = mysqli_fetch_assoc($pRes)) $pasajeros[] = $p;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $condicionInfante = $_POST['condicionInfante'] ?? 0;
    $iva = $_POST['iva'] ?? 0;
    $descuento = $_POST['descuento'] ?? 0;
    $subtotal = $_POST['subtotal'] ?? 0;
    $idDisponibilidad = $_POST['idDisponibilidad'] ?? 0;
    $idPasajeros = $_POST['idPasajeros'] ?? 0;
    $update = "UPDATE reservas SET condicionInfante=?, iva=?, descuento=?, subtotal=?, idDisponibilidad=?, idPasajeros=? WHERE idReserva=?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'ddddiii', $condicionInfante, $iva, $descuento, $subtotal, $idDisponibilidad, $idPasajeros, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/reservas.php');
        exit;
    } else $error = 'Error';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Reserva</title>
</head>

<body>
    <h2>Editar Reserva</h2>
    <?php if (!empty($error)) echo '<p>' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <input name="condicionInfante" value="<?= htmlspecialchars($row['condicionInfante']) ?>">
        <input name="iva" value="<?= htmlspecialchars($row['iva']) ?>">
        <input name="descuento" value="<?= htmlspecialchars($row['descuento']) ?>">
        <input name="subtotal" value="<?= htmlspecialchars($row['subtotal']) ?>">
        <select name="idDisponibilidad">
            <?php foreach ($disps as $d): ?>
                <option value="<?= htmlspecialchars($d['idDisponibilidad']) ?>" <?= $row['idDisponibilidad'] == $d['idDisponibilidad'] ? 'selected' : '' ?>><?= htmlspecialchars($d['fecha'].' - '.$d['origen'].' → '.$d['destino']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="idPasajeros">
            <?php foreach ($pasajeros as $p): ?>
                <option value="<?= htmlspecialchars($p['idPasajero']) ?>" <?= $row['idPasajeros'] == $p['idPasajero'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nombres'].' '.$p['primerApellido']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="../../pages/admin/reservas.php">Volver</a></p>
</body>

</html>