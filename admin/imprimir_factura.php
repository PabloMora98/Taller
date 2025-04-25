<?php
require_once '../vendor/autoload.php';
include_once "funciones.php";
include_once "../conexion.php";

use Dompdf\Dompdf;
use Dompdf\Options;

// Verificar ID de factura
$id_factura = $_GET['id'] ?? null;
if (!$id_factura) {
    die("ID de factura no especificado");
}

// Obtener datos de la factura
$conn = Conexion::ConexionBD();
$stmt = $conn->prepare("
    SELECT f.*, 
           p.nombre + ' ' + p.apellido1 AS cliente,
           p.direccion AS direccion_cliente,
           p.telefono AS telefono_cliente,
           v.marca, v.modelo, v.num_placa, v.num_chasis,
           a.nombre + ' ' + a.apellido1 AS administrador,
           o.diagnostico, o.observaciones AS observaciones_orden
    FROM Facturas f
    INNER JOIN Ordenes o ON f.id_orden = o.id_orden
    INNER JOIN Vehiculos v ON o.id_vehiculo = v.id_vehiculo
    INNER JOIN Personas p ON v.id_persona = p.id_persona
    INNER JOIN Personas a ON f.id_persona = a.id_persona
    WHERE f.id_factura = ?
");
$stmt->execute([$id_factura]);
$factura = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$factura) {
    die("Factura no encontrada");
}

// Configurar Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultFont', 'Arial');

$dompdf = new Dompdf($options);

// HTML para el PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Factura #' . $factura['id_factura'] . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; color: #333; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .logo { max-width: 150px; }
        .titulo { text-align: center; margin-bottom: 30px; }
        .info-factura { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .datos-cliente, .datos-factura { width: 48%; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { text-align: right; font-weight: bold; font-size: 1.2em; }
        .footer { margin-top: 50px; font-size: 0.8em; text-align: center; }
        .firma { margin-top: 70px; border-top: 1px solid #333; width: 300px; text-align: center; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2>Taller Mecánico Automotriz</h2>
            <p>Dirección: Calle Principal #123, Ciudad</p>
            <p>Teléfono: 2222-2222</p>
            <p>Email: info@tallerautomotriz.com</p>
        </div>
        <div>
            <h3>FACTURA #' . $factura['id_factura'] . '</h3>
            <p>Fecha: ' . date('d/m/Y', strtotime($factura['fecha_emision'])) . '</p>
        </div>
    </div>

    <div class="info-factura">
        <div class="datos-cliente">
            <h4>Datos del Cliente</h4>
            <p><strong>Nombre:</strong> ' . $factura['cliente'] . '</p>
            <p><strong>Dirección:</strong> ' . $factura['direccion_cliente'] . '</p>
            <p><strong>Teléfono:</strong> ' . $factura['telefono_cliente'] . '</p>
        </div>
        <div class="datos-factura">
            <h4>Datos del Vehículo</h4>
            <p><strong>Marca/Modelo:</strong> ' . $factura['marca'] . ' ' . $factura['modelo'] . '</p>
            <p><strong>Placa:</strong> ' . $factura['num_placa'] . '</p>
            <p><strong>Chasis:</strong> ' . $factura['num_chasis'] . '</p>
        </div>
    </div>

    <h4>Detalle de Servicios</h4>
    <table>
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Reparación: ' . substr($factura['diagnostico'], 0, 50) . '...</td>
                <td>1</td>
                <td>' . number_format($factura['monto_total'], 2) . '</td>
                <td>' . number_format($factura['monto_total'], 2) . '</td>
            </tr>
        </tbody>
    </table>

    <div class="total">
        <p>TOTAL: ' . number_format($factura['monto_total'], 2) . '</p>
    </div>

    <div>
        <p><strong>Método de Pago:</strong> ' . $factura['metodo_pago'] . '</p>
        <p><strong>Estado:</strong> ' . $factura['estado_pago'] . '</p>
        <p><strong>Observaciones:</strong> ' . $factura['observaciones_orden'] . '</p>
    </div>

    <div class="firma">
        <p>Firma Autorizada</p>
    </div>

    <div class="footer">
        <p>Gracias por su preferencia</p>
        <p>Taller Mecánico Automotriz - Tel: 2222-2222</p>
    </div>
</body>
</html>
';

// Cargar el HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar el tamaño y orientación del papel
$dompdf->setPaper('A4', 'portrait');

// Renderizar el PDF
$dompdf->render();

// Generar el PDF para descarga
$dompdf->stream("factura_" . $factura['id_factura'] . ".pdf", [
    "Attachment" => true
]);