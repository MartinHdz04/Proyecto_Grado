<?php

session_start();

if($_SESSION["type_user"] != "1"){
  header("location: /Proyecto_Grado/index.php");
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
        <form action="Reporte_estudiante.php" method="POST" onsubmit="return validateForm()">

            <div class="form-group">
                <label for="report-time">Hora del Reporte*</label>
                <input type="datetime-local" id="report-time" name="report-time" disabled>
            </div>
            <div class="form-group">
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
                <option value="L10">L10</option>
                <option value="N01">N01</option>
                <option value="N02">N02</option>
                <option value="N03">N03</option>
                <option value="N04">N04</option>
                <option value="N05">N05</option>
                <option value="N06">N06</option>
                <option value="N07">N07</option>
              <!-- Agregar más opciones según sea necesario -->
              </select>
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto <span class="span_descripcion">--opcional--</span></label>
                <textarea id="description" name="description" rows="4" ></textarea>
            </div>

            <button type="submit">Enviar Reporte (Llamar Vigilante)</button>
        </form>
    </div>
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