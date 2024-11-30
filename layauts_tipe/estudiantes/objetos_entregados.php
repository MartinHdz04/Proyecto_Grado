<?php


session_start();
$usuario_id = $_SESSION["usuario_id"];
$referencia_user = $_SESSION["referencia_user"];
// Verificar si el usuario ha iniciado sesión
// Incluir archivo de conexión
include '../../conexion.php';

// Obtener los objetos encontrados de la tabla peticiones
$sql = "SELECT referencia_objeto, fecha_entrega, fotografia_entrega FROM objetos_entregados WHERE referencia_usuario = $referencia_user ORDER BY id_entrega DESC";


$result = $conn->query($sql);



?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetos Abiertos</title>
    <link rel="stylesheet" href="../../styles/est_princ.css">
    <style>
        .posts {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            gap: 20px;
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

        .posts article {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: white;
            padding: 20px;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .posts article h2 {
            margin-top: 0;
        }

        .posts article p {
            margin-bottom: 10px;
        }

        .posts article a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }

        .posts article a:hover {
            text-decoration: underline;
        }

        .estado-abierto {
            color: green;
            text-transform: capitalize;
        }

        .estado-cerrado {
            color: red;
            text-transform: capitalize;
        }

        /* Estilos para dispositivos móviles */
        @media (max-width: 768px) {
            .posts article {
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .posts {
                padding: 10px;
            }

            .posts article {
                padding: 15px;
            }
            .body-reportar {
                font-family: Arial, sans-serif;
                display: flex;
                justify-content: center;
                height: 100vh; /* Hace que el body ocupe toda la ventana */
                margin: 0;
            }
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

    <main>
            <?php if ($result->num_rows > 0):  ?>
                <?php while ($row = $result->fetch_assoc()):?>
                    <article>
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['fotografia_entrega']) ?>" alt="Imagen del Objeto" class="clickable-image">
                        <div class="post-info" style="display: flex; flex-direction: column; justify-content: center;">
                            <h2><?= htmlspecialchars($row['referencia_objeto']) ?></h2>
                            <p><strong>Fecha de entrega:</strong> <?= htmlspecialchars($row['fecha_entrega']) ?></p>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No se encontraron resultados.</p>
            <?php endif; ?>
    </main>
    <footer>
        <h2>Lost & Found EAN copy Rigt 2024</h2>
    </footer>
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
$conn->close();
?>

