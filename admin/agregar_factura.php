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

// Obtener órdenes sin factura
$stmtOrdenes = $conn->prepare("
    SELECT o.id_orden, v.marca, v.modelo, v.num_placa, 
           p.nombre + ' ' + p.apellido1 AS cliente
    FROM Ordenes o
    INNER JOIN Vehiculos v ON o.id_vehiculo = v.id_vehiculo
    INNER JOIN Personas p ON v.id_persona = p.id_persona
    WHERE o.activo = 1 AND o.estado = 'Finalizado'
    AND NOT EXISTS (SELECT 1 FROM Facturas f WHERE f.id_orden = o.id_orden AND f.activo = 1)
");
$stmtOrdenes->execute();
$ordenes = $stmtOrdenes->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("EXEC sp_AgregarFactura ?, ?, ?, ?, ?, ?");
        $stmt->execute([
            $_POST['id_orden'],
            $_SESSION['id_persona'],
            $_POST['fecha_emision'],
            $_POST['monto_total'],
            $_POST['metodo_pago'],
            $_POST['estado_pago']
        ]);
        
        $_SESSION['mensaje'] = [
            'tipo' => 'success',
            'texto' => 'Factura creada correctamente!'
        ];
        header("Location: facturas.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al crear la factura: " . $e->getMessage();
    }
}
?>

<div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Nueva Factura</h2>
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
                        <select class="form-select" name="id_orden" required>
                            <option value="">Seleccione una orden</option>
                            <?php foreach ($ordenes as $orden): ?>
                                <option value="<?= $orden['id_orden'] ?>">
                                    #<?= $orden['id_orden'] ?> - <?= htmlspecialchars($orden['cliente']) ?> 
                                    (<?= htmlspecialchars($orden['marca'] . ' ' . $orden['modelo'] . ' - ' . $orden['num_placa']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de Emisión</label>
                        <input type="date" class="form-control" name="fecha_emision" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Monto Total</label>
                        <input type="number" step="0.01" class="form-control" name="monto_total" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <select class="form-select" name="metodo_pago" required>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado de Pago</label>
                        <select class="form-select" name="estado_pago" required>
                            <option value="Pendiente">Pendiente</option>
                            <option value="Pagado">Pagado</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Guardar Factura</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>