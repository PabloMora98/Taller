<?php
session_start();
include_once "../conexion.php";
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../login.html");
    exit();
}
$conn = Conexion::ConexionBD();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Taller Mora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/index.css">

</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-white text-center mb-4">Menú Admin</h4>
        <a href="index_admin.php">Inicio</a>
        <a href="gestiones.php">Gestiónes</a>
        <a href="ordenes.php">Órdenes</a>
        <a href="facturas.php">Facturación y Pagos</a>
        <a href="../cerrar_sesion.php" class="text-danger">Cerrar Sesión</a>
    </div>