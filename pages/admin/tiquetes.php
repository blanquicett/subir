<?php
include "../../conexion.php";
$tiquetes = [];
$sql = "SELECT idTiquete, idPasajero, idVuelo, asiento, precio, fechaCompra, codigoReserva, fecha, totalPagar, idReserva FROM tiquetes";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) {
        $tiquetes[] = $row;
    }
}

$reservas = [];
$rRes = mysqli_query($conexion, "SELECT idReserva, subtotal FROM reservas ORDER BY idReserva");
if ($rRes && mysqli_num_rows($rRes) > 0) {
    while ($r = mysqli_fetch_assoc($rRes)) {
        $reservas[] = $r;
    }
}
$pasajeros = [];
$pRes = mysqli_query($conexion, "SELECT idPasajero, nombres FROM pasajeros ORDER BY idPasajero");
if ($pRes && mysqli_num_rows($pRes) > 0) {
    while ($p = mysqli_fetch_assoc($pRes)) {
        $pasajeros[] = $p;
    }
}
$vuelos = [];
$vRes = mysqli_query($conexion, "SELECT idAvion, destino FROM disponibilidad ORDER BY idAvion");
if ($vRes && mysqli_num_rows($vRes) > 0) {
    while ($v = mysqli_fetch_assoc($vRes)) {
        $vuelos[] = $v;
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tiquetes - SENASOFT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/admin.css">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg header">
        <div class="container">
            <div class="collapse navbar-collapse" id="navmenu">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="roles.php">Roles</a></li>
                    <li class="nav-item"><a class="nav-link" href="aerolineas.php">Aerolíneas</a></li>
                    <li class="nav-item"><a class="nav-link" href="modeloAviones.php">Modelos</a></li>
                    <li class="nav-item"><a class="nav-link" href="aviones.php">Aviones</a></li>
                    <li class="nav-item"><a class="nav-link" href="pasajeros.php">Pasajeros</a></li>
                    <li class="nav-item"><a class="nav-link" href="disponibilidad.php">Disponibilidad</a></li>
                    <li class="nav-item"><a class="nav-link" href="reservas.php">Reservas</a></li>
                    <li class="nav-item"><a class="nav-link" href="tiquetes.php">Tiquetes</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">

        <div class="text-center mb-4">
            <h1 class="fw-bold text-primary">Gestión de Tiquetes</h1>
            <p class="text-muted">Consulta, edita o crea nuevos tiquetes</p>
        </div>
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Pasajero</th>
                                <th>Vuelo</th>
                                <th>Asiento</th>
                                <th>Precio</th>
                                <th>Fecha Compra</th>
                                <th>Código Reserva</th>
                                <th>Fecha</th>
                                <th>Total Pagar</th>
                                <th>ID Reserva</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($tiquetes)): ?>
                                <?php foreach ($tiquetes as $t): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($t['idTiquete']) ?></td>
                                        <td><?= htmlspecialchars($t['idPasajero']) ?></td>
                                        <td><?= htmlspecialchars($t['idVuelo']) ?></td>
                                        <td><?= htmlspecialchars($t['asiento']) ?></td>
                                        <td>$<?= number_format($t['precio'], 2) ?></td>
                                        <td><?= htmlspecialchars($t['fechaCompra']) ?></td>
                                        <td><?= htmlspecialchars($t['codigoReserva']) ?></td>
                                        <td><?= htmlspecialchars($t['fecha']) ?></td>
                                        <td>$<?= number_format($t['totalPagar'], 2) ?></td>
                                        <td><?= htmlspecialchars($t['idReserva']) ?></td>
                                        <td>
                                            <a href="../../src/admin/editar-tiquete.php?idTiquete=<?= $t['idTiquete'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                            <a href="../../src/admin/eliminar-tiquete.php?idTiquete=<?= $t['idTiquete'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-muted py-4">No hay tiquetes registrados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h3 class="mb-3 text-primary text-center">Crear nuevo tiquete</h3>
                <form action="../../src/admin/crear-tiquete.php" method="post" class="row g-3 justify-content-center">
                    <div class="col-md-3">
                        <label class="form-label">Pasajero</label>
                        <select name="idPasajero" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($pasajeros as $p): ?>
                                <option value="<?= $p['idPasajero'] ?>"><?= $p['nombres'] ?> (ID <?= $p['idPasajero'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Vuelo</label>
                        <select name="idVuelo" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($vuelos as $v): ?>
                                <option value="<?= $v['idAvion'] ?>">Vuelo #<?= $v['idAvion'] ?> — <?= $v['destino'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Asiento</label>
                        <input type="text" name="asiento" class="form-control" maxlength="5" placeholder="Ej: A12" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Precio</label>
                        <input type="number" name="precio" step="0.01" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Compra</label>
                        <input type="date" name="fechaCompra" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Código Reserva</label>
                        <input type="text" name="codigoReserva" class="form-control" maxlength="30" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fecha Vuelo</label>
                        <input type="date" name="fecha" class="form-control" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Total a Pagar</label>
                        <input type="number" name="totalPagar" step="0.01" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reserva</label>
                        <select name="idReserva" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach ($reservas as $r): ?>
                                <option value="<?= $r['idReserva'] ?>">Reserva #<?= $r['idReserva'] ?> — $<?= number_format($r['subtotal'], 2) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-success px-5 mt-3">Crear Tiquete</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
