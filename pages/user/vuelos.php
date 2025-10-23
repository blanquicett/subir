<?php
include("../../conexion.php");
session_start();

// Simulamos un pasajero logueado
$idPasajero = $_SESSION['idPasajero'] ?? 1;

// Obtener todas las disponibilidades
$disponibilidades = $conexion->query("SELECT * FROM disponibilidad");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Compra de Tiquete</title>
  <link rel="stylesheet" href="../../css/vuelos.css">
</head>
<body>
  <nav>
    <div class="nav-links">
      <a href="user.php">Inicio</a>
      <a href="disponibilidad.php">Disponibilidad</a>
      <a href="tiquete.php">Tiquetes</a>
    </div>
  </nav>

  <div class="form-container">
    <h2>Seleccionar vuelo disponible</h2>

    <form method="POST" action="insertar_tiquete.php">
      <label for="idDisponibilidad">Selecciona un vuelo:</label>
      <select name="idDisponibilidad" id="idDisponibilidad" required>
        <option value="">-- Selecciona una opción --</option>
        <?php while ($d = $disponibilidades->fetch_assoc()): ?>
          <option value="<?= $d['idDisponibilidad'] ?>">
            <?= $d['origen'] ?> → <?= $d['destino'] ?> | Asiento: <?= $d['asiento'] ?> |
            Salida: <?= $d['horaSalida'] ?> | Llegada: <?= $d['horaLlegada'] ?> (<?= $d['fecha'] ?>)
          </option>
        <?php endwhile; ?>
      </select>

      <label for="tipoPago">Método de Pago:</label>
      <select name="tipoPago" id="tipoPago" required>
        <option value="">-- Selecciona --</option>
        <option value="Tarjeta">Tarjeta</option>
        <option value="Efectivo">Efectivo</option>
        <option value="Transferencia">Transferencia</option>
      </select>

      <label for="precio">Total a Pagar:</label>
      <input type="number" name="precio" step="0.01" required>

      <input type="hidden" name="idPasajero" value="<?= $idPasajero ?>">

      <button type="submit">Generar Tiquete</button>
    </form>
  </div>
</body>
</html>
