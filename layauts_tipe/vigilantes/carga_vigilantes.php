<?php 
session_start();

require_once '../../conexion.php'; // Asegúrate de que este archivo conecta a la base de datos y define $conn

if ($_SESSION["type_user"] != "2") {
    header("location: /Proyecto_Grado/index.php");
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Proyecto_Grado");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $hora_reporte = date('Y-m-d H:i:s');
    $lugar_reporte = $_POST['report-location'];
    $nombre_objeto = $_POST['name'];
    $descripcion_objeto = $_POST['description'];
    $estado_reporte = 'CREADO'; 
    $vigilante_id = $_SESSION['usuario_id'];
    $comentario = $_POST['comment'];

    // Generar una referencia única
    do {
        $referencia_unica = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11);
        $sql_check = "SELECT COUNT(*) AS count FROM objetos_reportados WHERE referencia_objeto = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $referencia_unica);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();
    } while ($count > 0);

    // Manejar la imagen
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] == 0) {
        $imagen = file_get_contents($_FILES['product-image']['tmp_name']);
    } else {
        echo "Error al subir la imagen.";
        exit();
    }

    // Preparar la consulta de inserción
    $sql = "INSERT INTO objetos_reportados (referencia_objeto, fecha_reporte, estado_reporte, nombre_objeto, descripcion_objeto, lugar_encontrado, vigilante_encargado, fotografia_objeto, comentario_vigilante) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);    

    // Comprobar si la preparación fue exitosa
    if (!$stmt) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit();
    }

    // Bind de los parámetros (b para BLOB)
    $stmt->bind_param("sssssisss", $referencia_unica, $hora_reporte, $estado_reporte, $nombre_objeto, $descripcion_objeto, $lugar_reporte, $vigilante_id, $imagen, $comentario);

    // Cambia el tipo de la imagen a BLOB
    $stmt->send_long_data(7, $imagen);

    if ($stmt->execute()) {
        header("Location: obj_rep_vig.php");
    } else {
        echo "Error al registrar el reporte: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>