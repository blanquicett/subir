<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idModeloA, modelo, capacidad FROM modeloaviones";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) $items[] = $row;
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Modelo Aviones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/register.css">
</head>
<nav class="navbar navbar-expand-lg header">
    <div class="container">
        <div class="collapse navbar-collapse" id="navmenu">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="roles.php">Roles</a></li>
                <li class="nav-item"><a class="nav-link" href="aerolineas.php">Aerolineas</a></li>
                <li class="nav-item"><a class="nav-link" href="modeloAviones.php">Modelo aviones</a></li>
                <li class="nav-item"><a class="nav-link" href="aviones.php">Aviones</a></li>
                <li class="nav-item"><a class="nav-link" href="pasajeros.php">Pasajeros</a></li>
                <li class="nav-item"><a class="nav-link" href="disponibilidad.php">Disponibilidad</a></li>
                <li class="nav-item"><a class="nav-link" href="reservas.php">Reservas</a></li>
                <li class="nav-item"><a class="nav-link" href="tiquites.php">Tiquetes</a></li>
            </ul>
        </div>
    </div>
</nav>
<body class="bg-light">
    <div class="container py-5">
        <h1>Modelos de Aviones</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Modelo</th>
                            <th>Capacidad</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['idModeloA']) ?></td>
                                    <td><?= htmlspecialchars($it['modelo']) ?></td>
                                    <td><?= htmlspecialchars($it['capacidad']) ?></td>
                                    <td>
                                        <a href="../../src/admin/editar-modeloaviones.php?idModeloA=<?= $it['idModeloA'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="../../src/admin/eliminar-modeloaviones.php?idModeloA=<?= $it['idModeloA'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="4">No hay registros</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h3 class="mt-4">Crear Modelo</h3>
        <form action="../../src/admin/crear-modeloaviones.php" method="post" class="row g-2">
            <div class="col-6"><input name="modelo" class="form-control" required placeholder="Modelo"></div>
            <div class="col-3"><input name="capacidad" type="number" class="form-control" required placeholder="Capacidad"></div>
            <div class="col-3"><button class="btn btn-primary">Crear</button></div>
        </form>

    </div>
</body>

</html>