<?php


// Incluir archivo de conexión
include 'conexion.php';



// Verificar datos de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_red = $_POST['usuario_red'];
    $clave = $_POST['clave'];

    // Consulta para verificar el usuario y la contraseña

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario_red' AND contrasenia = '$clave'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $tipo_usuario = $row['tipo_usuario'];
        redirigir_usuario($tipo_usuario);
        
    } else {
        // Login fallido
        echo "Usuario o contraseña incorrectos.";
    }

    $stmt->close();
}

// Cerrar conexión
$conn->close();

// Función para redirigir según el tipo de usuario
function redirigir_usuario($tipo_usuario) {
    switch ($tipo_usuario) {
        case 1:
            header("Location: layauts_tipe/estudiantes.php");
            break;
        case 2:
            header("Location: layauts_tipe/vigilantes.php");
            break;
        case 3:
            header("Location: layauts_tipe/admin.php");
            break;
        default:
            echo "Tipo de usuario no válido.";
            break;
    }
    exit();
}

?>

