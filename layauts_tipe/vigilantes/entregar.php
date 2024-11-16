    <?php 
    session_start();

    require_once '../../conexion.php'; // Asegúrate de que este archivo conecta a la base de datos y define $conn

    if ($_SESSION["type_user"] != "2") {
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
        $referencia_objeto = $_POST['referencia_objeto'];
        $referencia_usuario = $_POST['estudiante_id'];
        $estado_reporte = 'entregado'; 
        $vigilante_id = $_SESSION['usuario_id'];
        

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

            


            // Definir la carpeta de destino
        $carpetaDestino = '../../static/imgstest';
        
        // Crear la carpeta si no existe
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0755, true);
        }

        // Generar un nombre único para la imagen
        $nombreImagen = uniqid('img_') . '.jpg';

        // Definir la ruta completa de la imagen
        $rutaImagen = $carpetaDestino . $nombreImagen;

        // Guardar la imagen redimensionada en la carpeta de destino
        if (imagejpeg($imagenRedimensionada, $rutaImagen)) {
            //echo "Imagen guardada correctamente en " . $rutaImagen;
            $imagen = file_get_contents($rutaImagen);
            
        } else {
            echo "Error al guardar la imagen.";
            exit();
        }

        // Liberar memoria
        imagedestroy($imagenOriginal);
        imagedestroy($imagenRedimensionada);

    } else {
        echo "Error al subir la imagen.";
        exit();
    }

        

        // Preparar la consulta de inserción, incluyendo la imagen como parte de la misma operación
        $sql = "INSERT INTO objetos_entregados (referencia_objeto, fecha_entrega, referencia_usuario, referencia_vigilante, fotografia_entrega) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

                

        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $conn->error;
            exit();
        }
        
        
        // Vincular parámetros (s para string, i para integer, b para blob)
        $stmt->bind_param("sssis", $referencia_objeto, $hora_reporte, $referencia_usuario, $_SESSION["referencia_user"], $imagen);
        
        

        // Intentar ejecutar la consulta
        if (!$stmt->execute()) {
            
            echo "Error al ejecutar la consulta: " . $stmt->error;
            exit();

        }

        
        
        $sql = "UPDATE objetos_reportados SET estado_reporte = 'ENTREGADO' WHERE  referencia_objeto = ? ";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $conn->error;
            exit();
        }
        $stmt->bind_param("s", $referencia_objeto);
        // Intentar ejecutar la consulta
        if (!$stmt->execute()) {
            
            echo "Error al ejecutar la consulta: " . $stmt->error;
            exit();

        }

        if (file_exists($rutaImagen)) {
            if (unlink($rutaImagen)) {
                echo "Imagen eliminada correctamente.";
            } else {
                echo "Error al eliminar la imagen.";
                exit;
            }
        } else {
            echo "La imagen no existe para ser eliminada.";
            exit;
        }

        // Redirigir a la página de reportes
        header("Location: obj_rep_vig.php");
        exit();

        $stmt->close();
    }

    $conn->close();
    ?>
