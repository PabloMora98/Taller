<?php
include_once "menu_lateral.php";
include_once "funciones.php";
include_once "vistas_dinamicas.php";



// Procesar filtros
$estado = $_GET['estado'] ?? 'todos';
$busqueda = $_GET['busqueda'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desactivar_orden_id'])) {
    desactivarOrden($conn, $_POST['desactivar_orden_id']);
    header("Location: ordenes.php"); // Redirige para evitar re-envío del formulario
    exit();
}


// Obtener órdenes según filtros
if (!empty($busqueda)) {
    $ordenes =  obtenerOrdenesPorCliente($conn, $busqueda);
} elseif ($estado != 'todos') {
    $ordenes = obtenerOrdenesPorEstado($conn, $estado);
} else {
    $ordenes = obtenerOrdenesCompletas($conn);
}

?>


<body>
    <div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Ordenes</h2>
       
    </div>
        <!-- Filtros -->
        <div class="form-filtros shadow-sm mb-4">
            <form method="GET" action="" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="busqueda" class="form-label">Buscar por cliente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="busqueda" name="busqueda" 
                               value="<?= htmlspecialchars($busqueda) ?>" placeholder="Nombre del cliente">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="estado" class="form-label">Filtrar por estado:</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="todos" <?= $estado == 'todos' ? 'selected' : '' ?>>Todos los estados</option>
                        <option value="Pendiente" <?= $estado == 'Pendiente' ? 'selected' : '' ?>>Pendientes</option>
                        <option value="En proceso" <?= $estado == 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                        <option value="Finalizado" <?= $estado == 'Finalizado' ? 'selected' : '' ?>>Finalizados</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Tabla de órdenes -->
        <?php vistaOrdenes($ordenes); ?>
    </div>
<?php include_once "footer.php"; ?>