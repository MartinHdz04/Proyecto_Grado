<?php

session_start();

include '../../conexion.php';

if($_SESSION["type_user"] != "3"){
  header("location: /Proyecto_Grado/index.php");
}

$id_peticion = $_GET["id"];
$sql = "SELECT comentarios_peticion, fecha_creacion, lugar_peticion, id_peticion FROM peticiones WHERE id_peticion= '$id_peticion'";

$result = $conn->query($sql);

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

    html{
      height: 100%;
    }
    .body_reportar {
      background-color: #f5f5f5;
      height: 100%;
      display: flex;
      flex-direction: column;
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
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }
    button:hover {
      background-color: #45a049;
    }
    .span_descripcion{
      color: gray;
      font-weight: 300;
    }
    @media (max-width: 480px){
      nav{
          flex-direction: column;
        }
      ul{
        flex-direction: column;
        align-items: center;
      }
      li{
        margin-top: 15px;
      }
    }

  </style>

</head>

<body class="body_reportar">
    <header>    
        <nav>
            <h1>Lost & Found EAN</h1>
            <ul>
                <li><a href="/Proyecto_Grado/index.php">Inicio</a></li>
                <li><a href="objetos_abiertos.php">Objetos reportados</a></li>
                <li><a href="objetos_entregados.php">Objetos reclamados</a></li>
                <li><a href="../universal/logout.php">Cerrar Sesion</a></li>
                
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <?php if ($result->num_rows > 0): $row = $result->fetch_assoc()?>

            <div class="form-group">
                <label for="report-time">ID de la petición</label>
                <input type="text" value="<?php echo htmlspecialchars($row['id_peticion']); ?>" disabled>
            </div>
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
            <a href="peticiones_admin.php" style="text-decoration:none;">
                <button type="">Volver</button>
            </a>
            

        <?php else: ?>
          <p>Objeto no encontrado</p>
        <?php endif; ?>
    </div>
    <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
</body>


</html>