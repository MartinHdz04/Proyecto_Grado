<?php

// Incluir archivo de conexión
include 'conexion.php';

session_start();

// Si ya hay una sesión activa, redirigir según el tipo de usuario
if (isset($_SESSION['usuario_id'])) {
    redirigir_usuario($_SESSION['type_user']);
}

// Verificar datos de login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_red = $_POST['usuario_red'];
    $clave = $_POST['clave'];
    $usuario_encontrado = false;

    // Primera consulta: verificar en la tabla `usuarios`
    $sql = "SELECT * FROM usuarios WHERE `user_name` = ? AND `pass_word` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario_red, $clave);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usuario_id'] = $row['id_usuario'];
        $_SESSION['nombre_usuario'] = $row['primer_Nombre'];
        $_SESSION['primer_apellido'] = $row['primer_apellido'];
        $_SESSION['type_user'] = $row['rol'];
        $usuario_encontrado = true;
        $tipo_usuario = $row['rol'];
    }
    $stmt->close();

    // Segunda consulta: verificar en la tabla `vigilantes` solo si no se encontró en `usuarios`
    if (!$usuario_encontrado) {
        $sql = "SELECT * FROM vigilantes WHERE `user_name` = ? AND `pass_word` = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario_red, $clave);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['usuario_id'] = $row['id_vigilante'];
            $_SESSION['nombre_usuario'] = $row['primer_Nombre'];
            $_SESSION['primer_apellido'] = $row['primer_apellido'];
            $_SESSION['type_user'] = $row['rol'];
            $tipo_usuario = $row['rol'];
            $usuario_encontrado = true;
        }
        $stmt->close();
    }

    // Redirigir según el tipo de usuario si fue encontrado
    if ($usuario_encontrado) {
        redirigir_usuario($tipo_usuario);
    } else {
        echo "Usuario o contraseña incorrectos.";
    }
}

// Cerrar conexión
$conn->close();

// Función para redirigir según el tipo de usuario
function redirigir_usuario($tipo_usuario) {
    switch ($tipo_usuario) {
        case 1:
            header("Location: layauts_tipe/estudiantes/estudiantes.php");
            break;
        case 2:
            header("Location: layauts_tipe/vigilantes/vigilantes.php");
            break;
        case 3:
            header("Location: layauts_tipe/admin/admin.php");
            break;
        default:
            echo "Tipo de usuario no válido.";
            break;
    }
    exit();
}
?>
