<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        // Enviar la notificación al servidor Node.js mediante CURL

        // Datos que se enviarán como notificación
        $data = array('mensaje' => 'Nuevo reporte realizado por el estudiante', 'hora' => date('H:i:s'), 'lugar' => $_POST['report-location']);
        $data_string = json_encode($data);

        // Inicializa cURL para enviar una solicitud POST al servidor WebSocket
        $ch = curl_init('http://localhost:3000/notificar');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);

        // Comprobar si hay errores en cURL
        if (curl_errno($ch)) {
            echo 'Error en cURL: ' . curl_error($ch);
            exit;
        } else {
            echo 'Respuesta desde server.js: ' . $result;
        }

        curl_close($ch);

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

echo "hola";
exit;




?>