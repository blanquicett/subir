<?php
include "../../conexion.php";
$id = $_GET['idDisponibilidad'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/disponibilidad.php');
    exit;
}
$sql = "SELECT * FROM disponibilidad WHERE idDisponibilidad = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else die('Error');
// obtener aviones
$aviones = [];
$avRes = mysqli_query($conexion, "SELECT idAvion, nombreAvion FROM aviones ORDER BY nombreAvion");
if ($avRes && mysqli_num_rows($avRes) > 0) while ($a = mysqli_fetch_assoc($avRes)) $aviones[] = $a;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $asiento = $_POST['asiento'] ?? '';
    $origen = $_POST['origen'] ?? '';
    $destino = $_POST['destino'] ?? '';
    $horaSalida = $_POST['horaSalida'] ?? '';
    $horaLlegada = $_POST['horaLlegada'] ?? '';
    $idAvion = $_POST['idAvion'] ?? 0;
    $update = "UPDATE disponibilidad SET fecha=?, asiento=?, origen=?, destino=?, horaSalida=?, horaLlegada=?, idAvion=? WHERE idDisponibilidad=?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'ssssssii', $fecha, $asiento, $origen, $destino, $horaSalida, $horaLlegada, $idAvion, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/disponibilidad.php');
        exit;
    } else $error = 'Error';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Disponibilidad</title>
</head>

<body>
    <h2>Editar Disponibilidad</h2>
    <?php if (!empty($error)) echo '<p>' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <input name="fecha" type="date" value="<?= htmlspecialchars($row['fecha']) ?>">
        <input name="asiento" value="<?= htmlspecialchars($row['asiento']) ?>">
        <input name="origen" value="<?= htmlspecialchars($row['origen']) ?>">
        <input name="destino" value="<?= htmlspecialchars($row['destino']) ?>">
        <input name="horaSalida" value="<?= htmlspecialchars($row['horaSalida']) ?>">
        <input name="horaLlegada" value="<?= htmlspecialchars($row['horaLlegada']) ?>">
        <select name="idAvion">
            <?php foreach ($aviones as $a): ?>
                <option value="<?= htmlspecialchars($a['idAvion']) ?>" <?= $row['idAvion'] == $a['idAvion'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nombreAvion']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="../../pages/admin/disponibilidad.php">Volver</a></p>
</body>

</html>