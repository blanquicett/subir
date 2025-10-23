<?php
include "../../conexion.php";
$id = $_GET['idPasajero'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/pasajeros.php');
    exit;
}
$sql = "SELECT * FROM pasajeros WHERE idPasajero = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else die('Error');

// obtener roles para select
$roles = [];
$rSql = "SELECT idRol, nombreRol FROM roles ORDER BY nombreRol";
$rRes = mysqli_query($conexion, $rSql);
if ($rRes && mysqli_num_rows($rRes) > 0) while ($r = mysqli_fetch_assoc($rRes)) $roles[] = $r;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    $update = "UPDATE pasajeros SET nombres=?, primerApellido=?, segundoApellido=?, fechNacimiento=?, genero=?, tipoDocumento=?, documento=?, celular=?, email=?, password=?, idrRol=? WHERE idPasajero=?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'ssssssssssii', $nombres, $primerApellido, $segundoApellido, $fechNacimiento, $genero, $tipoDocumento, $documento, $celular, $email, $password, $idrRol, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/pasajeros.php');
        exit;
    } else $error = 'Error';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Pasajero</title>
</head>

<body>
    <h2>Editar Pasajero</h2>
    <?php if (!empty($error)) echo '<p>' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <input name="nombres" value="<?= htmlspecialchars($row['nombres']) ?>" required>
        <input name="primerApellido" value="<?= htmlspecialchars($row['primerApellido']) ?>" required>
        <input name="segundoApellido" value="<?= htmlspecialchars($row['segundoApellido']) ?>">
        <input name="fechNacimiento" type="date" value="<?= htmlspecialchars($row['fechNacimiento']) ?>">
        <select name="genero" required>
            <option value="Masculino" <?= $row['genero'] === 'Masculino' ? 'selected' : '' ?>>Masculino</option>
            <option value="Femenino" <?= $row['genero'] === 'Femenino' ? 'selected' : '' ?>>Femenino</option>
            <option value="Otro" <?= $row['genero'] === 'Otro' ? 'selected' : '' ?>>Otro</option>
        </select>
        <input name="tipoDocumento" value="<?= htmlspecialchars($row['tipoDocumento']) ?>">
        <input name="documento" value="<?= htmlspecialchars($row['documento']) ?>">
        <input name="celular" value="<?= htmlspecialchars($row['celular']) ?>">
        <input name="email" value="<?= htmlspecialchars($row['email']) ?>">
        <input name="password" value="<?= htmlspecialchars($row['password']) ?>">
        <select name="idrRol" required>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= htmlspecialchars($rol['idRol']) ?>" <?= $row['idrRol'] == $rol['idRol'] ? 'selected' : '' ?>><?= htmlspecialchars($rol['nombreRol']) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <p><a href="../../pages/admin/pasajeros.php">Volver</a></p>
</body>

</html>