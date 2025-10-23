<?php
include "../../conexion.php";

$id = $_GET['idRol'] ?? null;
if (!$id){
    header('Location: ../../pages/admin/roles.php');
    exit;
}

// obtener
$sql = "SELECT idRol, nombreRol FROM roles WHERE idRol = ?";
if ($stmt = mysqli_prepare($conexion, $sql)){
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
} else { die('Error'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nombre = $_POST['nombreRol'] ?? '';
    $update = "UPDATE roles SET nombreRol = ? WHERE idRol = ?";
    if ($stmt = mysqli_prepare($conexion, $update)){
        mysqli_stmt_bind_param($stmt, 'si', $nombre, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/roles.php');
        exit;
    } else { $error = 'Error'; }
}

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Editar Rol</title></head>
<body>
<h2>Editar Rol</h2>
<?php if (!empty($error)) echo '<p>'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <input name="nombreRol" value="<?= htmlspecialchars($row['nombreRol']) ?>" required>
    <button type="submit">Guardar</button>
</form>
<p><a href="../../pages/admin/roles.php">Volver</a></p>
</body>
</html>
