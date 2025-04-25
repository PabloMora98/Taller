<?php
include_once "menu_lateral.php";
include_once "funciones.php";



if (!isset($_GET['id'])) {
    die("ID de orden no especificado.");
}

$id_orden = $_GET['id'];

// Obtener los datos de la orden
$stmt = $conn->prepare("SELECT * FROM Ordenes WHERE id_orden = ?");
$stmt->execute([$id_orden]);
$orden = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$orden) {
    die("Orden no encontrada.");
}

// Procesar el formulario si se envió
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $conn->prepare("EXEC sp_ModificarOrden ?, ?, ?, ?, ?, ?");
        $stmt->execute([
            $id_orden,
            $_POST['fecha_ingreso'],
            $_POST['diagnostico'],
            $_POST['estado'],
            (float) str_replace(',', '.', $_POST['costo_servicio']),
            $_POST['observaciones']
        ]);
        

        header("Location: ordenes.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error al actualizar la orden: " . $e->getMessage();
    }
}
?>


<div class="main-content px-4 py-4">
    <h2>Editar orden #<?= $orden['id_orden'] ?></h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Fecha de ingreso</label>
            <input type="date" class="form-control" name="fecha_ingreso" value="<?= $orden['fecha_ingreso'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Diagnóstico</label>
            <textarea class="form-control" name="diagnostico" required><?= htmlspecialchars($orden['diagnostico']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select class="form-select" name="estado" required>
                <?php foreach (['Pendiente', 'En proceso', 'Finalizado'] as $estado): ?>
                    <option value="<?= $estado ?>" <?= $orden['estado'] === $estado ? 'selected' : '' ?>>
                        <?= $estado ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Costo del servicio ($)</label>
            <input type="number" step="0.01" class="form-control" name="costo_servicio" value="<?= $orden['costo_servicio'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Observaciones</label>
            <textarea class="form-control" name="observaciones"><?= htmlspecialchars($orden['observaciones']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Guardar cambios</button>
        <a href="ordenes.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once "footer.php"; ?>
