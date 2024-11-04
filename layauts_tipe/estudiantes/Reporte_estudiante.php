<?php

session_start();

// Incluir archivo de conexión
include '../../conexion.php';

try {

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario

    $descripcion_objeto = $_POST['description'];
    $lugar_reporte = $_POST['report-location'];
    $hora_reporte = date("Y-m-d"); // Hora actual del servidor
    $usuario_id = $_SESSION['usuario_id'];

    $sql = "INSERT INTO peticiones (fecha_creacion, estado_peticion, comentarios_peticion, lugar_peticion, id_usuario) VALUES ('$hora_reporte','abierto','$descripcion_objeto','$lugar_reporte','$usuario_id')";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . $conn->error);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Reporte enviado exitosamente.'); window.location.href='objetos_abiertos.php';</script>";
    } else {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    $stmt->close();
    
}

} catch (Exception $e) {
    // Mostrar mensaje de error
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

$conn->close();





?>