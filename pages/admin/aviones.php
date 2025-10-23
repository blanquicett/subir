<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idAvion, nombreAvion, idModeloA, idAerolinea FROM aviones";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

// obtener modelos y aerolineas para selects
$modelos = [];
$mRes = mysqli_query($conexion, "SELECT idModeloA, modelo FROM modeloaviones ORDER BY modelo");
if ($mRes && mysqli_num_rows($mRes) > 0) while ($m = mysqli_fetch_assoc($mRes)) $modelos[] = $m;
$aerolineas = [];
$aRes = mysqli_query($conexion, "SELECT idAerolinea, nombreAerolinea FROM aerolinea ORDER BY nombreAerolinea");
if ($aRes && mysqli_num_rows($aRes) > 0) while ($a = mysqli_fetch_assoc($aRes)) $aerolineas[] = $a;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Aviones</title>
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
        <h1>Aviones</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Modelo</th>
                            <th>Aerolínea</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['idAvion']) ?></td>
                                    <td><?= htmlspecialchars($it['nombreAvion']) ?></td>
                                    <td><?= htmlspecialchars($it['idModeloA']) ?></td>
                                    <td><?= htmlspecialchars($it['idAerolinea']) ?></td>
                                    <td>
                                        <a href="../../src/admin/editar-aviones.php?idAvion=<?= $it['idAvion'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="../../src/admin/eliminar-aviones.php?idAvion=<?= $it['idAvion'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?><tr>
                                <td colspan="5">No hay aviones</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="mt-4">Crear Avión</h3>
        <form action="../../src/admin/crear-aviones.php" method="post" class="row g-2">
            <div class="col-4"><input name="nombreAvion" class="form-control" required placeholder="Nombre Avión"></div>
            <div class="col-4">
                <select name="idModeloA" class="form-control" required>
                    <option value="">Seleccione modelo</option>
                    <?php foreach ($modelos as $m): ?>
                        <option value="<?= htmlspecialchars($m['idModeloA']) ?>"><?= htmlspecialchars($m['modelo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-4">
                <select name="idAerolinea" class="form-control" required>
                    <option value="">Seleccione aerolínea</option>
                    <?php foreach ($aerolineas as $a): ?>
                        <option value="<?= htmlspecialchars($a['idAerolinea']) ?>"><?= htmlspecialchars($a['nombreAerolinea']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-2 mt-2"><button class="btn btn-primary">Crear</button></div>
        </form>
    </div>
</body>

</html>