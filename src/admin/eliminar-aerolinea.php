<?php
include "../../conexion.php";

$id = $_GET['idAerolinea'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/aerolineas.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $del = "DELETE FROM aerolinea WHERE idAerolinea = ?";
    if ($stmt = mysqli_prepare($conexion, $del)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header('Location: ../../pages/admin/aerolineas.php');
        exit;
    } else {
        $error = 'Error al preparar eliminación';
    }
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Eliminar Aerolinea</title>
</head>
<body>
<h2>Eliminar Aerolínea</h2>
<p>¿Confirma eliminar la aerolínea con ID <?= htmlspecialchars($id) ?> ?</p>
<?php if (!empty($error)) echo '<p>'.htmlspecialchars($error).'</p>'; ?>
<form method="post">
    <button type="submit">Sí, eliminar</button>
    <a href="../../pages/admin/aerolineas.php">Cancelar</a>
</form>
</body>
</html>
