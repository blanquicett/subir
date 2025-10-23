<?php
session_start();
include("../../conexion.php");

// Verificar si tenemos los datos necesarios en la sesión
if (!isset($_SESSION['datos_reserva'])) {
    header('Location: reserva.php');
    exit;
}

// Obtener asientos seleccionados
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asientosSeleccionados'])) {
    $asientosSeleccionados = json_decode($_POST['asientosSeleccionados'], true);
    $_SESSION['datos_reserva']['asientos'] = $asientosSeleccionados;
} elseif (!isset($_SESSION['datos_reserva']['asientos'])) {
    header('Location: seleccionar_asiento.php');
    exit;
}

// Obtener información completa del vuelo
$sql = "SELECT d.*, av.nombreAvion, a.nombreAerolinea, ma.capacidad, ma.modelo
        FROM disponibilidad d
        INNER JOIN aviones av ON d.idAvion = av.idAvion
        INNER JOIN aerolinea a ON av.idAerolinea = a.idAerolinea
        INNER JOIN modeloaviones ma ON av.idModeloA = ma.idModeloA
        WHERE d.idDisponibilidad = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['datos_reserva']['idVuelo']);
mysqli_stmt_execute($stmt);
$vuelo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

// Calcular precio base del vuelo (esto debería venir de la BD, aquí es un ejemplo)
$precioBase = 150000; // Precio base por asiento

// Calcular totales
$cantidadPasajeros = count($_SESSION['datos_reserva']['asientos']);
$subtotal = $precioBase * $cantidadPasajeros;
$iva = $subtotal * 0.19; // IVA 19%
$total = $subtotal + $iva;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/confirmar_reserva.css">
    
</head>
<body class="bg-light">
    <div class="container py-5">
        <!-- Pasos del proceso -->
        <div class="progress-steps">
            <div class="step completed">1. Búsqueda</div>
            <div class="step completed">2. Datos de Pasajeros</div>
            <div class="step completed">3. Selección de Asientos</div>
            <div class="step active">4. Confirmación</div>
        </div>

        <h1 class="mb-4"><i class="fas fa-check-circle text-success"></i> Confirmación de Reserva</h1>

        <!-- Información del vuelo -->
        <div class="flight-info">
            <h4 class="mb-3"><i class="fas fa-plane"></i> Información del Vuelo</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Aerolínea:</strong> <?= htmlspecialchars($vuelo['nombreAerolinea']) ?></p>
                    <p><strong>Avión:</strong> <?= htmlspecialchars($vuelo['nombreAvion']) ?> (Modelo <?= htmlspecialchars($vuelo['modelo']) ?>)</p>
                    <p><strong>Origen:</strong> <?= htmlspecialchars($vuelo['origen']) ?></p>
                    <p><strong>Destino:</strong> <?= htmlspecialchars($vuelo['destino']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($vuelo['fecha'])) ?></p>
                    <?php if ($vuelo['fechaRegreso']): ?>
                    <p><strong>Fecha de Regreso:</strong> <?= date('d/m/Y', strtotime($vuelo['fechaRegreso'])) ?></p>
                    <?php endif; ?>
                    <p><strong>Hora de Salida:</strong> <?= htmlspecialchars($vuelo['horaSalida']) ?></p>
                    <p><strong>Hora de Llegada:</strong> <?= htmlspecialchars($vuelo['horaLlegada']) ?></p>
                </div>
            </div>
        </div>

        <!-- Información de los pasajeros -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-users"></i> Información de Pasajeros</h4>
                
                <?php 
                $indicePasajero = 0;
                foreach ($_SESSION['datos_reserva']['pasajeros'] as $index => $pasajero): 
                    if (isset($pasajero['nombres']) && !empty($pasajero['nombres'])):
                        $asiento = $_SESSION['datos_reserva']['asientos'][$indicePasajero] ?? 'N/A';
                ?>
                    <div class="passenger-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="mb-0">
                                <i class="fas fa-user info-icon"></i>
                                Pasajero <?= $indicePasajero + 1 ?>: 
                                <?= htmlspecialchars($pasajero['nombres']) ?> 
                                <?= htmlspecialchars($pasajero['primerApellido']) ?> 
                                <?= htmlspecialchars($pasajero['segundoApellido'] ?? '') ?>
                            </h5>
                            <span class="seat-badge">
                                <i class="fas fa-chair"></i> Asiento <?= htmlspecialchars($asiento) ?>
                            </span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Tipo de Documento:</strong> <?= htmlspecialchars($pasajero['tipoDocumento']) ?></p>
                                <p class="mb-2"><strong>Número de Documento:</strong> <?= htmlspecialchars($pasajero['documento']) ?></p>
                                <p class="mb-2"><strong>Fecha de Nacimiento:</strong> <?= date('d/m/Y', strtotime($pasajero['fechaNacimiento'])) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-2"><strong>Género:</strong> <?= htmlspecialchars($pasajero['genero']) ?></p>
                                <p class="mb-2"><strong>Email:</strong> <?= htmlspecialchars($pasajero['email']) ?></p>
                                <?php if (!empty($pasajero['celular'])): ?>
                                <p class="mb-2"><strong>Celular:</strong> <?= htmlspecialchars($pasajero['celular']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Información del infante si existe -->
                        <?php if (isset($pasajero['infante']) && !empty($pasajero['infante']['nombres'])): ?>
                        <div class="mt-3 p-3" style="background-color: #fff3cd; border-radius: 5px; border-left: 4px solid #ffc107;">
                            <h6><i class="fas fa-baby info-icon"></i> Viaja con Infante <span class="infant-badge">Menor de 2 años</span></h6>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Nombre:</strong> <?= htmlspecialchars($pasajero['infante']['nombres']) ?> 
                                        <?= htmlspecialchars($pasajero['infante']['primerApellido']) ?> 
                                        <?= htmlspecialchars($pasajero['infante']['segundoApellido'] ?? '') ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Fecha de Nacimiento:</strong> <?= date('d/m/Y', strtotime($pasajero['infante']['fechaNacimiento'])) ?></p>
                                    <p class="mb-1"><strong>Documento:</strong> <?= htmlspecialchars($pasajero['infante']['documento']) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php 
                    $indicePasajero++;
                    endif;
                endforeach; 
                ?>
            </div>
        </div>

        <!-- Resumen de costos -->
        <div class="card mb-4">
            <div class="card-body">
                <h4 class="card-title mb-4"><i class="fas fa-dollar-sign"></i> Resumen de Costos</h4>
                
                <div class="summary-row">
                    <span>Subtotal (<?= $cantidadPasajeros ?> pasajero<?= $cantidadPasajeros > 1 ? 's' : '' ?>):</span>
                    <span>$<?= number_format($subtotal, 0, ',', '.') ?> COP</span>
                </div>
                
                <div class="summary-row">
                    <span>IVA (19%):</span>
                    <span>$<?= number_format($iva, 0, ',', '.') ?> COP</span>
                </div>
                
                <div class="summary-row total">
                    <span>Total a Pagar:</span>
                    <span class="text-success">$<?= number_format($total, 0, ',', '.') ?> COP</span>
                </div>
            </div>
        </div>

        <!-- Botones de acción -->
        <form action="procesar_pago.php" method="GET">
            <div class="d-flex justify-content-between align-items-center">
                <a href="seleccionar_asiento.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Modificar Asientos
                </a>
                <div>
                    <a href="../vuelos.php" class="btn btn-outline-danger me-2">
                        <i class="fas fa-times"></i> Cancelar Reserva
                    </a>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-check"></i> Ir a Pago
                    </button>
                </div>
            </div>
        </form>

        <!-- Información adicional -->
        <div class="alert alert-info mt-4" role="alert">
            <i class="fas fa-info-circle"></i> <strong>Información importante:</strong>
            <ul class="mb-0 mt-2">
                <li>Los infantes (menores de 2 años) no ocupan asiento y viajan en brazos del adulto responsable.</li>
                <li>Por favor, revise cuidadosamente todos los datos antes de confirmar.</li>
                <li>Una vez confirmada la reserva, se generará un código de reserva que recibirá por correo electrónico.</li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>