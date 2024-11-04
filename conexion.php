<?php


// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "root";
$database = "lostandfound";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
    echo "error conexion";
    exit();
}
?>

