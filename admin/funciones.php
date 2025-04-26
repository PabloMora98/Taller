<?php
// Funciones para obtener datos de la base de datos usando stored procedures

//FUNCIONES CONSULTAS
function obtenerClientes($conn) {
    $stmt = $conn->prepare("EXEC sp_GetClientesActivos");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerVehiculos($conn) {
    $stmt = $conn->prepare("EXEC sp_GetVehiculosActivosConPropietario");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerInventario($conn) {
    $stmt = $conn->prepare("EXEC sp_GetInventarioActivo");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerFacturas($conn) {
    $stmt = $conn->prepare("EXEC sp_ConsultarFacturas");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerPagosPorFactura($conn, $id_factura) {
    $stmt = $conn->prepare("EXEC sp_ConsultarPagos ?");
    $stmt->execute([$id_factura]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerOrdenesPorCliente($conn, $busqueda) {
    $stmt = $conn->prepare("EXEC sp_BuscarOrdenesPorCliente ?");
    $stmt->execute([$busqueda]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerOrdenesPorEstado($conn, $estado) {
    $stmt = $conn->prepare("EXEC sp_FiltrarOrdenesPorEstado ?");
    $stmt->execute([$estado]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerOrdenesCompletas($conn) {
    $stmt = $conn->prepare("EXEC sp_ConsultarOrdenesCompletas");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//FUNCIONES AGREGAR
function agregarPago($conn, $id_factura, $id_persona, $fecha_pago, $monto_pagado, $metodo_pago) {
    $stmt = $conn->prepare("EXEC sp_AgregarPago ?, ?, ?, ?, ?");
    $stmt->execute([$id_factura, $id_persona, $fecha_pago, $monto_pagado, $metodo_pago]);
}


//FUNCIONES DESACTIVAR
function desactivarCliente($conn, $id) {
    $stmt = $conn->prepare("EXEC sp_DesactivarCliente ?");
    $stmt->execute([$id]);
}

function desactivarVehiculo($conn, $id) {
    $stmt = $conn->prepare("EXEC sp_DesactivarVehiculo ?");
    $stmt->execute([$id]);
}

function desactivarInventario($conn, $id) {
    $stmt = $conn->prepare("EXEC sp_DesactivarInventario ?");
    $stmt->execute([$id]);
}

function desactivarOrden($conn, $id) {
    $stmt = $conn->prepare("EXEC sp_DesactivarOrden ?");
    $stmt->execute([$id]);
}

function desactivarFactura($conn, $id) {
    $stmt = $conn->prepare("EXEC sp_DesactivarFactura ?");
    $stmt->execute([$id]);
}
?>
