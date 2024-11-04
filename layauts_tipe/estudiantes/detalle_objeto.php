<?php

session_start();

include '../../conexion.php';

if($_SESSION["type_user"] != "1"){
  header("location: /Proyecto_Grado/index.php");
}

$id_peticion = $_GET["id"];
$sql = "SELECT comentarios_peticion, fecha_creacion, lugar_peticion, id_peticion FROM peticiones WHERE id_peticion= '$id_peticion'";

$result = $conn->query($sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Procesar los datos del formulario después de enviarlo
  $id_peticion_post = $_POST['id_peticion_post'];
  
  $sql_delete = "DELETE FROM peticiones WHERE id_peticion = $id_peticion_post";
  $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete->execute()) {
      echo "<script>alert('Petición eliminada exitosamente.'); window.location.href='objetos_abiertos.php';</script>";
  } else {
      echo "Error al eliminar la petición: " . $stmt_delete->error;
  }

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found</title>
    <link rel="stylesheet" href="../../styles/est_princ.css">
    <style>
    * {
      box-sizing: border-box;
    }
    .body-reportar {
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      height: 100vh; /* Hace que el body ocupe toda la ventana */
      margin: 0;
    }
    .form-container {
      width: 90%;
      max-width: 500px;
      padding: 20px;
      background-color: #f4f4f4;
      border-radius: 8px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
      margin: 20px auto;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input[type="text"],
    input[type="datetime-local"],
    textarea,
    select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    input[type="file"] {
      width: 100%;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: red;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background-color: darkred;
    }
    .span_descripcion{
      color: gray;
      font-weight: 300;
    }
  </style>

</head>

<body class="body_reportar">
    <header>    
        <nav>
            <h1>Lost & Found EAN</h1>
            <ul>
                <li><a href="/Proyecto_Grado/index.php">Inicio</a></li>
                <li><a href="objetos_abiertos.php">Objetos reclamados</a></li>
                <li><a href="../universal/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <?php if ($result->num_rows > 0): $row = $result->fetch_assoc()?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <label for="report-time">Fecha del Reporte*</label>
                <input type="text" value="<?php echo htmlspecialchars($row['fecha_creacion']); ?>" disabled>
            </div>
            <div class="form-group">
              <select required disabled>
                <option value="" disabled selected>Lugar del reporte: <?php echo htmlspecialchars($row['lugar_peticion']); ?></option>
              </select>
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto <span class="span_descripcion">--opcional--</span></label>
                <textarea rows="4" disabled><?php echo htmlspecialchars($row['comentarios_peticion']); ?></textarea>
            </div>
            <input type="hidden" name="id_peticion_post" value="<?php echo htmlspecialchars($row['id_peticion']); ?>">
            <button type="submit">Cancelar Reporte (No Llamar Vigilante)</button>
        </form>
        <?php else: ?>
          <p>Objeto no encontrado</p>
        <?php endif; ?>
    </div>
</body>


</html>