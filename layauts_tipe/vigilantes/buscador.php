<?php



session_start();

include '../../conexion.php';

if($_SESSION["type_user"] != "2"){
  header("location: /Proyecto_Grado/index.php");
}


// Verificar si hay una búsqueda
if (isset($_GET['query'])) {
    
    $query = $_GET['query'];

    
    // Consultar la base de datos
    $sql = "SELECT referencia_usuario, cedula, primer_nombre, primer_apellido 
            FROM usuarios 
            WHERE primer_nombre LIKE '%$query%' 
            OR primer_apellido LIKE '%$query%' 
            OR cedula LIKE '%$query%'
            LIMIT 10";
            
        
            
    $result = $conn->query($sql);
    
    
    // Verificar si hay resultados
    if ($result = $conn->query($sql)) {
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div data-iduser = '" . htmlspecialchars($row['referencia_usuario'])."' class='result' id = '" . htmlspecialchars($row['cedula']) . "'>" . htmlspecialchars($row['primer_nombre']) . " " . htmlspecialchars($row['primer_apellido']) . " (Cédula: " . htmlspecialchars($row['cedula']) . ")</div>";
            }
        } else {
            echo "<div>No se encontraron resultados.</div>";
        }
        $result->free();
    } else {
        echo "Error en la consulta: " . $conexion->error;
    }
}

$conn->close();
?>
