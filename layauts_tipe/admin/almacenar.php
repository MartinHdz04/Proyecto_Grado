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
        $referencia_objeto = $_POST['referencia_objeto'];
        $referencia_usuario = $_POST['vigilante_id'];
        $valor_objeto = $_POST['valor_objeto'];
        $comentarios = $_POST['comentario_vigilante'];
        


        // Preparar la consulta de inserción, incluyendo la imagen como parte de la misma operación
        $sql = "INSERT INTO objetos_almacenados (fecha_almacenado, referencia_objeto, id_vigilante, valor_objeto, comentario_vigilante) VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

                

        if (!$stmt) {
            echo "Error en la preparación de la consulta: " . $conn->error;
            exit();
        }
        
        
        // Vincular parámetros (s para string, i para integer, b para blob)
        $stmt->bind_param("ssiis", $hora_reporte, $referencia_objeto, $referencia_usuario, $valor_objeto, $comentarios);
        
        

        // Intentar ejecutar la consulta
        if (!$stmt->execute()) {
            
            echo "Error al ejecutar la consulta: " . $stmt->error;
            exit();

        }

        
        
        $sql = "UPDATE objetos_reportados SET estado_reporte = 'ALMACENADO' WHERE  referencia_objeto = ? ";
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

        

        // Redirigir a la página de reportes
        header("Location: obj_rep_admin.php");
        exit();

        $stmt->close();
    }

    $conn->close();
    ?>
