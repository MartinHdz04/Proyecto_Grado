<?php
session_start();

//Comprueba si hay sesión iniciada
if(!isset($_SESSION['usuario_id'])){
    
     header("Location: /Proyecto_Grado/index.php");
 }   

if($_SESSION["type_user"] != "3"){
     header("location: /Proyecto_Grado/index.php");
     exit;
 }

 include '../../conexion.php';

 $usuario_id = $_SESSION['usuario_id'];

//Consultar datos del usuario
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

// Consultar objetos reportados y almacenados por el vigilante
$sql_reportados = "
    SELECT id_objeto, referencia_objeto, fecha_reporte, nombre_objeto, descripcion_objeto, fotografia_objeto
    FROM objetos_reportados ORDER BY id_objeto DESC";

$stmt_reportados = $conn->prepare($sql_reportados);

$stmt_reportados->execute();
$result_reportados = $stmt_reportados->get_result();


$arrayObjetos = [];

if($result_reportados->num_rows > 0){
    while($refs_objetos = $result_reportados->fetch_assoc()){
        $referencia = $refs_objetos['referencia_objeto'];
        $valor_objeto = $refs_objetos['valor_objeto'];
        $sql = "SELECT id_objeto, referencia_objeto, estado_reporte, fecha_reporte, nombre_objeto, descripcion_objeto, lugar_encontrado, fotografia_objeto 
        FROM objetos_reportados WHERE referencia_objeto = '$referencia'";
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
    <style>
        /* Estilo para el modal */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Fondo semitransparente */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            position: relative;
            background-color: #fff;
            padding: 20px;
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 5px;
        }

        .close-modal {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #333;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #d00;
        }
    </style>
</head>
<body>
    <div class="layout">
    <?php include '../universal/header_admin.php'?>
        <section class="profile-sidebar">
            <?php if ($foto_perfil): ?>
                <img src="data:image/png;base64,<?php echo base64_encode($foto_perfil); ?>" alt="Profile Picture" class="clickable-image">
            <?php else: ?>
                <img src="../../static/imgstest/imagen_no_disponible.png" alt="Profile Picture" class="clickable-image">
            <?php endif; ?>
            <h2><?php echo strtoupper($nombre_usuario); ?></h2>
            <p> VIGILANTE: <?php echo strtoupper(htmlspecialchars($user['primer_nombre']) . " " . htmlspecialchars($user['primer_apellido'])) ?></p>
            <p> TELÉFONO: <?php echo strtoupper(htmlspecialchars($user['telefono'])) ?></p>
            <p> CORREO: <?php echo strtoupper(htmlspecialchars($user['correo'])) ?></p>
            <div class="social-media">
                <button id="btn_reportar_est">Reportar</button>
                <script>
                    document.getElementById("btn_reportar_est").onclick = function(){
                        location.href = "reportar_admin.php";
                    };
                </script>
            </div>
        </section>
            <main>
            <h3>Todos los objetos</h3>
            <section class="posts">
                <?php if (!empty($arrayObjetos)): ?>
                    <?php foreach ($arrayObjetos as $objects): ?>
                        <article>
                                <img src="data:image/png;base64,<?php echo base64_encode($objects['fotografia']); ?>" alt="Objeto Encontrado" class="clickable-image">
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

            <!-- Modal para mostrar la imagen -->
            <div id="image-modal" class="modal">
                <span class="close-modal" id="close-modal">&times;</span>
                <div class="modal-content">
                    <img id="modal-image" src="" alt="Imagen ampliada">
                </div>
            </div>

            <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
    </div>   
    <script>
        // Obtener elementos del DOM
        const modal = document.getElementById("image-modal");
        const modalImage = document.getElementById("modal-image");
        const closeModal = document.getElementById("close-modal");
        const clickableImages = document.querySelectorAll(".clickable-image");

        // Agregar evento de clic a cada imagen
        clickableImages.forEach(img => {
            img.addEventListener("click", () => {
                modal.style.display = "flex"; // Mostrar el modal
                modalImage.src = img.src; // Asignar la imagen al modal
            });
        });

        // Cerrar el modal al hacer clic en la "X"
        closeModal.addEventListener("click", () => {
            modal.style.display = "none";
        });

        // Cerrar el modal al hacer clic fuera del contenido
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });
    </script>
 
</body>
</html>

<?php
$stmt->close();
$stmt_reportados->close();
$conn->close();
?>