<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idDisponibilidad, fecha, asiento, origen, destino, horaSalida, horaLlegada, idAvion FROM disponibilidad";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

// obtener aviones para select
$aviones = [];
$avRes = mysqli_query($conexion, "SELECT idAvion, nombreAvion FROM aviones ORDER BY nombreAvion");
if ($avRes && mysqli_num_rows($avRes) > 0) while ($a = mysqli_fetch_assoc($avRes)) $aviones[] = $a;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Disponibilidad</title>
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
        <h1>Disponibilidad</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Asiento</th>
                            <th>Origen</th>
                            <th>Destino</th>
                            <th>Salida</th>
                            <th>Llegada</th>
                            <th>Avión</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['idDisponibilidad']) ?></td>
                                    <td><?= htmlspecialchars($it['fecha']) ?></td>
                                    <td><?= htmlspecialchars($it['asiento']) ?></td>
                                    <td><?= htmlspecialchars($it['origen']) ?></td>
                                    <td><?= htmlspecialchars($it['destino']) ?></td>
                                    <td><?= htmlspecialchars($it['horaSalida']) ?></td>
                                    <td><?= htmlspecialchars($it['horaLlegada']) ?></td>
                                    <td><?= htmlspecialchars($it['idAvion']) ?></td>
                                    <td>
                                        <a href="../../src/admin/editar-disponibilidad.php?idDisponibilidad=<?= $it['idDisponibilidad'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="../../src/admin/eliminar-disponibilidad.php?idDisponibilidad=<?= $it['idDisponibilidad'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?><tr>
                                <td colspan="9">No hay disponibilidad</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="mt-4">Crear Disponibilidad</h3>
        <form action="../../src/admin/crear-disponibilidad.php" method="post" class="row g-2">
            <div class="col-3"><input name="fecha" type="date" class="form-control" required></div>
            <div class="col-2"><input name="asiento" class="form-control" required placeholder="Asiento"></div>
            <div class="col-2"><input name="origen" class="form-control" required placeholder="Origen"></div>
            <div class="col-2"><input name="destino" class="form-control" required placeholder="Destino"></div>
            <div class="col-2"><input name="horaSalida" class="form-control" required placeholder="Hora Salida"></div>
            <div class="col-2"><input name="horaLlegada" class="form-control" required placeholder="Hora Llegada"></div>
            <div class="col-2">
                <select name="idAvion" class="form-control" required>
                    <option value="">Seleccione avión</option>
                    <?php foreach ($aviones as $a): ?>
                        <option value="<?= htmlspecialchars($a['idAvion']) ?>"><?= htmlspecialchars($a['nombreAvion']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-2"><button class="btn btn-primary">Crear</button></div>
        </form>
    </div>
</body>

</html>