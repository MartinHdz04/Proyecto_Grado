<?php
session_start();

//Comprueba si hay sesión iniciada
if(!isset($_SESSION['usuario_id'])){
    
    header("Location: /Proyecto_Grado/index.php");
}   

if($_SESSION["type_user"] != "2"){
    header("location: /Proyecto_Grado/index.php");
    exit;
}

include '../../conexion.php';

$usuario_id = $_SESSION['usuario_id'];

// Consultar datos del usuario
$sql = "SELECT primer_nombre, primer_apellido, telefono, correo, user_name, foto_perfil FROM vigilantes WHERE id_vigilante = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nombre_usuario = htmlspecialchars($user['user_name']);
    $informacion_usuario = "Nombre: " . htmlspecialchars($user['primer_nombre']) . " " . htmlspecialchars($user['primer_apellido']) . "<br>Teléfono: " . htmlspecialchars($user['telefono']) . "<br>Correo: " . htmlspecialchars($user['correo']);
    $foto_perfil = $user['foto_perfil'];
} else {
    $nombre_usuario = "Usuario no encontrado";
    $informacion_usuario = "No hay información disponible.";
    $foto_perfil = null;
}

$sql = "SELECT referencia_objeto,valor_objeto FROM objetos_almacenados ORDER BY id_almacenado DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result1 = $stmt->get_result();

$arrayObjetos = [];

if($result->num_rows > 0){
    while($refs_objetos = $result1->fetch_assoc()){
        $referencia = $refs_objetos['referencia_objeto'];
        $valor_objeto = $refs_objetos['valor_objeto'];
        $sql = "SELECT id_objeto, referencia_objeto, estado_reporte, fecha_reporte, nombre_objeto, descripcion_objeto, lugar_encontrado, fotografia_objeto 
        FROM objetos_reportados WHERE estado_reporte = 'CREADO' and referencia_objeto = '$referencia'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            while($objecto = $result->fetch_assoc()){
                array_push($arrayObjetos,[
                    "valor" => $valor_objeto,
                    "Nombre" => $objecto['nombre_objeto'],
                    "Referencia" => $objecto['referencia_objeto'],
                    "Fecha" => $objecto['fecha_reporte'],
                    "descripcion" => $objecto['descripcion_objeto'],
                    "lugar" => $objecto['lugar_encontrado'],
                    "fotografia" => $objecto['fotografia_objeto']
                ]);
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
</head>
<body>
    <div class="layout">
    <?php include '../universal/header_vig.php'?>
        <section class="profile-sidebar">
            <?php if ($foto_perfil): ?>
                <img src="data:image/png;base64,<?php echo base64_encode($foto_perfil); ?>" alt="Profile Picture">
            <?php else: ?>
                <img src="../../static/imgstest/imagen_no_disponible.png" alt="Profile Picture">
            <?php endif; ?>
            <h2><?php echo strtoupper($nombre_usuario); ?></h2>
            <p> VIGILANTE: <?php echo strtoupper(htmlspecialchars($user['primer_nombre']) . " " . htmlspecialchars($user['primer_apellido'])) ?></p>
            <p> TELÉFONO: <?php echo strtoupper(htmlspecialchars($user['telefono'])) ?></p>
            <p> CORREO: <?php echo strtoupper(htmlspecialchars($user['correo'])) ?></p>
            <div class="social-media">
                <button id="btn_reportar_est">Reportar</button>
                <script>
                    document.getElementById("btn_reportar_est").onclick = function(){
                        location.href = "reportar_vigilantes.php";
                    };
                </script>
            </div>
        </section>
            <main>
            <h3>¡Pegunta por el objeto en Administracion!</h3>
            <section class="posts">
                <?php if (!empty($arrayObjetos)): ?>
                    <?php foreach ($arrayObjetos as $objects): ?>
                        <article>
                            <?php if ($objects['fotografia'] and intval($objects['valor']) == 1):?>
                                <img src="data:image/png;base64,<?php echo base64_encode($objects['fotografia']); ?>" alt="Objeto Encontrado">
                            <?php else: $objects['lugar'] = "Almacenado"?>
                                <img src="../../static/imgstest/imagen_no_disponible.png" alt="Objeto Encontrado" width="20" height="20">
                            <?php endif; ?>
                            <div class="post-info">
                                <h2><?php echo strtoupper($objects['Nombre']) . " - " . strtoupper($objects['Referencia']); ?></h2>
                                <p>Fecha del reporte: <?php echo strtoupper($objects['Fecha']) . " | " . strtoupper($objects['lugar']); ?></p>
                                <p><?php echo strtoupper($objects['descripcion'])?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se encontraron objetos activos reportados en este momento.</p>
                <?php endif; ?>
            </section>
            </main>
            <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
    </div>    
</body>
</html>