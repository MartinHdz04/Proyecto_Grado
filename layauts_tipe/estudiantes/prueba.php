<?php
// Iniciar sesión
session_start();

// Incluir archivo de conexión
include '../../conexion.php';

// Verificar si el usuario ha iniciado sesión y obtener su ID
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : null;

if (!$usuario_id) {
    die("Usuario no autenticado");
}

try {
    // Consulta SQL para obtener la imagen
    $sql = "SELECT fotografia_entrega FROM objetos_entregados WHERE id_entrega = 20";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Obtener la fila asociativa
        $row = $result->fetch_assoc();
        $imagen = $row['fotografia_entrega'];

        // Verificar si la imagen no está vacía
        if (!empty($imagen)) {
            // Definir la ruta para guardar la imagen
            $ruta_archivo = '../../static/perfil_usuario_' . $usuario_id . '.jpg';

            // Guardar la imagen como un archivo local
            if (file_put_contents($ruta_archivo, $imagen) !== false) {
                echo "Imagen guardada exitosamente en la ruta: $ruta_archivo";
            } else {
                echo "Error al guardar la imagen en la ruta especificada.";
            }
        } else {
            echo "La imagen está vacía o no se pudo extraer.";
        }
    } else {
        echo "No se encontró una imagen para este usuario.";
    }

    // Cerrar el resultado y la conexión
    $result->free();
    $conn->close();
} catch (Exception $e) {
    echo "Error al obtener la imagen: " . $e->getMessage();
}
?>
