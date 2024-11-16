<?php



session_start();

include_once '../../conexion.php';


if($_SESSION["type_user"] != "2"){
  header("location: /Proyecto_Grado/index.php");
}


// Verificar si hay una búsqueda



// Verificar si hay una cédula
if (isset($_GET['cedula'])) {

    $cedula = $_GET['cedula'];
    
    // Consultar la base de datos para ver si la cédula existe
    $sql = "SELECT * FROM usuarios WHERE cedula = $cedula";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        echo 'existe';
    } else {
        echo 'no_existe';
    }
}

$conn->close();
?>
