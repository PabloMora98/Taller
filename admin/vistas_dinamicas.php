<?php
// Vista de Clientes


function vistaClientes($clientes) {
    echo '<div id="vista_clientes" class="vista-section">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4>Lista de Clientes</h4>';
    echo '<a id="btnAgregar" href="agregar.php?vista=cliente" class="btn btn-primary">+ Añadir Registro</a>';
    echo '</div>';
    echo '<table class="table table-bordered table-hover mt-3">';
    echo '<thead class="table-dark">';
    echo '<tr><th>Nombre</th><th>Dirección</th><th>Teléfono</th><th>Correo</th><th>Acciones</th></tr>';
    echo '</thead><tbody>';

    foreach ($clientes as $cliente) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido1'] . ' ' . $cliente['apellido2']) . '</td>';
        echo '<td>' . htmlspecialchars($cliente['direccion']) . '</td>';
        echo '<td>' . htmlspecialchars($cliente['telefono']) . '</td>';
        echo '<td>' . htmlspecialchars($cliente['correo']) . '</td>';
        echo '<td>';
        echo '<button class="btn btn-sm btn-warning btn-editar" data-url="editar.php?vista=clientes&id=' . $cliente['id_persona'] . '">';
        echo '<i class="bi bi-pencil"></i>';
        echo '</button>';
        echo '<form method="POST" class="form-desactivar-cliente d-inline">';
        echo '<input type="hidden" name="desactivar_id" value="' . $cliente['id_persona'] . '">';
        echo '<button type="submit" class="btn btn-sm btn-danger btn-desactivar">';
        echo '<i class="bi bi-trash"></i>';
        echo '</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}


// Vista de Vehículos
function vistaVehiculos($vehiculos) {
    echo '<div id="vista_vehiculos" class="vista-section">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4>Lista de Vehículos</h4>';
    echo '<a id="btnAgregar" href="agregar.php?vista=vehiculos" class="btn btn-primary">+ Añadir Registro</a>';
    echo '</div>';
    echo '<table class="table table-bordered table-hover mt-3">';
    echo '<thead class="table-dark">';
    echo '<tr><th>Cliente</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Chasis</th><th>Placa</th><th>Acciones</th></tr>';
    echo '</thead><tbody>';

    foreach ($vehiculos as $v) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($v['nombre'] . ' ' . $v['apellido1']) . '</td>';
        echo '<td>' . htmlspecialchars($v['marca']) . '</td>';
        echo '<td>' . htmlspecialchars($v['modelo']) . '</td>';
        echo '<td>' . htmlspecialchars($v['fecha_creacion']) . '</td>';
        echo '<td>' . htmlspecialchars($v['num_chasis']) . '</td>';
        echo '<td>' . htmlspecialchars($v['num_placa']) . '</td>';
        echo '<td>';
        echo '<button class="btn btn-sm btn-warning btn-editar" data-url="editar.php?vista=vehiculos&id=' . $v['id_vehiculo'] . '">';
        echo '<i class="bi bi-pencil"></i>';
        echo '</button>';
        echo '<form method="POST" class="form-desactivar-vehiculo d-inline">';
        echo '<input type="hidden" name="desactivar_vehiculo_id" value="' . $v['id_vehiculo'] . '">';
        echo '<button type="submit" class="btn btn-sm btn-danger btn-desactivar">';
        echo '<i class="bi bi-trash"></i>';
        echo '</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}


// Vista de Inventario
function vistaInventario($inventario) {
    echo '<div id="vista_inventario" class="vista-section">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4>Lista de Inventario</h4>';
    echo '<a id="btnAgregar" href="agregar.php?vista=inventario" class="btn btn-primary">+ Añadir Registro</a>';
    echo '</div>';
    echo '<table class="table table-bordered table-hover mt-3">';
    echo '<thead class="table-dark">';
    echo '<tr><th>Código</th><th>Descripción</th><th>Cantidad</th><th>Precio</th><th>Acciones</th></tr>';
    echo '</thead><tbody>';

    foreach ($inventario as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['codigo']) . '</td>';
        echo '<td>' . htmlspecialchars($item['descripcion']) . '</td>';
        echo '<td>' . $item['cantidad_disponible'] . '</td>';
        echo '<td>₡' . number_format($item['precio_unitario'], 2) . '</td>';
        echo '<td>';
        echo '<button class="btn btn-sm btn-warning btn-editar" data-url="editar.php?vista=inventario&id=' . $item['id_inventario'] . '">';
        echo '<i class="bi bi-pencil"></i>';
        echo '</button>';
        echo '<form method="POST" class="form-desactivar-inventario d-inline">';
        echo '<input type="hidden" name="desactivar_inventario_id" value="' . $item['id_inventario'] . '">';
        echo '<button type="submit" class="btn btn-sm btn-danger btn-desactivar">';
        echo '<i class="bi bi-trash"></i>';
        echo '</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

function vistaOrdenes($ordenes) {
    echo '<div id="vista_ordenes" class="vista-section">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4>Lista de Órdenes</h4>';
    echo '<a href="agregar_orden.php" class="btn btn-primary btn-sm">';
    echo '<i class="bi bi-plus-circle"></i> Nueva Orden';
    echo '</a>';
    echo '</div>';
    
    echo '<table class="table table-bordered table-hover">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Vehículo</th>';
    echo '<th>Cliente</th>';
    echo '<th>Fecha Ingreso</th>';
    echo '<th>Estado</th>';
    echo '<th>Costo</th>';
    echo '<th>Acciones</th>';
    echo '</tr>';
    echo '</thead><tbody>';

    foreach ($ordenes as $orden) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($orden['id_orden']) . '</td>';
        echo '<td>' . htmlspecialchars($orden['marca_vehiculo'] . ' ' . $orden['modelo_vehiculo']) . '</td>';
        echo '<td>' . htmlspecialchars($orden['cliente']) . '</td>';
        echo '<td>' . date('d/m/Y', strtotime($orden['fecha_ingreso'])) . '</td>';
        
        // Estado con badge de color
        echo '<td><span class="badge ';
        switch($orden['estado']) {
            case 'Pendiente': echo 'bg-warning text-dark'; break;
            case 'En proceso': echo 'bg-primary'; break;
            case 'Finalizado': echo 'bg-success'; break;
            default: echo 'bg-secondary';
        }
        echo '">' . htmlspecialchars($orden['estado']) . '</span></td>';
        
        echo '<td>₡' . number_format($orden['costo_servicio'], 2) . '</td>';
        
        // Acciones
        echo '<td class="text-nowrap">';
        echo '<button class="btn btn-sm btn-info me-1 btn-ver-orden" 
        data-id="' . $orden['id_orden'] . '"
        data-vehiculo="' . htmlspecialchars($orden['marca_vehiculo'] . ' ' . $orden['modelo_vehiculo']) . '"
        data-cliente="' . htmlspecialchars($orden['cliente']) . '"
        data-fecha="' . date('d/m/Y', strtotime($orden['fecha_ingreso'])) . '"
        data-estado="' . htmlspecialchars($orden['estado']) . '"
        data-costo="₡' . number_format($orden['costo_servicio'], 2) . '">
        <i class="bi bi-eye"></i>
    </button>';

        echo '<button type="button" class="btn btn-sm btn-warning me-1 btn-editar" data-url="editar_orden.php?id=' . $orden['id_orden'] . '">';
        echo '<i class="bi bi-pencil"></i>';
        echo '</button>';        
        echo '<form method="POST" class="form-desactivar-orden d-inline">';
        echo '<input type="hidden" name="desactivar_orden_id" value="' . $orden['id_orden'] . '">';
        echo '<button type="submit" class="btn btn-sm btn-danger btn-desactivar">';
        echo '<i class="bi bi-trash"></i>';
        echo '</button>';
        echo '</form>';
        echo '</td>';
        
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}

function vistaFacturas($facturas) {
    echo '<div id="vista_facturas" class="vista-section">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4>Facturas Emitidas</h4>';
    echo '<a href="agregar_factura.php" class="btn btn-primary btn-sm">';
    echo '<i class="bi bi-plus-circle"></i> Nueva Factura';
    echo '</a>';
    echo '</div>';

    echo '<table class="table table-bordered table-hover">';
    echo '<thead class="table-dark">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Cliente</th>';
    echo '<th>Vehículo</th>';
    echo '<th>Placa</th>';
    echo '<th>Fecha Emisión</th>';
    echo '<th>Monto Total</th>';
    echo '<th>Estado de Pago</th>';
    echo '<th>Administrador</th>';
    echo '<th>Acciones</th>';
    echo '</tr>';
    echo '</thead><tbody>';

    foreach ($facturas as $factura) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($factura['id_factura']) . '</td>';
        echo '<td>' . htmlspecialchars($factura['nombre_cliente']) . '</td>';
        echo '<td>' . htmlspecialchars($factura['marca'] . ' ' . $factura['modelo']) . '</td>';
        echo '<td>' . htmlspecialchars($factura['num_placa']) . '</td>';
        echo '<td>' . date('d/m/Y', strtotime($factura['fecha_emision'])) . '</td>';
        echo '<td>₡' . number_format($factura['monto_total'], 2) . '</td>';

        echo '<td><span class="badge ';
        echo ($factura['estado_pago'] == 'Pagado') ? 'bg-success' : 'bg-warning text-dark';
        echo '">' . htmlspecialchars($factura['estado_pago']) . '</span></td>';

        echo '<td>' . htmlspecialchars($factura['nombre_admin']) . '</td>';

        echo '<td class="text-nowrap">';
        echo '<a href="imprimir_factura.php?id=' . $factura['id_factura'] . '" class="btn btn-sm btn-secondary me-1" target="_blank">';
        echo '<i class="bi bi-printer"></i> Imprimir';
        echo '</a>';
        
        echo '<button class="btn btn-sm btn-info me-1 btn-ver-factura" 
            data-id="' . $factura['id_factura'] . '"
            data-cliente="' . htmlspecialchars($factura['nombre_cliente']) . '"
            data-vehiculo="' . htmlspecialchars($factura['marca'] . ' ' . $factura['modelo']) . '"
            data-placa="' . htmlspecialchars($factura['num_placa']) . '"
            data-monto="' . number_format($factura['monto_total'], 2) . '"
            data-estado="' . htmlspecialchars($factura['estado_pago']) . '"
            data-admin="' . htmlspecialchars($factura['nombre_admin']) . '"
            data-fecha="' . date('d/m/Y', strtotime($factura['fecha_emision'])) . '">
            <i class="bi bi-eye"></i>
        </button>';
        
        echo '<a href="editar_factura.php?id=' . $factura['id_factura'] . '" class="btn btn-sm btn-warning me-1">';
        echo '<i class="bi bi-pencil"></i></a>';
        
        if ($factura['estado_pago'] === 'Pendiente') {
            echo '<a href="agregar_pago.php?id_factura=' . $factura['id_factura'] . '" class="btn btn-sm btn-success me-1">';
            echo '<i class="bi bi-cash-coin"></i> Pagar</a>';
        }
        
        echo '<form method="POST" class="form-desactivar-factura d-inline">';
        echo '<input type="hidden" name="desactivar_factura_id" value="' . $factura['id_factura'] . '">';
        echo '<button type="submit" class="btn btn-sm btn-danger">';
        echo '<i class="bi bi-trash"></i>';
        echo '</button></form>';
        echo '</td>';
        
        echo '</tr>';
    }

    echo '</tbody></table>';
    echo '</div>';
}



?>


