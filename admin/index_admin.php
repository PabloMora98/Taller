<?php include_once "menu_lateral.php"; ?>

  
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h2>Bienvenido, <?php echo $_SESSION['nombre'] . " " . $_SESSION['apellido']; ?> 👋</h2>
            <p>Panel de administración del taller</p>
        </div>

        <div class="mt-4">
            <h4>Módulos disponibles:</h4>
            <ul>
                <li><strong>Clientes:</strong> Información personal de los clientes.</li>
                <li><strong>Vehículos y Reparaciones:</strong> Detalles técnicos de cada vehículo y su historial de reparaciones.</li>
                <li><strong>Inventario:</strong> Control de stock de piezas y materiales.</li>
                <li><strong>Órdenes de Trabajo:</strong> Registro de servicios realizados, diagnósticos y seguimiento.</li>
                <li><strong>Facturación y Pagos:</strong> Generación de facturas y registro de pagos.</li>
            </ul>
        </div>
    </div>

    </body>
    </html>
