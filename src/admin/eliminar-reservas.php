<?php
include "../../conexion.php";
$id = $_GET['idReserva'] ?? null;
if (!$id) {
    header('Location: ../../pages/admin/reservas.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $del = "DELETE FROM reservas WHERE idReserva = ?";
    if ($stmt = mysqli_prepare($conexion, $del)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
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
    <title>Eliminar Reserva</title>
</head>

<body>
    <h2>Eliminar Reserva</h2>
    <p>¿Confirma eliminar id <?= htmlspecialchars($id) ?>?</p>
    <form method="post"><button type="submit">Sí</button><a href="../../pages/admin/reservas.php">Cancelar</a></form>
</body>

</html>