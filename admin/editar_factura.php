<?php
include_once "menu_lateral.php";
include_once "funciones.php";

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = Conexion::ConexionBD();

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['id_persona']) || $_SESSION['rol'] != 'admin') {
    die("Error: Acceso no autorizado");
}

// Obtener ID de la factura a editar
$id_factura = $_GET['id'] ?? null;
if (!$id_factura) {
    die("Error: ID de factura no especificado");
}

// Obtener datos de la factura
$stmtFactura = $conn->prepare("
    SELECT f.*, o.id_orden, v.marca, v.modelo, v.num_placa, 
           p.nombre + ' ' + p.apellido1 AS cliente
    FROM Facturas f
    INNER JOIN Ordenes o ON f.id_orden = o.id_orden
    INNER JOIN Vehiculos v ON o.id_vehiculo = v.id_vehiculo
    INNER JOIN Personas p ON v.id_persona = p.id_persona
    WHERE f.id_factura = ? AND f.activo = 1
");
$stmtFactura->execute([$id_factura]);
$factura = $stmtFactura->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("Error: Factura no encontrada");
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("EXEC sp_ModificarFactura ?, ?, ?");
        $stmt->execute([
            $id_factura,
            $_POST['metodo_pago'],
            $_POST['estado_pago']
        ]);
        
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => 'Factura actualizada correctamente!'
        ];
        header("Location: facturas.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al actualizar la factura: " . $e->getMessage();
    }
}
?>

<div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar Factura #<?= $factura['id_factura'] ?></h2>
        <a href="facturas.php" class="btn btn-outline-secondary">← Volver</a>
    </div>

    <div class="card shadow-sm" style="max-width: 50%;">
        <div class="row g-0">
            <div class="col-12 md-6 px-4 py-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Orden de Servicio</label>
                        <input type="text" class="form-control" 
                            value="#<?= $factura['id_orden'] ?> - <?= htmlspecialchars($factura['cliente']) ?> 
                            (<?= htmlspecialchars($factura['marca'] . ' ' . $factura['modelo'] . ' - ' . $factura['num_placa']) ?>)" 
                            readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" class="form-control" 
                            value="<?= date('Y-m-d', strtotime($factura['fecha_emision'])) ?>" 
                            readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Monto Total</label>
                        <input type="text" class="form-control" 
                            value="₡<?= number_format($factura['monto_total'], 2) ?>" 
                            >
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <select class="form-select" name="metodo_pago" required>
                            <option value="Efectivo" <?= $factura['metodo_pago'] == 'Efectivo' ? 'selected' : '' ?>>Efectivo</option>
                            <option value="Tarjeta" <?= $factura['metodo_pago'] == 'Tarjeta' ? 'selected' : '' ?>>Tarjeta</option>
                            <option value="Transferencia" <?= $factura['metodo_pago'] == 'Transferencia' ? 'selected' : '' ?>>Transferencia</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado de Pago</label>
                        <select class="form-select" name="estado_pago" required>
                            <option value="Pendiente" <?= $factura['estado_pago'] == 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="Pagado" <?= $factura['estado_pago'] == 'Pagado' ? 'selected' : '' ?>>Pagado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Actualizar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>