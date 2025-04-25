<?php
include_once "menu_lateral.php";
include_once "funciones.php";

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



// Verificar si el usuario está logueado
if (!isset($_SESSION['id_persona'])) {
    die("Error: No hay usuario autenticado");
}

// Obtener lista de vehículos con información de clientes
$stmtVehiculos = $conn->prepare("EXEC sp_GetVehiculosActivosConPropietario");
$stmtVehiculos->execute();
$vehiculos = $stmtVehiculos->fetchAll(PDO::FETCH_ASSOC);

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("EXEC sp_AgregarOrden ?, ?, ?, ?, ?, ?, ?");
        $stmt->execute([
            $_POST['id_vehiculo'],
            $_SESSION['id_persona'],
            $_POST['fecha_ingreso'],
            $_POST['diagnostico'],
            'Pendiente',
            (float) str_replace(',', '.', $_POST['costo_servicio']), 
            $_POST['observaciones']
        ]);
        header("Location: ordenes.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al crear la orden: " . $e->getMessage();
    }
}
?>

<div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Agregar nueva orden</h2>
        <a href="ordenes.php" class="btn btn-outline-secondary">← Volver</a>
    </div>

    <div class="card shadow-sm" style="max-width: 50%;">
        <div class="row g-0">
            <div class="col-12 md-6 px-4 py-4">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger mb-4"><?= $error ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Vehículo</label>
                        <select class="form-select" name="id_vehiculo" required>
                            <option value="">Seleccione un vehículo</option>
                            <?php foreach ($vehiculos as $vehiculo): ?>
                                <option value="<?= $vehiculo['id_vehiculo'] ?>">
                                    <?= htmlspecialchars($vehiculo['marca'] . ' ' . $vehiculo['modelo'] . ' - ' . $vehiculo['nombre'] . ' ' . $vehiculo['apellido1']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Fecha de ingreso</label>
                        <input type="date" class="form-control" name="fecha_ingreso" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Diagnóstico</label>
                        <textarea class="form-control" name="diagnostico" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Costo del servicio (₡)</label>
                        <input type="number" step="0.01" class="form-control" name="costo_servicio" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" name="observaciones" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Estado</label>
                        <input type="text" class="form-control" value="Pendiente" readonly>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Guardar Orden</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>