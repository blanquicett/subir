<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idRol, nombreRol FROM roles";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $items[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/register.css">
    <title>Roles</title>
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
        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">Roles</h1>
            <p class="text-muted">Listado de roles</p>
        </div>
        <div class="card shadow-lg border-0 rounded-6">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>#</th>
                                <th>Nombre Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $it): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($it['idRol']) ?></td>
                                        <td><?= htmlspecialchars($it['nombreRol']) ?></td>
                                        <td>
                                            <a href="../../src/admin/editar-roles.php?idRol=<?= $it['idRol'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="../../src/admin/eliminar-roles.php?idRol=<?= $it['idRol'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3">No hay roles.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <h3>Crear rol</h3>
            <form action="../../src/admin/crear_roles.php" method="post" class="row g-2">
                <div class="col-6">
                    <input name="nombreRol" class="form-control" required placeholder="Nombre del rol">
                </div>
                <div class="col-2">
                    <button class="btn btn-primary">Crear</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>