// 🔁 Control de vistas y botón "Agregar"
const selector = document.getElementById('vistaSelect');
const btnAgregar = document.getElementById('btnAgregar');
const secciones = {
    clientes: document.getElementById('vista_clientes'),
    vehiculos: document.getElementById('vista_vehiculos'),
    inventario: document.getElementById('vista_inventario')
};

function actualizarVista() {
    const vistaActual = selector.value;

    // Oculta todas las vistas
    Object.values(secciones).forEach(seccion => seccion?.classList.add('d-none'));

    // Muestra la vista seleccionada
    secciones[vistaActual]?.classList.remove('d-none');

    // Actualiza el enlace del botón Agregar
    if (btnAgregar) {
        btnAgregar.href = `agregar.php?vista=${vistaActual}`;
    }
}

// Solo ejecuta si los elementos existen
if (selector && btnAgregar) {
    selector.addEventListener('change', actualizarVista);
    actualizarVista(); // Inicializa la vista al cargar
}

document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.btn-ver-factura').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const cliente = this.getAttribute('data-cliente');
            const vehiculo = this.getAttribute('data-vehiculo');
            const placa = this.getAttribute('data-placa');
            const fecha = this.getAttribute('data-fecha');
            const monto = this.getAttribute('data-monto');
            const estado = this.getAttribute('data-estado');
            const admin = this.getAttribute('data-admin');

            Swal.fire({
                title: `Factura #${id}`,
                html: `
                    <p><strong>Cliente:</strong> ${cliente}</p>
                    <p><strong>Vehículo:</strong> ${vehiculo}</p>
                    <p><strong>Placa:</strong> ${placa}</p>
                    <p><strong>Fecha de Emisión:</strong> ${fecha}</p>
                    <p><strong>Monto Total:</strong> ₡${monto}</p>
                    <p><strong>Estado de Pago:</strong> ${estado}</p>
                    <p><strong>Administrador:</strong> ${admin}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Cerrar',
                customClass: {
                    popup: 'text-start'
                },
                width: 600
            });
        });
    });

    document.querySelectorAll('.btn-ver-orden').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const vehiculo = this.getAttribute('data-vehiculo');
            const cliente = this.getAttribute('data-cliente');
            const fecha = this.getAttribute('data-fecha');
            const estado = this.getAttribute('data-estado');
            const costo = this.getAttribute('data-costo');
          
            
            Swal.fire({
                title: `Orden #${id}`,
                html: `
                    <p><strong>Vehículo:</strong> ${vehiculo}</p>
                    <p><strong>Cliente:</strong> ${cliente}</p>
                    <p><strong>Fecha de Ingreso:</strong> ${fecha}</p>
                    <p><strong>Estado:</strong> ${estado}</p>
                    <p><strong>Costo del Servicio:</strong> ${costo}</p>
                  
                `,
                icon: 'info',
                confirmButtonText: 'Cerrar',
                customClass: {
                    popup: 'text-start'
                },
                width: 600
            });
            
        });
    });

    // Botones para desactivar (clientes, vehículos, inventario, órdenes)
    document.querySelectorAll('form[class^="form-desactivar"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esto desactivará el registro.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Botones para editar (usan data-url)
    document.querySelectorAll('.btn-editar').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            Swal.fire({
                title: '¿Deseas editar este registro?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, editar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed && url) {
                    window.location.href = url;
                }
            });
        });
    });

});
