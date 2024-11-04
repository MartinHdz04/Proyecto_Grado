<?php

session_start();
$usuario_id = $_SESSION["usuario_id"];
// Verificar si el usuario ha iniciado sesión
// Incluir archivo de conexión
include '../../conexion.php';

// Obtener los objetos encontrados de la tabla peticiones
$sql = "SELECT id_peticion, comentarios_peticion, fecha_creacion, estado_peticion, lugar_peticion FROM peticiones WHERE id_usuario= $usuario_id ORDER BY id_peticion DESC";
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
    </style>
</head>
<body class="body_reportar">
    <header>    
        <nav>
            <h1>Lost & Found EAN</h1>
            <ul>
                <li><a href="/Proyecto_Grado/index.php">Inicio</a></li>
                <li><a href="objetos_abiertos.php">Objetos reclamados</a></li>
                <li><a href="../universal/logout.php">Cerrar Sesion</a></li>
            </ul>
        </nav>
    </header>

    <main>
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
                        <a href="detalle_objeto.php?id=<?php echo $row['id_peticion']; ?>">Ver más detalles</a>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No hay objetos encontrados disponibles en este momento.</p>
            <?php endif; ?>
        </section>
    </main>
</body>
</html>

<?php
$conn->close();
?>

