<?php
session_start();
require '../../conexion.php';

// Verificar si el usuario tiene permisos
if ($_SESSION["type_user"] != "3") {
    header("location: /Proyecto_Grado/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_peticion = isset($_POST['id_peticion']) ? intval($_POST['id_peticion']) : 0;

    if ($id_peticion > 0) {
        $sql = "DELETE FROM peticiones WHERE id_peticion = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $id_peticion);

            if ($stmt->execute()) {
                // Redirigir con mensaje de éxito (opcional)
                header("location: peticiones_admin.php?msg=cancelada");
                exit;
            } else {
                echo "Error al cancelar la petición: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error en la consulta: " . $conn->error;
        }
    } else {
        echo "ID de petición no válido.";
    }
} else {
    echo "Método no permitido.";
}

$conn->close();
?>
