<?php 
session_start();

// Comprueba si hay sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Proyecto_Grado/index.php");
    exit;
}

// Comprueba el tipo de usuario
if ($_SESSION["type_user"] != "3") {
    header("location: /Proyecto_Grado/index.php");
    exit;
}

include '../../conexion.php'; 

$usuario_id = $_SESSION['usuario_id'];

// Configuración de filtros
$filters = [
    'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
    'fecha_fin' => $_GET['fecha_fin'] ?? '',
    'vigilante_encargado' => $_GET['vigilante_encargado'] ?? '',
    'id_peticion' => $_GET['id_peticion'] ?? '',
    'lugar_encontrado' => $_GET['lugar_encontrado'] ?? '',
    'estado_reporte' => $_GET['estado_reporte'] ?? '',
    'nombre_objeto' => $_GET['nombre_objeto'] ?? ''
];

// Construcción de la consulta dinámica
$query = "SELECT * FROM objetos_reportados WHERE 1=1";
$params = [];
$types = "";

// Aplicar filtros de rango de fecha si se especifica
if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
    $query .= " AND fecha_reporte BETWEEN ? AND ?";
    $params[] = $filters['fecha_inicio'];
    $params[] = $filters['fecha_fin'];
    $types .= "ss"; // Dos strings para las fechas
}


// Aplicar filtros de búsqueda según los demás valores recibidos
foreach ($filters as $key => $value) {
    if (!empty($value) && $key !== 'fecha_inicio' && $key !== 'fecha_fin') {
        $query .= " AND $key LIKE ?";
        $params[] = "%$value%";
        $types .= "s"; // Define el tipo de dato como string
    }
}

$query .= " ORDER BY id_objeto DESC"; // Agregar ORDER BY solo después de agregar condiciones

// Preparar y ejecutar la consulta solo si hay conexión
if ($stmt = $conn->prepare($query)) {
    // Vincular parámetros si existen
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error: No se pudo preparar la consulta.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Objetos</title>
    <link rel="stylesheet" href="../../styles/est_princ.css">
    <style>

        /* Estilos para el formulario de filtros */
        .filter-form {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            margin: 20px 0;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .filter-form h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        .filter-form label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .filter-form input[type="text"],
        .filter-form input[type="date"],
        .filter-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .filter-form button {
            background-color: #569644;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }

        .filter-form button:hover {
            background-color: #569656;
        }

        /* Responsive design */
        @media (max-width: 600px) {
            .filter-form, .results {
                width: 90%;
                padding: 15px;
            }

            .filter-form h1 {
                font-size: 20px;
            }

            .filter-form label {
                font-size: 14px;
            }

                .filter-form input[type="text"],
                .filter-form input[type="date"],
                .filter-form button {
                    font-size: 14px;
                }
            }
    </style>
</head>
<body>
    <div class="layout">
    <?php include '../universal/header_admin.php'?>
        <section class="profile-sidebar">
            <div class="filter-form">
                <h1>Buscar Objetos Reportados</h1>
                <form method="GET" action="obj_rep_admin.php">
                    <label>Fecha de Reporte (Inicio):</label>
                    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filters['fecha_inicio']) ?>">

                    <label>Fecha de Reporte (Fin):</label>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filters['fecha_fin']) ?>">

                    <label>Vigilante Encargado:</label>
                    <input type="text" name="vigilante_encargado" value="<?= htmlspecialchars($filters['vigilante_encargado']) ?>">

                    <label>ID de la Petición:</label>
                    <input type="text" name="id_peticion" value="<?= htmlspecialchars($filters['id_peticion']) ?>">

                    <label>Lugar Encontrado:</label>
                    <input type="text" name="lugar_encontrado" value="<?= htmlspecialchars($filters['lugar_encontrado']) ?>">

                    <label>Estado del Reporte:</label>
                    <input type="text" name="estado_reporte" value="<?= htmlspecialchars($filters['estado_reporte']) ?>">

                    <label>Nombre del Objeto:</label>
                    <input type="text" name="nombre_objeto" value="<?= htmlspecialchars($filters['nombre_objeto']) ?>">

                    <button type="submit">Buscar</button>
                </form>
            </div>
        </section>
            <main>
                <h3>Todos los objetos</h3>
                <section class="posts">
        
                    <h2>Resultados de la Búsqueda</h2>
                    <?php if (isset($results) && count($results) > 0): ?>
                        <?php foreach ($results as $row): ?>
                            <article>
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['fotografia_objeto']) ?>" alt="Imagen del Objeto">
                                <div class="post-info">
                                    <h2><?= htmlspecialchars($row['nombre_objeto']) ?></h2>
                                    <p>Reporte: <?= htmlspecialchars($row['fecha_reporte']) ?> | Lugar: <?= htmlspecialchars($row['lugar_encontrado']) ?></p>
                                    <p>Descripción: <?= htmlspecialchars($row['descripcion_objeto']) ?></p>
                                    <p><strong>Estado:</strong> <?= htmlspecialchars($row['estado_reporte']) ?> - <strong>Vigilante:</strong> <?= htmlspecialchars($row['vigilante_encargado']) ?></p>
                                    <a href="#">Leer más</a>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No se encontraron resultados.</p>
                    <?php endif; ?>
            

                </section>
            </main>
            <footer>
            <h2>Lost & Found EAN copy Rigt 2024</h2>
        </footer>
    </div>    
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>