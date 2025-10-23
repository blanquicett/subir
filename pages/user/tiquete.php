<?php
include("../../conexion.php");
session_start();

// Supongamos que el pasajero ya está en sesión
$idPasajero = $_SESSION['idPasajero'] ?? 1;

// Obtener aviones disponibles
$aviones = $conexion->query("SELECT * FROM aviones");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Seleccionar avión y asiento</title>
  <link rel="stylesheet" href="../../css/tiquetes.css">
</head>
<body>

  <!-- 🔹 NAV SUPERIOR -->
  <nav>
        <div class="nav-links">
            <a href="user.php">Inicio</a>
            <a href="vuelos.php">Reservas</a>
            <a href="disponibilidad.php">Disponibilidad</a>
            <a href="tiquete.php">Tiquetes</a>
        </div>
    </nav>

  <div class="container">
    <h2>Seleccionar avión y asiento</h2>

    <form method="POST" action="insertar_tiquete.php">
      <!-- Avión -->
      <label for="idAvion">Avión disponible:</label>
      <select name="idAvion" id="idAvion" required>
        <option value="">-- Selecciona un avión --</option>
        <?php while ($avion = $aviones->fetch_assoc()): ?>
          <option value="<?= $avion['idAvion'] ?>">
            <?= $avion['modelo'] ?> (Capacidad: <?= $avion['capacidad'] ?>)
          </option>
        <?php endwhile; ?>
      </select>

      <!-- Asientos -->
      <label for="idAsiento">Asiento disponible:</label>
      <select name="idAsiento" id="idAsiento" required>
        <option value="">-- Selecciona un avión primero --</option>
      </select>

      <!-- Precio -->
      <label for="precio">Precio:</label>
      <input type="number" name="precio" id="precio" step="0.01" required>

      <input type="hidden" name="idPasajero" value="<?= $idPasajero ?>">

      <button type="submit">Confirmar Tiquete</button>
    </form>
  </div>
  <script src="../../js/tiquetes.js"></script>

</body>
</html>
