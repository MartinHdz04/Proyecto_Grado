<?php 
session_start();

require_once '../../conexion.php'; // Asegúrate de que este archivo conecta a la base de datos y define $conn

if ($_SESSION["type_user"] != "3") {
    header("location: /Proyecto_Grado/index.php");
    exit();
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Proyecto_Grado/index.php");
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
    $peticion_id = $_POST['peticion_id'];

    // Generar una referencia única
    do {
        $referencia_unica = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11);
        $sql_check = "SELECT COUNT(*) AS count FROM objetos_reportados WHERE referencia_objeto = ?";
        $stmt_check = $conn->prepare($sql_check);
        if (!$stmt_check) {
            echo "Error preparando la consulta para verificar referencia única: " . $conn->error;
            exit();
        }
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

        // Definir un tamaño específico para la imagen
    $anchoDeseado = 200; // Ancho en píxeles
    $altoDeseado = 200;  // Alto en píxeles

    // Manejar la imagen subida
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] == 0) {
        // Obtener el tipo de imagen
        $tipoImagen = $_FILES['product-image']['type'];
        $rutaTemporal = $_FILES['product-image']['tmp_name'];

        // Crear una imagen desde el archivo temporal según su tipo
        switch ($tipoImagen) {
            case 'image/jpeg':
                $imagenOriginal = imagecreatefromjpeg($rutaTemporal);
                break;
            case 'image/png':
                $imagenOriginal = imagecreatefrompng($rutaTemporal);
                break;
            case 'image/gif':
                $imagenOriginal = imagecreatefromgif($rutaTemporal);
                break;
            default:
                echo "Tipo de imagen no soportado.";
                exit();
        }

        // Obtener las dimensiones originales de la imagen
        list($anchoOriginal, $altoOriginal) = getimagesize($rutaTemporal);

        // Crear una imagen nueva con el tamaño deseado
        $imagenRedimensionada = imagecreatetruecolor($anchoDeseado, $altoDeseado);

        // Redimensionar la imagen original a la nueva imagen
        imagecopyresampled(
            $imagenRedimensionada,
            $imagenOriginal,
            0, 0, 0, 0,
            $anchoDeseado, $altoDeseado,
            $anchoOriginal, $altoOriginal
        );

        // Guardar la imagen redimensionada en una variable para la base de datos
        ob_start(); // Iniciar la salida en buffer
        imagejpeg($imagenRedimensionada); // Crear la imagen en el buffer
        $imagen = ob_get_clean(); // Obtener el contenido del buffer

        // Liberar memoria
        imagedestroy($imagenOriginal);
        imagedestroy($imagenRedimensionada);
    } else {
        echo "Error al subir la imagen.";
        exit();
    }

    

    // Preparar la consulta de inserción, incluyendo la imagen como parte de la misma operación
    $sql = "INSERT INTO objetos_reportados (referencia_objeto, fecha_reporte, estado_reporte, nombre_objeto, descripcion_objeto, lugar_encontrado, vigilante_encargado, comentario_vigilante, fotografia_objeto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    

    if (!$stmt) {
        echo "Error en la preparación de la consulta: " . $conn->error;
        exit();
    }

    // Vincular parámetros (s para string, i para integer, b para blob)
    $stmt->bind_param("sssssissb", $referencia_unica, $hora_reporte, $estado_reporte, $nombre_objeto, $descripcion_objeto, $lugar_reporte, $vigilante_id, $comentario, $imagen);

    // Enviar la imagen como datos largos (BLOB)
    $stmt->send_long_data(8, $imagen);
    

    // Intentar ejecutar la consulta
    if (!$stmt->execute()) {
        echo "Error al ejecutar la consulta: " . $stmt->error;
        exit();
    }
    
    if($peticion_id != null){
        $sql = "UPDATE peticiones SET estado_peticion = 'cerrado' WHERE id_peticion = ?";
        
        $stmt = $conn->prepare($sql);
        
        $stmt->bind_param("i", $peticion_id);
        
        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            exit();
        }
    }

    // Redirigir a la página de reportes
    header("Location: obj_rep_admin.php");
    exit();

    
}
$stmt->close();
$conn->close();
?>
