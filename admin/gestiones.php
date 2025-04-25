<?php
include_once "menu_lateral.php";
include_once "funciones.php";
include_once "vistas_dinamicas.php";



// Desactivar cliente si se envió solicitud
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desactivar_id'])) {
    desactivarCliente($conn, $_POST['desactivar_id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desactivar_vehiculo_id'])) {
    desactivarVehiculo($conn, $_POST['desactivar_vehiculo_id']);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['desactivar_inventario_id'])) {
    desactivarInventario($conn, $_POST['desactivar_inventario_id']);
}

// Obtener datos
$clientes = obtenerClientes($conn);
$vehiculos = obtenerVehiculos($conn);
$inventario = obtenerInventario($conn);
?>

<div class="main-content px-4 py-4">
    <!-- Botones superiores -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestión del Sistema</h2>
        
    </div>

    <!-- Selector de vistas -->
    <div class="mb-3">
        <label for="vistaSelect" class="form-label">Seleccionar vista:</label>

        <select id="vistaSelect" class="form-select w-auto">
            <option value="clientes">Clientes</option>
            <option value="vehiculos">Vehículos</option>
            <option value="inventario">Inventario</option>
        </select>       
    
    </div>
    
    <!-- Vistas -->
    <?php
    vistaClientes($clientes);
    vistaVehiculos($vehiculos);
    vistaInventario($inventario);
    ?>
</div>
<?php include_once "footer.php"; ?>
