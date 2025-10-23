<?php
include "../../conexion.php";
$items = [];
$sql = "SELECT idPasajero, nombres, primerApellido, segundoApellido, fechNacimiento, genero, tipoDocumento, documento, celular, email, idRol FROM pasajeros";
$res = mysqli_query($conexion, $sql);
if ($res && mysqli_num_rows($res) > 0) while ($row = mysqli_fetch_assoc($res)) $items[] = $row;

// Obtener roles para el select
$roles = [];
$rSql = "SELECT idRol, nombreRol FROM roles ORDER BY nombreRol";
$rRes = mysqli_query($conexion, $rSql);
if ($rRes && mysqli_num_rows($rRes) > 0) while ($r = mysqli_fetch_assoc($rRes)) $roles[] = $r;
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Pasajeros</title>
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
        <h1>Pasajeros</h1>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Documento</th>
                            <th>Celular</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): foreach ($items as $it): ?>
                                <tr>
                                    <td><?= htmlspecialchars($it['idPasajero']) ?></td>
                                    <td><?= htmlspecialchars($it['nombres']) ?></td>
                                    <td><?= htmlspecialchars($it['primerApellido'] . ' ' . $it['segundoApellido']) ?></td>
                                    <td><?= htmlspecialchars($it['tipoDocumento'] . ' ' . $it['documento']) ?></td>
                                    <td><?= htmlspecialchars($it['celular']) ?></td>
                                    <td><?= htmlspecialchars($it['email']) ?></td>
                                    <td><?= htmlspecialchars($it['idRol']) ?></td>
                                    <td>
                                        <a href="../../src/admin/editar-pasajeros.php?idPasajero=<?= $it['idPasajero'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                        <a href="../../src/admin/eliminar-pasajeros.php?idPasajero=<?= $it['idPasajero'] ?>" class="btn btn-sm btn-danger ms-2">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?><tr>
                                <td colspan="8">No hay pasajeros</td>
                            </tr><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="mt-4">Crear Pasajero</h3>
        <form action="../../src/admin/crear-pasajeros.php" method="post" class="row g-2">
            <div class="col-4"><input name="nombres" class="form-control" required placeholder="Nombres"></div>
            <div class="col-4"><input name="primerApellido" class="form-control" required placeholder="Primer Apellido"></div>
            <div class="col-4"><input name="segundoApellido" class="form-control" placeholder="Segundo Apellido"></div>
            <div class="col-3"><input name="fechNacimiento" type="date" class="form-control" required></div>
            <div class="col-3">
                <select name="genero" class="form-control" required>
                    <option value="">Seleccione género</option>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                    <option value="Otro">Otro</option>
                </select>
             </div>
            <div class="col-3"><input name="tipoDocumento" class="form-control" required placeholder="Tipo Documento"></div>
            <div class="col-3"><input name="documento" class="form-control" required placeholder="Documento"></div>
            <div class="col-4"><input name="celular" class="form-control" required placeholder="Celular"></div>
            <div class="col-4"><input name="email" type="email" class="form-control" required placeholder="Email"></div>
            <div class="col-4"><input name="password" type="password" class="form-control" required placeholder="Password"></div>
            <div class="col-4">
                <select name="idRol" class="form-control" required>
                    <option value="">Seleccione rol</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= htmlspecialchars($rol['idRol']) ?>"><?= htmlspecialchars($rol['nombreRol']) ?></option>
                    <?php endforeach; ?>
                </select>
             </div>
            <div class="col-2"><button class="btn btn-primary">Crear</button></div>
        </form>

    </div>
</body>

</html>