<?php
include_once "menu_lateral.php";
include_once "funciones.php";
include_once "vistas_dinamicas.php";



$vista = $_GET['vista'] ?? 'clientes';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<div class='alert alert-danger m-4'>ID no proporcionado.</div>";
    exit;
}

// Cargar datos del registro a editar
switch ($vista) {
    case 'clientes':
        $stmt = $conn->prepare("EXEC sp_ConsultarPersonaPorID ?");
        break;
    case 'vehiculos':
        $stmt = $conn->prepare("EXEC sp_ConsultarVehiculoPorID ?");
        break;
    case 'inventario':
        $stmt = $conn->prepare("EXEC sp_ConsultarInventarioPorID ?");
        break;
    default:
        echo "<div class='alert alert-danger m-4'>Vista no válida.</div>";
        exit;
}
$stmt->execute([$id]);
$registro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$registro) {
    echo "<div class='alert alert-warning m-4'>Registro no encontrado.</div>";
    exit;
}

// Procesar edición si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($_POST['accion']) {
        case 'editar_clientes':
            // Usando sp_ModificarPersona
            $stmt = $conn->prepare("EXEC sp_ModificarPersona ?, ?, ?, ?, ?, ?, ?, 'cliente'");
            $stmt->execute([
                $id,
                $_POST['nombre'], 
                $_POST['apellido1'], 
                $_POST['apellido2'],
                $_POST['direccion'], 
                $_POST['telefono'], 
                $_POST['correo']
            ]);
            header("Location: gestiones.php?vista=clientes");
            break;

        case 'editar_vehiculos':
            // Usando sp_ModificarVehiculo
            $stmt = $conn->prepare("EXEC sp_ModificarVehiculo ?, ?, ?, ?, ?, ?");
            $stmt->execute([
                $id,
                $_POST['marca'], 
                $_POST['modelo'], 
                $_POST['fecha_creacion'],
                $_POST['num_chasis'], 
                $_POST['num_placa']
            ]);
            header("Location: gestiones.php?vista=vehiculos");
            break;

        case 'editar_inventario':
            // Usando sp_ModificarInventario
            $stmt = $conn->prepare("EXEC sp_ModificarInventario ?, ?, ?, ?, ?");
            $stmt->execute([
                $id,
                $_POST['codigo'], 
                $_POST['descripcion'], 
                $_POST['cantidad'], 
                $_POST['precio']
            ]);
            header("Location: gestiones.php?vista=inventario");
            break;
    }
    exit;
}
?>

<!-- El resto del código HTML permanece igual -->
<div class="main-content px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Editar <?= ucfirst($vista) ?></h2>
        <a href="gestiones.php" class="btn btn-outline-secondary">← Volver</a>
    </div>

    <div class="card shadow-sm" style="max-width: 50%;">
        <div class="row g-0">
            <div class="col-12 px-4 py-4">
                <?php if ($vista === 'clientes'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="editar_clientes">
                        <div class="mb-3"><label class="form-label">Nombre</label><input type="text" class="form-control" name="nombre" value="<?= $registro['nombre'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Primer Apellido</label><input type="text" class="form-control" name="apellido1" value="<?= $registro['apellido1'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Segundo Apellido</label><input type="text" class="form-control" name="apellido2" value="<?= $registro['apellido2'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Dirección</label><input type="text" class="form-control" name="direccion" value="<?= $registro['direccion'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Teléfono</label><input type="text" class="form-control" name="telefono" value="<?= $registro['telefono'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Correo</label><input type="email" class="form-control" name="correo" value="<?= $registro['correo'] ?>" required></div>
                        <button type="submit" class="btn btn-primary">Actualizar Cliente</button>
                    </form>

                <?php elseif ($vista === 'vehiculos'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="editar_vehiculos">
                        <div class="mb-3"><label class="form-label">Marca</label><input type="text" class="form-control" name="marca" value="<?= $registro['marca'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Modelo</label><input type="text" class="form-control" name="modelo" value="<?= $registro['modelo'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Fecha de creación</label><input type="date" class="form-control" name="fecha_creacion" value="<?= $registro['fecha_creacion'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Número de Chasis</label><input type="text" class="form-control" name="num_chasis" value="<?= $registro['num_chasis'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Número de Placa</label><input type="text" class="form-control" name="num_placa" value="<?= $registro['num_placa'] ?>" required></div>
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <select class="form-select" name="id_cliente" required>
                                <?php
                                $clientes = obtenerClientes($conn);
                                foreach ($clientes as $cliente) {
                                    $selected = $cliente['id_persona'] == $registro['id_persona'] ? "selected" : "";
                                    echo "<option value='{$cliente['id_persona']}' $selected>" . htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido1']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Actualizar Vehículo</button>
                    </form>

                <?php elseif ($vista === 'inventario'): ?>
                    <form method="POST" action="">
                        <input type="hidden" name="accion" value="editar_inventario">
                        <div class="mb-3"><label class="form-label">Código</label><input type="text" class="form-control" name="codigo" value="<?= $registro['codigo'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Descripción</label><input type="text" class="form-control" name="descripcion" value="<?= $registro['descripcion'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Cantidad</label><input type="number" class="form-control" name="cantidad" value="<?= $registro['cantidad_disponible'] ?>" required></div>
                        <div class="mb-3"><label class="form-label">Precio</label><input type="number" step="0.01" class="form-control" name="precio" value="<?= $registro['precio_unitario'] ?>" required></div>
                        <button type="submit" class="btn btn-primary">Actualizar Inventario</button>
                    </form>

                <?php else: ?>
                    <div class="alert alert-danger">Vista no válida.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once "footer.php"; ?>