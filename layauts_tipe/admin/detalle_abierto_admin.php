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
      background-color: green;
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
    <?php include '../universal/header_admin.php'?>
    <div class="form-container">
        <h3><?php echo "ID peticion: $id_peticion"; ?></h3>
        <br>
        <?php if ($result->num_rows > 0): $row = $result->fetch_assoc()?>
        <form enctype="multipart/form-data" method="POST" onsubmit="return validateForm()" action="carga_admin.php">
            <div class="form-group">
                <label for="product-image">Fotografía del Producto*</label>
                <input type="file" id="product-image" name="product-image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="report-time">Hora del Reporte</label>
                <input type="datetime-local" id="report-time" name="report-time" disabled required>
            </div>

            <div class="form-group">
              <label for="report-location">Lugar de encuentro:</label>
              <select id="report-location" name="report-location" required>
                  <option value="<?php echo htmlspecialchars($row['lugar_peticion']);?>" selected><?php echo htmlspecialchars($row['lugar_peticion']); ?></option>
                  <option value="Plaza de comidas">Plaza de comidas</option>
                  <option value="Biblioteca">Biblioteca</option>
                  <option value="L04">L04</option>
                  <option value="L06">L06</option>
                  <option value="L01">L01</option>
                  <option value="L02">L02</option>
                  <option value="L03">L03</option>
                  <option value="L05">L05</option>
                  <option value="L07">L07</option>
                  <option value="L08">L08</option>
                  <option value="L09">L09</option>
                  <option value="L10">L10</option>
                  <option value="N01">N01</option>
                  <option value="N02">N02</option>
                  <option value="N03">N03</option>
                  <option value="N04">N04</option>
                  <option value="N05">N05</option>
                  <option value="N06">N06</option>
                  <option value="N07">N07</option>
              </select>
            </div>

            <div class="form-group">
                <br>
                <label for="name">Nombre del objeto*</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto</label>
                <textarea id="description" name="description" rows="4" required ><?php echo htmlspecialchars($row["comentarios_peticion"]) ?></textarea>
            </div>

            <div class="form-group">
                <h3>Persona quien recibe:*</h3>
                <br>
                <label for="nombre_vig">Nombre:</label>
                <input type="text" id="nombre_vig" name="nombre_vig" value="<?php echo htmlspecialchars($_SESSION["nombre_usuario"])?>"  required>
                <label for="apellido_vig">Apellido:</label>
                <input type="text" id="apellido_vig" name="apellido_vig" value="<?php echo htmlspecialchars($_SESSION["primer_apellido"])?>"  required>
            </div>

            <div class="form-group">
                <label for="comment">Comentario Vigilante:*</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
            </div>

            <input type="hidden" name="peticion_id" id="peticion_id" value="<?php echo $id_peticion?>">
 
            <button type="submit">Enviar Reporte</button>
        </form>

        <br>
        <!-- Formulario para cancelar reporte -->
        <form method="POST" action="cancelar_reporte_admin.php">
            <input type="hidden" name="id_peticion" value="<?php echo htmlspecialchars($row['id_peticion']); ?>">
            <button type="submit" style="background-color: #f44336; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer;">
                Cancelar Reporte
            </button>
        </form>

        <?php else: ?>
          <p>Objeto no encontrado</p>
        <?php endif; ?>
    </div>
    <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
</body>



<script>
    // Función para obtener la fecha y hora actual en formato compatible
    function setCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        // Formato "YYYY-MM-DDTHH:MM" para el campo datetime-local
        const formattedDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        document.getElementById('report-time').value = formattedDateTime;
    }

    // Ejecutar la función al cargar la página
    window.onload = setCurrentDateTime;
</script>

</html>

<?php   $stmt->close(); ?>