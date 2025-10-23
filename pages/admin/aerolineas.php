<?php
include "../../conexion.php";
$aerolineas = [];
$sql = "SELECT idAerolinea, nombreAerolinea, email, nit, direccion, password FROM aerolinea";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $aerolineas[] = $row;
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
    <title>Document</title>
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
            <h1 class="fw-bold text-primary">Aerolíneas Registradas</h1>
            <p class="text-muted">Listado actualizado de aerolíneas en el sistema</p>
        </div>

        <div class="card shadow-lg border-0 rounded-6">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center col-4">
                        <thead class="table-primary ">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre Aerolínea</th>
                                <th scope="col">Correo</th>
                                <th scope="col">NIT</th>
                                <th scope="col">Dirección</th>
                                <th scope="col">Contraseña</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($aerolineas)): ?>
                                <?php foreach ($aerolineas as $a): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($a['idAerolinea']) ?></td>
                                        <td class="fw-semibold"><?= htmlspecialchars($a['nombreAerolinea']) ?></td>
                                        <td><?= htmlspecialchars($a['email']) ?></td>
                                        <td><?= htmlspecialchars($a['nit']) ?></td>
                                        <td><?= htmlspecialchars($a['direccion']) ?></td>
                                        <td><?= htmlspecialchars($a['password']) ?></td>
                                        <td>
                                            <a href="../../src/admin/editar-aerolinea.php?idAerolinea=<?= $a['idAerolinea']; ?>" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="../../src/admin/eliminar-aerolinea.php?idAerolinea=<?= $a['idAerolinea']; ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-muted py-4">
                                        <i class="bi bi-exclamation-circle me-2"></i>No hay aerolíneas registradas.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="contenedor">
            <div class="formulario">
                <h2 class="text-center p-4 ">Crear una aerolinea</h2>
                <form action="../../src/admin/crear_aerolinea.php" method="post" class="text-center p-4">
                    <input name="nombreAerolinea" type="text" placeholder="Nombre Aerolinea" required>
                    <input name="email" type="email" placeholder="Correo" required>
                    <input name="nit" type="number" placeholder="Nit" required>
                    <input name="direccion" type="text" placeholder="Dirección" required>
                    <input name="password" type="password" placeholder="Contraseña" required>
                    <div class="col-3 text-center">
                        <button type="submit" class="btn btn-primary w-100">Agregar Aerolinea</button>
                    </div>
                </form>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>