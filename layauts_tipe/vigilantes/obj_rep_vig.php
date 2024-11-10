<?php
session_start();
require_once '../../conexion.php'; // Conexión a la base de datos

// Verificar que el usuario es un vigilante
if ($_SESSION["type_user"] != "2") {
    header("location: /Proyecto_Grado/index.php");
    exit();
}

// Obtener el ID del vigilante en sesión
$vigilante_id = $_SESSION['usuario_id'];

// Consultar objetos reportados y almacenados por el vigilante
$sql_reportados = "
    SELECT referencia_objeto, fecha_reporte, nombre_objeto, descripcion_objeto, fotografia_objeto
    FROM objetos_reportados
    WHERE vigilante_encargado = ?";

$sql_almacenados = "
    SELECT oa.referencia_objeto, oa.fecha_almacenado, orp.nombre_objeto, orp.descripcion_objeto, orp.fotografia_objeto
    FROM objetos_almacenados oa
    JOIN objetos_reportados orp ON oa.referencia_objeto = orp.referencia_objeto
    WHERE oa.id_vigilante = ?";

$stmt_reportados = $conn->prepare($sql_reportados);
$stmt_almacenados = $conn->prepare($sql_almacenados);

$stmt_reportados->bind_param("i", $vigilante_id);
$stmt_almacenados->bind_param("i", $vigilante_id);

$stmt_reportados->execute();
$result_reportados = $stmt_reportados->get_result();

$stmt_almacenados->execute();
$result_almacenados = $stmt_almacenados->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetos Reportados y Almacenados</title>
    <link rel="stylesheet" href="../../styles/est_princ.css"> <!-- Asegúrate de que el archivo CSS está en la ruta correcta -->
    <style>
        body {
            background-color: #f5f5f5;
            background-color: #f5f5f5;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        main {
            display: flex;
            max-width: 1200px;
            gap: 20px;
            justify-content: center;
        }
        article {
            background-color: #F0F5F1;
            display: flex;
            margin-bottom: 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        article img {
            width: 30%;
            object-fit: cover;
        }
        .posts {
            margin: 10px;
        }

        .post-info {
            padding: 20px;
            width: 70%;
        }

        .post-info h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .post-info p {
            color: #777;
            margin-bottom: 10px;
        }

        .post-info a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include '../universal/header_vig.php'?>
<main>
    <section class="posts">
        <h1>Objetos Reportados</h1>

        <?php while ($row = $result_reportados->fetch_assoc()): ?>
            <article>
                <img src="data:image/jpeg;base64,<?= base64_encode($row['fotografia_objeto']) ?>" alt="Imagen del Objeto">
                <div class="post-info">
                    <h2><?= htmlspecialchars($row['nombre_objeto']) ?></h2>
                    <p><?= date("F j, Y", strtotime($row['fecha_reporte'])) ?> | Reportado</p>
                    <p><?= htmlspecialchars(substr($row['descripcion_objeto'], 0, 100)) ?>... <a href="#">Leer más</a></p>
                </div>
            </article>
        <?php endwhile; ?>

        <h1>Objetos Almacenados</h1>

        <?php while ($row = $result_almacenados->fetch_assoc()): ?>
            <article>
                <img src="data:image/jpeg;base64,<?= base64_encode($row['fotografia_objeto']) ?>" alt="Imagen del Objeto">
                <div class="post-info">
                    <h2><?= htmlspecialchars($row['nombre_objeto']) ?></h2>
                    <p><?= date("F j, Y", strtotime($row['fecha_almacenado'])) ?> | Almacenado</p>
                    <p><?= htmlspecialchars(substr($row['descripcion_objeto'], 0, 100)) ?>... <a href="#">Leer más</a></p>
                </div>
            </article>
        <?php endwhile; ?>
    </section>
</main>

<footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
</body>
</html>

<?php
$stmt_reportados->close();
$stmt_almacenados->close();
$conn->close();
?>
