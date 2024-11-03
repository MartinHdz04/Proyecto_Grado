<?php



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
  </style>

</head>

<body class="body_reportar">
    <header>    
        <nav>
            <h1>Lost & Found EAN</h1>
            <ul>
                <li><a href="#">Inicio</a></li>
                <li><a href="#">Objetos reclamados</a></li>
                <li><a href="#">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <div class="form-container">
        <form>
            <div class="form-group">
                <label for="product-image">Fotografía del Producto</label>
                <input type="file" id="product-image" name="product-image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="report-time">Hora del Reporte</label>
                <input type="datetime-local" id="report-time" name="report-time" disabled>
            </div>

            <select id="report-location" name="report-location" required>
                    <option value="">Seleccione un lugar</option>
                    <option value="recepción">Recepción</option>
                    <option value="biblioteca">Biblioteca</option>
                    <option value="cafetería">Cafetería</option>
                    <option value="gimnasio">Gimnasio</option>
                    <option value="salón de clases">Salón de Clases</option>
                    <!-- Agregar más opciones según sea necesario -->
            </select>

            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="recipient">Persona a quien se le entregó el objeto</label>
                <input type="text" id="recipient" name="recipient" required>
            </div>

            <button type="submit">Enviar Reporte</button>
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