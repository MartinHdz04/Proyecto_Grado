<?php
session_start();

// Verificación de sesión y redirección
if (!isset($_SESSION['usuario_id'])) {
    header("Location: /Proyecto_Grado/index.php");
    exit;
}

if ($_SESSION["type_user"] != "3") {
    header("location: /Proyecto_Grado/index.php");
    exit;
}

$usuario_id = $_SESSION["usuario_id"];
include '../../conexion.php';

// Configuración de filtros
$filters = [
    'fecha_inicio' => $_GET['fecha_inicio'] ?? '',
    'fecha_fin' => $_GET['fecha_fin'] ?? '',
    'id_usuario' => $_GET['id_usuario'] ?? '',
    'id_peticion' => $_GET['id_peticion'] ?? '',
    'lugar_peticion' => $_GET['lugar_peticion'] ?? '',
    'estado_peticion' => $_GET['estado_peticion'] ?? ''
];

// Construcción de la consulta dinámica
$query = "SELECT * FROM peticiones WHERE 1=1";
$params = [];
$types = "";

// Aplicar filtros de rango de fecha si se especifica
if (!empty($filters['fecha_inicio']) && !empty($filters['fecha_fin'])) {
    $query .= " AND fecha_creacion BETWEEN ? AND ?";
    $params[] = $filters['fecha_inicio'];
    $params[] = $filters['fecha_fin'];
    $types .= "ss";
}

// Aplicar filtros de búsqueda según los demás valores recibidos
foreach ($filters as $key => $value) {
    if (!empty($value) && $key !== 'fecha_inicio' && $key !== 'fecha_fin') {
        $query .= " AND $key LIKE ?";
        $params[] = "%$value%";
        $types .= "s";
    }
}

$query .= " ORDER BY id_peticion DESC"; // Agregar ORDER BY solo después de agregar condiciones

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($query);
mysqli_error($conn);
if ($stmt) {
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Error en la consulta: " . $conn->error);
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
                <h1>Buscar Peticiones</h1>
                <form method="GET" action="peticion.php">
                    <label>Fecha de Reporte (Inicio):</label>
                    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filters['fecha_inicio']) ?>">

                    <label>Fecha de Reporte (Fin):</label>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filters['fecha_fin']) ?>">

                    <label>ID del usuario:</label>
                    <input type="text" name="id_usuario" value="<?= htmlspecialchars($filters['id_usuario']) ?>">

                    <label>ID de la Petición:</label>
                    <input type="text" name="id_peticion" value="<?= htmlspecialchars($filters['id_peticion']) ?>">

                    <label>Lugar Encontrado:</label>
                    <input type="text" name="lugar_peticion" value="<?= htmlspecialchars($filters['lugar_peticion']) ?>">

                    <label>Estado de la peticion:</label>
                    <input type="text" name="estado_peticion" value="<?= htmlspecialchars($filters['estado_peticion']) ?>">

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
                                <h2 class="<?php echo strtolower($row['estado_peticion']) == 'abierto' ? 'estado-abierto' : 'estado-cerrado'; ?>">
                                    <?php echo "Estado: ", ucfirst(strtolower($row['estado_peticion'])); ?>
                                </h2>
                                <p><strong>Descripcion:</strong><?php echo strlen($row['comentarios_peticion']) > 50 ? htmlspecialchars(substr($row['comentarios_peticion'], 0, 50)) . '...' : htmlspecialchars($row['comentarios_peticion']); ?></p>
                                <p><strong>Fecha de creación:</strong> <?php echo htmlspecialchars($row['fecha_creacion']); ?></p>
                                <p><strong>Lugar del Encuentro:</strong> <?php echo htmlspecialchars($row['lugar_peticion']); ?></p>
                                <a href="detalle_peticion.php?id=<?php echo $row['id_peticion']; ?>">Ver más detalles</a>
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

