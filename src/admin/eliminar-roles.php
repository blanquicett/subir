<?php
include "../../conexion.php";

$id = $_GET['idRol'] ?? null;
if (!$id){
    header('Location: ../../pages/admin/roles.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $del = "DELETE FROM roles WHERE idRol = ?";
    if ($stmt = mysqli_prepare($conexion, $del)){
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ../../pages/admin/roles.php');
        exit;
    } else { $error = 'Error'; }
}

?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Eliminar Rol</title></head>
<body>
<h2>Eliminar Rol</h2>
<p>¿Confirma eliminar el rol con ID <?= htmlspecialchars($id) ?> ?</p>
<?php if (!empty($error)) echo '<p>'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <button type="submit">Sí, eliminar</button>
    <a href="../../pages/admin/roles.php">Cancelar</a>
</form>
</body>
</html>
