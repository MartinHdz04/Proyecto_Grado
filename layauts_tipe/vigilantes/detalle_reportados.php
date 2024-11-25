<?php

session_start();

include '../../conexion.php';

if($_SESSION["type_user"] != "2"){
  header("location: /Proyecto_Grado/index.php");
}



$id_objeto_r = $_GET["id"];
$sql = "SELECT * FROM objetos_reportados WHERE id_objeto= $id_objeto_r";


$result = $conn->query($sql);
if($result->num_rows > 0){

  $row = $result->fetch_assoc();
  if($row["estado_reporte"] == "ENTREGADO"){
    
    $referencia_objeto = $row['referencia_objeto'];
    $sqlEntregado = "SELECT * FROM objetos_entregados WHERE referencia_objeto= '$referencia_objeto'";
    $resultadoEntregado = $conn->query($sqlEntregado);
    if($resultadoEntregado > 0){
      
      $rowEntregado = $resultadoEntregado->fetch_assoc();
      $usuarioRef = $rowEntregado['referencia_usuario'];
      $imagenEntregado = $rowEntregado['fotografia_entrega'];
      
      $sqlUser = "SELECT cedula, primer_nombre, primer_apellido FROM usuarios WHERE referencia_usuario = '$usuarioRef'";
      $resultadoUser = $conn->query($sqlUser);
      if($resultadoUser > 0){
        $rowUser = $resultadoUser->fetch_assoc();
        $cedulaUsuario = $rowUser['cedula'];
        $Nombre = $rowUser['primer_nombre'] . " " . $rowUser['primer_apellido'];
      }
  
    }
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

    .form-group img{
      align-self: center;
    }

    .resultados div{
    margin: 5px;
    padding: 5px;
    text-align: center;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
    border-radius: 5px;

    }

    .imagen{
      text-align: center;
    }

  </style>

</head>

<body class="body_reportar">
  <?php include '../universal/header_vig.php'?>

    <div class="form-container">
        <?php if ($row): ?>
        <form action="obj_rep_vig.php">
            <div class="form-group imagen">
              
                <img src="data:image/jpeg;base64,<?= base64_encode($row['fotografia_objeto']) ?>" alt="Imagen del Objeto">

            </div>

            <div class="form-group">
                <label>Hora del Reporte</label>
                <input type="text" value="<?php echo htmlspecialchars($row['fecha_reporte'])?>" disabled >
            </div>

            <div class="form-group">
                <label>Lugar del Encuentro</label>
                <input type="text" value="<?php echo htmlspecialchars($row['lugar_encontrado'])?>" disabled >
            </div>

            <div class="form-group">
                <label>Nombre del objeto</label>
                <input type="text"  value="<?php echo htmlspecialchars($row['nombre_objeto'])?>" disabled >
            </div>

            <div class="form-group">
                <label for="description">Descripción del Objeto</label>
                <textarea id="description" name="description" rows="4" required disabled><?php echo htmlspecialchars($row["descripcion_objeto"]) ?></textarea>
            </div>

            <div class="form-group">
                <h3>Persona quien recibe:</h3>
                <br>
                <label for="nombre_vig">Nombre:</label>
                <input type="text" id="nombre_vig" name="nombre_vig" value="<?php echo htmlspecialchars($_SESSION["nombre_usuario"])?>"  required>
                <label for="apellido_vig">Apellido:</label>
                <input type="text" id="apellido_vig" name="apellido_vig" value="<?php echo htmlspecialchars($_SESSION["primer_apellido"])?>"  required>
            </div>

            <div class="form-group">
                <label for="comment">Comentario Vigilante:</label>
                <textarea id="comment" name="comment" rows="4" required disabled><?php echo htmlspecialchars($row["comentario_vigilante"]) ?></textarea>
            </div>
            <?php if($row['estado_reporte'] == 'ENTREGADO'): ?>
              <div class="form-group imagen">
                  <label>Imagen de la entrega:</label>
                  <img src="data:image/jpeg;base64,<?= base64_encode($rowEntregado['fotografia_entrega'])?>" alt="Imagen del Objeto">
              </div>
              <div class="form-group">
                <label>Cedula a quien se le entrego:</label>
                <input type="text"  value="<?php echo htmlspecialchars($cedulaUsuario)?>" disabled >
              </div>
              <div class="form-group">
                <label>Nombre a quien se le entrego:</label>
                <input type="text"  value="<?php echo htmlspecialchars($Nombre)?>" disabled >
              </div>

              <button type="submit" id="verificar">< Volver</button>
            <?php endif; ?>
        </form>

        <?php if($row['estado_reporte'] != 'ENTREGADO'): ?>

          <form id="formulario" enctype="multipart/form-data" method="POST" onsubmit="return validateForm()" action="entregar.php">

              <div class="form-group">
                  <label for="product-image">Foto de la Entrega*</label>
                  <input type="file" id="product-image" name="product-image" accept="image/*">
              </div>

              <div class="form-group">
              <label for="busqueda">Cedula a quien se le entrega: *</label>
              <input type="text" id="busqueda" name="cedula" placeholder="Buscar..." autocomplete="off">
              <div id="resultados" class="resultados"></div>


              </div>

              <input type="hidden" name="estudiante_id" id="estudiante_id" value="">
              <input type="hidden" name="objeto_id" id="objeto_id" value="<?php echo $id_objeto_r?>">
              <input type="hidden" name="referencia_objeto" id="referencia_objeto" value="<?php echo htmlspecialchars($row["referencia_objeto"])?>">


              <div class="form-group">
                <div id="mensaje-error"></div>
              </div>
  
              <button type="submit" id="verificar">Enviar Reporte</button>

          </form>
        <?php endif; ?>
        <?php else: ?>
          <p>Objeto no encontrado</p>
        <?php endif; ?>
    </div>
    <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
</body>

<script>
      // Función para manejar la entrada de texto en el buscador
      document.getElementById('busqueda').addEventListener('input', function() {
          let query = this.value;

          console.log(query);
          // Crear una solicitud AJAX
          let xhr = new XMLHttpRequest();
          xhr.open('GET', 'buscador.php?query=' + encodeURIComponent(query), true);
          xhr.onload = function() {
              if (xhr.status === 200) {
                  document.getElementById('resultados').innerHTML = xhr.responseText;
                  let results = document.querySelectorAll('.result');

                  // Recorrer todos los resultados y añadir un listener de clic a cada uno
                  results.forEach(function(result) {
                      result.addEventListener('click', function() {
                          // Obtener el valor del div que se hizo clic y pegarlo en el input de id "busqueda"
                          let valorSeleccionado = result.id;
                          document.getElementById('busqueda').value = valorSeleccionado;

                          // Obtener el valor del atributo data-id del div que se hizo clic
                          let id = result.dataset.iduser;

                          // Pegar el valor del data-id en el input hidden con id "estudiante_id"
                          document.getElementById('estudiante_id').value = id;

                          // Eliminar todos los divs con la clase "result"
                          let allResults = document.querySelectorAll('.result');
                          allResults.forEach(function(div) {
                              div.remove(); // Remueve cada div del DOM
                          });
                      });
                  });
              }
          };
          xhr.send();
      });

    // Obtener el formulario y el botón de verificación
    let formulario = document.getElementById('formulario');
    let botonVerificar = document.getElementById('verificar');

    // Escuchar el evento submit del formulario
    formulario.addEventListener('submit', function(event) {
        // Prevenir el envío del formulario por defecto
        event.preventDefault();

        //console.log(botonVerificar);
        // Obtener el valor del input con el id "estudiante_id"
        let cedula = document.getElementById('busqueda').value;

        

        // Realizar la verificación a través de AJAX
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'verificar_cedula.php?cedula=' + encodeURIComponent(cedula), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Respuesta desde el servidor
                let respuesta = xhr.responseText;

                if (respuesta.trim() === 'existe') {
                    //Si la cédula existe, enviar el formulario
                    formulario.submit();
                } else {
                    console.log(respuesta)
                    // Si la cédula no existe, mostrar el mensaje de error
                    let mensajeError = document.getElementById('mensaje-error');
                    mensajeError.style.display = 'block';
                    mensajeError.textContent = 'Error: La cédula no se encuentra en la base de datos.';
                }
            }
        };
        xhr.send();
    });

      
</script>

</html>

<?php   $stmt->close(); ?>