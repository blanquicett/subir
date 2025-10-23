<?php
include "../../conexion.php";
$id = $_GET['idModeloA'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/modeloAviones.php');
    exit;
}
$sql = "SELECT idModeloA, modelo, capacidad FROM modeloaviones WHERE idModeloA = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else die('Error');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelo = $_POST['modelo'] ?? '';
    $capacidad = $_POST['capacidad'] ?? 0;
    $update = "UPDATE modeloaviones SET modelo = ?, capacidad = ? WHERE idModeloA = ?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'sii', $modelo, $capacidad, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/modeloAviones.php');
        exit;
    } else $error = 'Error';
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Editar Modelo</title>
</head>

<body>
    <h2>Editar Modelo</h2>
    <?php if (!empty($error)) echo '<p>' . htmlspecialchars($error) . '</p>'; ?>
    <form method="post">
        <input name="modelo" value="<?= htmlspecialchars($row['modelo']) ?>" required>
        <input name="capacidad" type="number" value="<?= htmlspecialchars($row['capacidad']) ?>" required>
        <button>Guardar</button>
    </form>
    <p><a href="../../pages/admin/modeloAviones.php">Volver</a></p>
</body>

</html>