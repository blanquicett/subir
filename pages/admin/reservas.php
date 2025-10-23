<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idReserva, condicionInfante, iva, descuento, subtotal, idDisponibilidad, idPasajeros FROM reservas";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

// obtener disponibilidades y pasajeros para selects
$disps = [];
$dRes = mysqli_query($conexion, "SELECT idDisponibilidad, fecha, origen, destino FROM disponibilidad ORDER BY fecha");
if ($dRes && mysqli_num_rows($dRes) > 0) while ($d = mysqli_fetch_assoc($dRes)) $disps[] = $d;
$pasajeros = [];
$pRes = mysqli_query($conexion, "SELECT idPasajero, nombres, primerApellido FROM pasajeros ORDER BY nombres");
if ($pRes && mysqli_num_rows($pRes) > 0) while ($p = mysqli_fetch_assoc($pRes)) $pasajeros[] = $p;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reservas</title>
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
        <h1>Reservas</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Infante</th>
                            <th>IVA</th>
                            <th>Descuento</th>
                            <th>Subtotal</th>
                            <th>Disponibilidad</th>
                            <th>Pasajero</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['idReserva']) ?></td>
                                    <td><?= htmlspecialchars($it['condicionInfante']) ?></td>
                                    <td><?= htmlspecialchars($it['iva']) ?></td>
                                    <td><?= htmlspecialchars($it['descuento']) ?></td>
                                    <td><?= htmlspecialchars($it['subtotal']) ?></td>
                                    <td><?= htmlspecialchars($it['idDisponibilidad']) ?></td>
                                    <td><?= htmlspecialchars($it['idPasajeros']) ?></td>
                                    <td>
                                        <a href="../../src/admin/editar-reservas.php?idReserva=<?= $it['idReserva'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="../../src/admin/eliminar-reservas.php?idReserva=<?= $it['idReserva'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?><tr>
                                <td colspan="8">No hay reservas</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="mt-4">Crear Reserva</h3>
        <form action="../../src/admin/crear-reservas.php" method="post" class="row g-2">
            <div class="col-2"><input name="condicionInfante" type="number" class="form-control" placeholder="Infante (0/1)"></div>
            <div class="col-2"><input name="iva" type="number" step="0.01" class="form-control" placeholder="IVA"></div>
            <div class="col-2"><input name="descuento" type="number" step="0.01" class="form-control" placeholder="Descuento"></div>
            <div class="col-2"><input name="subtotal" type="number" step="0.01" class="form-control" placeholder="Subtotal"></div>
            <div class="col-3">
                <select name="idDisponibilidad" class="form-control">
                    <option value="">Seleccione disponibilidad</option>
                    <?php foreach ($disps as $d): ?>
                        <option value="<?= htmlspecialchars($d['idDisponibilidad']) ?>"><?= htmlspecialchars($d['fecha'].' - '.$d['origen'].' → '.$d['destino']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-3">
                <select name="idPasajeros" class="form-control">
                    <option value="">Seleccione pasajero</option>
                    <?php foreach ($pasajeros as $p): ?>
                        <option value="<?= htmlspecialchars($p['idPasajero']) ?>"><?= htmlspecialchars($p['nombres'].' '.$p['primerApellido']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-2"><button class="btn btn-primary">Crear</button></div>
        </form>
    </div>
</body>

</html>