<?php
include_once "menu_lateral.php";
include_once "funciones.php";

$id_factura = $_GET['id_factura'] ?? null;

if (!$id_factura) {
    die("Factura no especificada.");
}

// Consulta detalles de la factura
$stmt = $conn->prepare("SELECT f.*, p.nombre + ' ' + p.apellido1 AS cliente 
                        FROM Facturas f
                        INNER JOIN Personas p ON f.id_persona = p.id_persona
                        WHERE f.id_factura = ?");
$stmt->execute([$id_factura]);
$factura = $stmt->fetch();

if (!$factura) {
    die("Factura no encontrada.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $metodo = $_POST['metodo_pago'];
    $monto = $_POST['monto_pagado'];
    $fecha = date('Y-m-d');
    $id_cliente = $factura['id_persona']; // el cliente que pagó

    // Insertar pago
    $stmt = $conn->prepare("INSERT INTO Pagos (id_factura, id_persona, fecha_pago, monto_pagado, metodo_pago) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$id_factura, $id_cliente, $fecha, $monto, $metodo]);

    // Actualizar estado de factura a Pagado
    $conn->prepare("UPDATE Facturas SET estado_pago = 'Pagado' WHERE id_factura = ?")
         ->execute([$id_factura]);

    header("Location: facturas.php?exito_pago=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h4 class="mb-4">Registrar Pago</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Factura</label>
                        <input type="text" class="form-control" 
                            value="#<?= $factura['id_factura'] ?> - <?= htmlspecialchars($factura['cliente']) ?>" 
                            readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Monto a Pagar</label>
                        <input type="number" step="0.01" name="monto_pagado" class="form-control" 
                            value="<?= $factura['monto_total'] ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <select class="form-select" name="metodo_pago" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-cash-coin"></i> Registrar Pago
                    </button>
                    <a href="facturas.php" class="btn btn-secondary ms-2">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
