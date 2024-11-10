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
    
    $sql = "SELECT fotografia_objeto FROM objetos_reportados WHERE id_objeto = 1";
    $stmt = $conn->query($sql);
    
    //$stmt->bind_param("i", '2');
    
    
    if ($stmt->num_rows >0) {
        

        $row = $stmt->fetch_assoc();
        $imagen = $row['fotografia_objeto'];

        
        // Definir la ruta para guardar la imagen
        $ruta_archivo = '../../static/perfil_usuario_' . $usuario_id . '.jpg';
        
        // Guardar la imagen como un archivo local
        file_put_contents($ruta_archivo, $imagen);
        
        echo "Imagen guardada exitosamente en la ruta: $ruta_archivo";
    } else {
        echo "No se encontró una imagen para este usuario.";
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo "Error al obtener la imagen: " . $e->getMessage();
}
?>
