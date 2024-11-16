<?php

session_start();
$usuario_id = $_SESSION["usuario_id"];
// Verificar si el usuario ha iniciado sesión
// Incluir archivo de conexión
include '../../conexion.php';

// Obtener los objetos encontrados de la tabla peticiones
$sql = "SELECT id_peticion, comentarios_peticion, fecha_creacion, lugar_peticion,estado_peticion FROM peticiones WHERE estado_peticion= 'abierto' ORDER BY id_peticion DESC";
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
    <?php include '../universal/header_vig.php'?>
    <main>

    <h2 style="text-align: center;">¡Peticiones Pendientes!</h2>
        <section class="posts">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <article>
                        <h2 class="<?php echo strtolower($row['estado_peticion']) == 'abierto' ? 'estado-abierto' : 'estado-cerrado'; ?>">
                            <?php echo "Estado: ", ucfirst(strtolower($row['estado_peticion'])); ?>
                        </h2>
                        <p><strong>Descripcion:</strong><?php echo strlen($row['comentarios_peticion']) > 50 ? htmlspecialchars(substr($row['comentarios_peticion'], 0, 50)) . '...' : htmlspecialchars($row['comentarios_peticion']); ?></p>
                        <p><strong>Fecha de creación:</strong> <?php echo htmlspecialchars($row['fecha_creacion']); ?></p>
                        <p><strong>Lugar del Encuentro:</strong> <?php echo htmlspecialchars($row['lugar_peticion']); ?></p>
                        <a href="detalle_peticion.php?id=<?php echo $row['id_peticion']; ?>">Ver más detalles</a>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay objetos encontrados disponibles en este momento.</p>
            <?php endif; ?>
        </section>
    </main>
    <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
</body>
</html>

<?php
$conn->close();
?>

