<?php
include "../../conexion.php";

$id = $_GET['idAerolinea'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/aerolineas.php');
    exit;
}

// Obtener datos actuales
$sql = "SELECT idAerolinea, nombreAerolinea, email, nit, direccion, password FROM aerolinea WHERE idAerolinea = ?";
if ($stmt = mysqli_prepare($conexion, $sql)) {
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else {
    die('Error al preparar la consulta');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombreAerolinea'] ?? '';
    $email = $_POST['email'] ?? '';
    $nit = $_POST['nit'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $password = $_POST['password'] ?? '';

    $update = "UPDATE aerolinea SET nombreAerolinea=?, email=?, nit=?, direccion=?, password=? WHERE idAerolinea=?";
    if ($stmt = mysqli_prepare($conexion, $update)) {
        mysqli_stmt_bind_param($stmt, 'sssssi', $nombre, $email, $nit, $direccion, $password, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/aerolineas.php');
        exit;
    } else {
        $error = 'Error al preparar actualización';
    }
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Editar Aerolinea</title>
</head>
<body>
<h2>Editar Aerolínea</h2>
<?php if (!empty($error)) echo '<p>'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <input name="nombreAerolinea" value="<?= htmlspecialchars($row['nombreAerolinea']) ?>" required>
    <input name="email" type="email" value="<?= htmlspecialchars($row['email']) ?>" required>
    <input name="nit" value="<?= htmlspecialchars($row['nit']) ?>" required>
    <input name="direccion" value="<?= htmlspecialchars($row['direccion']) ?>" required>
    <input name="password" value="<?= htmlspecialchars($row['password']) ?>" required>
    <button type="submit">Guardar</button>
</form>
<p><a href="../../pages/admin/aerolineas.php">Volver</a></p>
</body>
</html>
