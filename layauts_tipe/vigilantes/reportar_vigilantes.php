<?php
session_start();

//Comprueba si hay sesión iniciada
if(!isset($_SESSION['usuario_id'])){
    header("Location: /Proyecto_Grado/index.php");
}

if($_SESSION["type_user"] != "2"){
    header("location: /Proyecto_Grado/index.php");
}

// Verificar si el usuario tiene nombres guardados
$nombre = isset($_SESSION['nombre_usuario']) ? $_SESSION['nombre_usuario'] : '';
$apellido = isset($_SESSION['primer_apellido']) ? $_SESSION['primer_apellido'] : '';
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found</title>
    <link rel="stylesheet" href="../../styles/est_princ.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.4.1/socket.io.min.js"></script>
    <script>
        const socket = io('http://localhost:3000');

        socket.on('notificacion', function(data) {
            // Aquí puedes mostrar la notificación en la interfaz de usuario
            alert(data.mensaje + ' a las ' + data.hora + 'en el lugar: '+ data.lugar);
        });
    </script>
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
  </style>

</head>

<body class="body_reportar">
  <?php include '../universal/header_vig.php'?>

    <div class="form-container">
        <form enctype="multipart/form-data" method="POST" onsubmit="return validateForm()" action="carga_vigilantes.php">
            <div class="form-group">
                <label for="product-image">Fotografía del Producto*</label>
                <input type="file" id="product-image" name="product-image" accept="image/*">
          </div>

            <div class="form-group">
                <label for="report-time">Hora del Reporte</label>
                <input type="datetime-local" id="report-time" name="report-time" disabled required>
            </div>

            <select id="report-location" name="report-location" required>
                <option value="" disabled selected>Seleccione un lugar *</option>
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

            <div class="form-group">
                <br>
                <label for="name">Nombre del objeto</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <h3>Persona quien recibe:</h3>
                <br>
                <label for="nombre_vig">Nombre:</label>
                <input type="text" id="nombre_vig" name="nombre_vig" value="<?php echo htmlspecialchars($nombre); ?>" required>
                <label for="apellido_vig">Apellido:</label>
                <input type="text" id="apellido_vig" name="apellido_vig" value="<?php echo htmlspecialchars($apellido); ?>" required>
            </div>

            <div class="form-group">
                <label for="comment">Comentario Vigilante:</label>
                <textarea id="comment" name="comment" rows="4" required></textarea>
            </div>
 
            <button type="submit">Enviar Reporte</button>
        </form>
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