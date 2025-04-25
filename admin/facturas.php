<?php
include_once "menu_lateral.php";
include_once "funciones.php";
include_once "vistas_dinamicas.php";

$facturas = obtenerFacturas($conn);

?>

<div class="main-content px-4 py-4">

<div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Facturas</h2>
       
    </div>
    <div class="row">
        <div class="col-12">
            <?php vistaFacturas($facturas); ?>
        </div>
    </div>
</div>


<?php include_once "footer.php"; ?>

