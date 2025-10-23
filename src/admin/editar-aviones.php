<?php
include "../../conexion.php";
$id = $_GET['idAvion'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/aviones.php');
    exit;
}
$sql = "SELECT idAvion, nombreAvion, idModeloA, idAerolinea FROM aviones WHERE idAvion = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else die('Error');
// obtener modelos y aerolineas
$modelos = [];
$mRes = mysqli_query($conexion, "SELECT idModeloA, modelo FROM modeloaviones ORDER BY modelo");
if ($mRes && mysqli_num_rows($mRes) > 0) while ($m = mysqli_fetch_assoc($mRes)) $modelos[] = $m;
$aerolineas = [];
$aRes = mysqli_query($conexion, "SELECT idAerolinea, nombreAerolinea FROM aerolinea ORDER BY nombreAerolinea");
if ($aRes && mysqli_num_rows($aRes) > 0) while ($a = mysqli_fetch_assoc($aRes)) $aerolineas[] = $a;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombreAvion'] ?? '';
    $idModelo = $_POST['idModeloA'] ?? 0;
    $idAerolinea = $_POST['idAerolinea'] ?? 0;
    $update = "UPDATE aviones SET nombreAvion=?, idModeloA=?, idAerolinea=? WHERE idAvion=?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'siii', $nombre, $idModelo, $idAerolinea, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/aviones.php');
        exit;
    } else $error = 'Error';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Avión</title>
</head>

<body>
    <h2>Editar Avión</h2>
    <?php if (!empty($error)) echo '<p>' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <input name="nombreAvion" value="<?= htmlspecialchars($row['nombreAvion']) ?>" required>
        <select name="idModeloA" required>
            <?php foreach ($modelos as $m): ?>
                <option value="<?= htmlspecialchars($m['idModeloA']) ?>" <?= $row['idModeloA'] == $m['idModeloA'] ? 'selected' : '' ?>><?= htmlspecialchars($m['modelo']) ?></option>
            <?php endforeach; ?>
        </select>
        <select name="idAerolinea" required>
            <?php foreach ($aerolineas as $a): ?>
                <option value="<?= htmlspecialchars($a['idAerolinea']) ?>" <?= $row['idAerolinea'] == $a['idAerolinea'] ? 'selected' : '' ?>><?= htmlspecialchars($a['nombreAerolinea']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="../../pages/admin/aviones.php">Volver</a></p>
</body>

</html>