<?php
session_start();
include("../../conexion.php");

// Verificar si tenemos los datos necesarios en la sesión
if (!isset($_SESSION['datos_reserva']) || !isset($_SESSION['datos_reserva']['asientos'])) {
    header('Location: reserva.php');
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

// Calcular totales
$precioBase = 150000;
$cantidadPasajeros = count($_SESSION['datos_reserva']['asientos']);
$subtotal = $precioBase * $cantidadPasajeros;
$iva = $subtotal * 0.19;
$total = $subtotal + $iva;

// Procesar el pago si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['metodoPago'])) {
    // Aquí iría la lógica de procesamiento real del pago
    // Por ahora simulamos un pago exitoso

    mysqli_begin_transaction($conexion);

    try {
        // Insertar pasajeros y crear reservas
        $codigoReserva = strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));

        foreach ($_SESSION['datos_reserva']['pasajeros'] as $index => $pasajero) {
            if (!isset($pasajero['nombres']) || empty($pasajero['nombres'])) continue;

            // Verificar si el pasajero ya existe
            $sqlCheck = "SELECT idPasajero FROM pasajeros WHERE email = ? AND documento = ?";
            $stmtCheck = mysqli_prepare($conexion, $sqlCheck);
            mysqli_stmt_bind_param($stmtCheck, "si", $pasajero['email'], $pasajero['documento']);
            mysqli_stmt_execute($stmtCheck);
            $resultCheck = mysqli_stmt_get_result($stmtCheck);

            if ($row = mysqli_fetch_assoc($resultCheck)) {
                $idPasajero = $row['idPasajero'];
            } else {
                // Insertar nuevo pasajero
                $sqlPasajero = "INSERT INTO pasajeros (nombres, primerApellido, segundoApellido, fechNacimiento, genero, tipoDocumento, documento, celular, email, password, idRol) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
                $stmtPasajero = mysqli_prepare($conexion, $sqlPasajero);
                $passwordDefault = password_hash('default123', PASSWORD_DEFAULT);
                mysqli_stmt_bind_param(
                    $stmtPasajero,
                    "ssssssssss",
                    $pasajero['nombres'],
                    $pasajero['primerApellido'],
                    $pasajero['segundoApellido'],
                    $pasajero['fechaNacimiento'],
                    $pasajero['genero'],
                    $pasajero['tipoDocumento'],
                    $pasajero['documento'],
                    $pasajero['celular'],
                    $pasajero['email'],
                    $passwordDefault
                );
                mysqli_stmt_execute($stmtPasajero);
                $idPasajero = mysqli_insert_id($conexion);
            }

            // Crear reserva
            $condicionInfante = isset($pasajero['infante']) && !empty($pasajero['infante']['nombres']) ? 1 : 0;
            $ivaDecimal = 0.19;
            $descuentoDecimal = 0.0;

            $sqlReserva = "INSERT INTO reservas (condicionInfante, iva, descuento, subtotal, idDisponibilidad, idPasajeros) 
                          VALUES (?, ?, ?, ?, ?, ?)";
            $stmtReserva = mysqli_prepare($conexion, $sqlReserva);
            mysqli_stmt_bind_param(
                $stmtReserva,
                "idddii",
                $condicionInfante,
                $ivaDecimal,
                $descuentoDecimal,
                $precioBase,
                $_SESSION['datos_reserva']['idVuelo'],
                $idPasajero
            );
            mysqli_stmt_execute($stmtReserva);
            $idReserva = mysqli_insert_id($conexion);

            // Obtener el índice del pasajero en la lista de asientos
            $indiceAsiento = 0;
            $contador = 0;
            foreach ($_SESSION['datos_reserva']['pasajeros'] as $idx => $p) {
                if (isset($p['nombres']) && !empty($p['nombres'])) {
                    if ($idx == $index) {
                        $indiceAsiento = $contador;
                        break;
                    }
                    $contador++;
                }
            }

            $asiento = $_SESSION['datos_reserva']['asientos'][$indiceAsiento] ?? 'N/A';

            // Crear tiquete
            $sqlTiquete = "INSERT INTO tiquetes (idPasajero, idVuelo, asiento, precio, fechaCompra, codigoReserva, fecha, totalPagar, idReserva) 
                          VALUES (?, ?, ?, ?, CURDATE(), ?, CURDATE(), ?, ?)";
            $stmtTiquete = mysqli_prepare($conexion, $sqlTiquete);
            $totalPagar = $precioBase + ($precioBase * 0.19);

            mysqli_stmt_bind_param(
                $stmtTiquete,
                "iisdsdi",
                $idPasajero,
                $_SESSION['datos_reserva']['idVuelo'],
                $asiento,
                $precioBase,
                $codigoReserva,
                $totalPagar,
                $idReserva
            );
            mysqli_stmt_execute($stmtTiquete);

            if ($index == 0) {
                $_SESSION['ultimo_tiquete'] = mysqli_insert_id($conexion);
                $_SESSION['codigo_reserva'] = $codigoReserva;
            }
        }

        mysqli_commit($conexion);

        // Redirigir a página de éxito
        header('Location: pago_exitoso.php');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conexion);
        $error = "Error al procesar la reserva: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/procesar_pagos.css">
</head>

<body class="bg-light">
    <div class="container py-5">
        <h1 class="mb-4"><i class="fas fa-credit-card"></i> Procesar Pago</h1>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <form action="" method="POST" id="paymentForm">
                    <h3 class="mb-4">Seleccione un Método de Pago</h3>

                    <!-- Tarjeta de Crédito/Débito -->
                    <div class="payment-method" onclick="selectPayment('tarjeta')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="metodoPago" value="tarjeta" id="metodo_tarjeta" required>
                            <i class="fas fa-credit-card payment-icon text-primary ms-3"></i>
                            <div>
                                <h5 class="mb-0">Tarjeta de Crédito/Débito</h5>
                                <small class="text-muted">Visa, Mastercard, American Express</small>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario de tarjeta -->
                    <div id="tarjetaForm" class="card-form">
                        <div class="credit-card-visual mb-4">
                            <div class="chip"></div>
                            <div class="card-number mb-3" style="font-size: 1.3rem; letter-spacing: 3px;">
                                <span id="displayCardNumber">•••• •••• •••• ••••</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <small style="opacity: 0.7;">TITULAR</small>
                                    <div id="displayCardName">NOMBRE APELLIDO</div>
                                </div>
                                <div>
                                    <small style="opacity: 0.7;">EXPIRA</small>
                                    <div id="displayExpiry">MM/YY</div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Número de Tarjeta</label>
                                <input type="text" class="form-control" id="cardNumber" maxlength="19" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nombre del Titular</label>
                                <input type="text" class="form-control" id="cardName" placeholder="Como aparece en la tarjeta">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Expiración</label>
                                <input type="text" class="form-control" id="cardExpiry" maxlength="5" placeholder="MM/YY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" id="cardCVV" maxlength="4" placeholder="123">
                            </div>
                        </div>
                    </div>

                    <!-- PSE -->
                    <div class="payment-method" onclick="selectPayment('pse')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="metodoPago" value="pse" id="metodo_pse" required>
                            <i class="fas fa-university payment-icon text-success ms-3"></i>
                            <div>
                                <h5 class="mb-0">PSE</h5>
                                <small class="text-muted">Pago seguro en línea</small>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario PSE -->
                    <div id="pseForm" class="card-form">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Seleccione su Banco</label>
                                <select class="form-select">
                                    <option value="">Seleccione...</option>
                                    <option value="bancolombia">Bancolombia</option>
                                    <option value="davivienda">Davivienda</option>
                                    <option value="bbva">BBVA</option>
                                    <option value="banco_bogota">Banco de Bogotá</option>
                                    <option value="popular">Banco Popular</option>
                                    <option value="occidente">Banco de Occidente</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tipo de Persona</label>
                                <select class="form-select">
                                    <option value="natural">Persona Natural</option>
                                    <option value="juridica">Persona Jurídica</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Nequi -->
                    <div class="payment-method" onclick="selectPayment('nequi')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="metodoPago" value="nequi" id="metodo_nequi" required>
                            <i class="fas fa-mobile-alt payment-icon text-danger ms-3"></i>
                            <div>
                                <h5 class="mb-0">Nequi</h5>
                                <small class="text-muted">Pago con tu celular</small>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario Nequi -->
                    <div id="nequiForm" class="card-form">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Número de Celular</label>
                                <input type="tel" class="form-control" placeholder="300 123 4567">
                            </div>
                        </div>
                    </div>

                    <!-- Daviplata -->
                    <div class="payment-method" onclick="selectPayment('daviplata')">
                        <div class="d-flex align-items-center">
                            <input type="radio" name="metodoPago" value="daviplata" id="metodo_daviplata" required>
                            <i class="fas fa-wallet payment-icon text-warning ms-3"></i>
                            <div>
                                <h5 class="mb-0">Daviplata</h5>
                                <small class="text-muted">Billetera digital</small>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario Daviplata -->
                    <div id="daviplataForm" class="card-form">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Número de Celular</label>
                                <input type="tel" class="form-control" placeholder="300 123 4567">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="confirmar_reserva.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-lock"></i> Pagar $<?= number_format($total, 0, ',', '.') ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resumen de compra -->
            <div class="col-lg-4">
                <div class="summary-box">
                    <h4 class="mb-4"><i class="fas fa-receipt"></i> Resumen de Compra</h4>

                    <div class="mb-3">
                        <strong>Vuelo:</strong><br>
                        <?= htmlspecialchars($vuelo['origen']) ?> → <?= htmlspecialchars($vuelo['destino']) ?>
                    </div>

                    <div class="mb-3">
                        <strong>Fecha:</strong> <?= date('d/m/Y', strtotime($vuelo['fecha'])) ?>
                    </div>

                    <div class="mb-3">
                        <strong>Pasajeros:</strong> <?= $cantidadPasajeros ?>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>$<?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (19%):</span>
                        <span>$<?= number_format($iva, 0, ',', '.') ?></span>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <strong style="font-size: 1.2rem;">Total:</strong>
                        <strong style="font-size: 1.2rem;" class="text-success">
                            $<?= number_format($total, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <div class="mt-4 p-3" style="background-color: white; border-radius: 8px;">
                        <small class="text-muted">
                            <i class="fas fa-shield-alt text-success"></i>
                            <strong>Pago Seguro</strong><br>
                            Tus datos están protegidos con encriptación SSL
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../js/procesar_pago.js"></script>

</body>

</html>