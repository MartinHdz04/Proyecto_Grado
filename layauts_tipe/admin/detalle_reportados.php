<?php

session_start();

include '../../conexion.php';

if($_SESSION["type_user"] != "3"){
  header("location: /Proyecto_Grado/index.php");
}



$id_objeto_r = $_GET["id"];
$sql = "SELECT * FROM objetos_reportados WHERE id_objeto= $id_objeto_r";


$result = $conn->query($sql);
if($result->num_rows > 0){

  $row = $result->fetch_assoc();
  if($row["estado_reporte"] == "CREADO"){
    
    $vigilanteId = $row['vigilante_encargado'];
    $sqlEntregado = "SELECT * FROM vigilantes WHERE id_vigilante= '$vigilanteId'";
    $resultadoEntregado = $conn->query($sqlEntregado);
    $rowVigilante = $resultadoEntregado->fetch_assoc();
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

                <h3>Vigilante quien recibio:</h3>
                <br>

                <div class="form-group">
                <label>Nombre del vigilante</label>
                <input type="text"  value="<?php echo htmlspecialchars($rowVigilante['primer_Nombre']. " ". $rowVigilante['segundo_Nombre'])?>" disabled >
            </div>

            <div class="form-group">
                <label>Apellido del vigilante</label>
                <input type="text"  value="<?php echo htmlspecialchars($rowVigilante['primer_apellido']. " ". $rowVigilante['segundo_Apellido'])?>" disabled >
            </div>

            <div class="form-group">
                <label>Cedula del vigilante</label>
                <input type="text"  value="<?php echo htmlspecialchars($rowVigilante['cedula']); ?>" disabled >
            </div>
            
            <div class="form-group">
                <label for="comment">Comentario Vigilante:</label>
                <textarea id="comment" name="comment" rows="4" required disabled><?php echo htmlspecialchars($row["comentario_vigilante"]) ?></textarea>
            </div>
            
        </form>

        <?php if($row['estado_reporte'] == 'CREADO'): ?>

          <form id="formulario" enctype="multipart/form-data" method="POST" onsubmit="return validateForm()" action="almacenar.php">

            

              

              <input type="hidden" name="vigilante_id" id="vigilante_id" value="<?=  htmlspecialchars($rowVigilante['id_vigilante'])?>">
              <input type="hidden" name="comentario_vigilante" id="comentario_vigilante" value="<?php echo htmlspecialchars($row["comentario_vigilante"]) ?>">
              <input type="hidden" name="referencia_objeto" id="referencia_objeto" value="<?php echo htmlspecialchars($row["referencia_objeto"])?>">


             

              <div class="form-group">
                <label>Elija un Valor al objeto:</label>
                <select name="valor_objeto" id="valor_objeto">
                    <option value="" disabled selected> Elija un tipo de valor* </option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
              </div>
  
              <button type="submit" id="verificar">Almacenar Objeto</button>

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


</html>

<?php   $stmt->close(); ?>