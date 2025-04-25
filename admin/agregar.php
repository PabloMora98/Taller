<?php
include_once "menu_lateral.php";
include_once "funciones.php";
include_once "vistas_dinamicas.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'agregar_clientes':
            // Usando sp_AgregarPersona
            $stmt = $conn->prepare("EXEC sp_AgregarPersona ?, ?, ?, ?, ?, ?, 'cliente'");
            $stmt->execute([
                $_POST['nombre'], $_POST['apellido1'], $_POST['apellido2'],
                $_POST['direccion'], $_POST['telefono'], $_POST['correo']
            ]);
            break;

        case 'agregar_vehiculos':
            // Usando sp_AgregarVehiculo
            $stmt = $conn->prepare("EXEC sp_AgregarVehiculo ?, ?, ?, ?, ?, ?");
            $stmt->execute([
                $_POST['id_cliente'],
                $_POST['marca'],
                $_POST['modelo'],
                $_POST['fecha_creacion'],
                $_POST['num_chasis'],
                $_POST['num_placa']
            ]);
            break;

        case 'agregar_inventario':
            // Usando sp_AgregarInventario
            $stmt = $conn->prepare("EXEC sp_AgregarInventario ?, ?, ?, ?");
            $stmt->execute([
                $_POST['codigo'], 
                $_POST['descripcion'], 
                $_POST['cantidad'], 
                $_POST['precio']
            ]);
            break;
    }
}

$vista = $_GET['vista'] ?? 'cliente';
?>

<!-- El resto del código HTML permanece igual -->
<div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Agregar nuevo <?= ucfirst($vista) ?></h2>
        <a href="gestiones.php" class="btn btn-outline-secondary">← Volver</a>
    </div>

    <div class="card shadow-sm" style="max-width: 50%;">
        <div class="row g-0">
            <div class="col-12 md-6 px-4 py-4">
                <?php if ($vista === 'clientes'): ?>
                    <form method="POST" action="agregar.php?vista=clientes">
                        <input type="hidden" name="accion" value="agregar_clientes">

                        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" required></div>
                        <div class="mb-3"><label class="form-label">Primer Apellido</label><input type="text" class="form-control" name="apellido1" required></div>
                        <div class="mb-3"><label class="form-label">Segundo Apellido</label><input type="text" class="form-control" name="apellido2" required></div>
                        <div class="mb-3"><label class="form-label">Dirección</label><input type="text" class="form-control" name="direccion" required></div>
                        <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" class="form-control" name="telefono" required></div>
                        <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="correo" required></div>

                        <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                    </form>

                <?php elseif ($vista === 'vehiculos'): ?>
                    <form method="POST" action="agregar.php?vista=vehiculos">
                        <input type="hidden" name="accion" value="agregar_vehiculos">

                        <div class="mb-3"><label class="form-label">Marca</label><input type="text" class="form-control" name="marca" required></div>
                        <div class="mb-3"><label class="form-label">Modelo</label><input type="text" class="form-control" name="modelo" required></div>
                        <div class="mb-3"><label class="form-label">Fecha de creación</label><input type="date" class="form-control" name="fecha_creacion" required></div>
                        <div class="mb-3"><label class="form-label">Número de Chasis</label><input type="text" class="form-control" name="num_chasis" required></div>
                        <div class="mb-3"><label class="form-label">Número de Placa</label><input type="text" class="form-control" name="num_placa" required></div>
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select class="form-select" name="id_cliente" required>
                                <?php
                                $clientes = obtenerClientes($conn);
                                foreach ($clientes as $cliente) {
                                    echo "<option value='{$cliente['id_persona']}'>" . htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido1']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Vehículo</button>
                    </form>

                <?php elseif ($vista === 'inventario'): ?>
                    <form method="POST" action="agregar.php?vista=inventario">
                        <input type="hidden" name="accion" value="agregar_inventario">

                        <div class="mb-3"><label class="form-label">Código</label><input type="text" class="form-control" name="codigo" required></div>
                        <div class="mb-3"><label class="form-label">Descripción</label><input type="text" class="form-control" name="descripcion" required></div>
                        <div class="mb-3"><label class="form-label">Cantidad</label><input type="number" class="form-control" name="cantidad" required></div>
                        <div class="mb-3"><label class="form-label">Precio</label><input type="number" step="0.01" class="form-control" name="precio" required></div>

                        <button type="submit" class="btn btn-primary">Guardar Inventario</button>
                    </form>

                <?php else: ?>
                    <div class="alert alert-danger">Vista no válida.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>