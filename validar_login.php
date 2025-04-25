<?php
session_start();
include_once "conexion.php";

if (!isset($_POST['usuario']) || !isset($_POST['password'])) {
    mostrarAlerta("Faltan datos del formulario.");
    exit();
}

$conn = Conexion::ConexionBD();

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$sql = "SELECT 
            u.id_usuario,
            u.nombre_usuario,
            u.contrasena,
            u.activo,
            p.id_persona,
            p.nombre,
            p.apellido1,
            p.rol
        FROM Usuarios u
        INNER JOIN Personas p ON u.id_persona = p.id_persona
        WHERE u.nombre_usuario = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$usuario]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    if (!$user['activo']) {
        mostrarAlerta("Cuenta inactiva. Contacte a soporte.");
        exit();
    }

    if ($user['contrasena'] === $password) {
        $_SESSION['usuario_id'] = $user['id_usuario'];
        $_SESSION['id_persona'] = $user['id_persona']; 
        $_SESSION['nombre'] = $user['nombre'];
        $_SESSION['apellido'] = $user['apellido1'];
        $_SESSION['rol'] = $user['rol'];
    
        if ($user['rol'] === 'admin') {
            header("Location: admin/index_admin.php");
        } 
        exit();
    }
     else {
        mostrarAlerta("Credenciales incorrectas.");
    }
} else {
    mostrarAlerta("Credenciales incorrectas.");
}

function mostrarAlerta($mensaje) {
    echo "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Error de login</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '$mensaje',
                confirmButtonText: 'Volver',
            }).then(() => {
                window.location.href = 'login.php';
            });
        </script>
    </body>
    </html>
    ";
}
?>
