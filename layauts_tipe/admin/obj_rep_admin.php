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
    'nombre_objeto' => $_GET['nombre_objeto'] ?? '',
    'referencia_objeto'=> $_GET['referencia_objeto'] ?? '',
    //'vigilante_cedula' => $_GET['vigilante_cedula'] ?? ''
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
        .form-group {
        margin-bottom: 15px;
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

            .resultados div{
            margin: 5px;
            padding: 5px;
            text-align: center;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.5);
            border-radius: 5px;

            }

            select{
                padding: 5px;
                width: 100%;
                background-color: white;
                min-height: 40px;
                border-radius: 5px;
            }

    </style>
</head>
<body>
    <div class="layout">
    <?php include '../universal/header_admin.php'?>
        <section class="profile-sidebar">
            <div class="filter-form">
                <h1>Buscar Objetos Reportados</h1>
                <form method="GET" action="obj_rep_admin.php" id="formulario">
                    <label>Fecha de Reporte (Inicio):</label>
                    <input type="date" name="fecha_inicio" value="<?= htmlspecialchars($filters['fecha_inicio']) ?>">

                    <label>Fecha de Reporte (Fin):</label>
                    <input type="date" name="fecha_fin" value="<?= htmlspecialchars($filters['fecha_fin']) ?>">

                    <label>Referencia Objeto:</label>
                    <input type="text" name="referencia_objeto" value="<?= htmlspecialchars($filters['referencia_objeto']) ?>">

                    <label for="busqueda">Vigilante Encargado:</label>
                    <input type="text" placeholder="Buscar..."  name="vigilante_cedula" id="busqueda" autocomplete="off">
                    <div id="resultados" class="resultados"></div>

                    <input type="hidden" name="vigilante_encargado" id="vigilante_id" value="">

                    <div class="form-group">
                        <div id="mensaje-error"></div>
                    </div>


                    <label>ID de la Petición:</label>
                    <input type="text" name="id_peticion" value="<?= htmlspecialchars($filters['id_peticion']) ?>">

                    <label>Lugar Encontrado:</label>
                    <div class="form-group">
                        <select id="lugar_encontrado" name="lugar_encontrado" >
                            <option value="<?php if($filters['lugar_encontrado']){ echo htmlspecialchars($filters['lugar_encontrado']); } else{ echo ""; }?>" <?php if($filters['estado_reporte']){ echo "" ; } else{ echo "disabled"; } ?> selected><?php if($filters['lugar_encontrado']){ echo htmlspecialchars($filters['lugar_encontrado']); } else{ echo htmlspecialchars("Seleccione un lugar"); }?> </option>
                            <option value="Plaza de comidas">Plaza de comidas</option>
                            <option value="Biblioteca">Biblioteca</option>
                            <option value="L04">L04</option>
                            <option value="L06">L06</option>
                            <option value="L01">L01</option>
                            <option value="L02">L02</option>
                            <option value="L03">L03</option>
                            <option value="L05">L05</option>
                            <option value="L07">L07</option>
                            <option value="L08">L08</option>
                            <option value="L09">L09</option>
                            <option value="L10">L10</option>
                            <option value="L10">L10</option>
                            <option value="N01">N01</option>
                            <option value="N02">N02</option>
                            <option value="N03">N03</option>
                            <option value="N04">N04</option>
                            <option value="N05">N05</option>
                            <option value="N06">N06</option>
                            <option value="N07">N07</option>
                        <!-- Agregar más opciones según sea necesario -->
                        </select>
                    </div>
                

                    <label>Estado del Reporte:</label>

                    <div class="form-group">
                        <select id="estado_reporte" name="estado_reporte" >
                            <option value="<?php if($filters['estado_reporte']){ echo htmlspecialchars($filters['estado_reporte']); } else{ echo ""; }?>"  <?php if($filters['estado_reporte']){ echo "" ; } else{ echo "disabled"; } ?>  selected><?php if($filters['estado_reporte']){ echo htmlspecialchars($filters['estado_reporte']); } else{ echo "Seleccione un estado"; }?></option>
                            <option value="CREADO">Reportados por Vigilante</option>
                            <option value="ALMACENADO">Almacenados por adminitrador</option>
                            <option value="ENTREGADO">Entregados al usuario</option>
                        <!-- Agregar más opciones según sea necesario -->
                        </select>
                    </div>

                    <label>Nombre del Objeto:</label>
                    <input type="text" name="nombre_objeto" value="<?= htmlspecialchars($filters['nombre_objeto']) ?>">
                    

                    <button type="submit" id="verificar">Buscar</button>
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
                                    <h2><?= htmlspecialchars($row['nombre_objeto'] . " | " . $row['referencia_objeto'] ) ?></h2>
                                    <p>Reporte: <?= htmlspecialchars($row['fecha_reporte']) ?> | Lugar: <?= htmlspecialchars($row['lugar_encontrado']) ?></p>
                                    <p>Descripción: <?= htmlspecialchars($row['descripcion_objeto']) ?></p>
                                    <p><strong>Estado:</strong> <?= htmlspecialchars($row['estado_reporte']) ?> - <strong>Vigilante:</strong> <?= htmlspecialchars($row['vigilante_encargado']) ?></p>
                                    <a href="<?php if($row['estado_reporte'] == "CREADO"){ echo "detalle_reportados.php?id=" . $row['id_objeto'] ; } elseif($row['estado_reporte'] == "ALMACENADO"){ echo "detalle_almacenados.php?id=" . $row['id_objeto'] ; } else{ echo "detalle_entregados.php?id=" . $row['id_objeto'] ; } ?>">Leer más</a>
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

<script>

    // Ejecutar la función al cargar la página
   // window.onload = setCurrentDateTime;

    
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
                        document.getElementById('vigilante_id').value = id;

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


</script>

</html>

<?php
$stmt->close();
$conn->close();
?>