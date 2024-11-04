<?php
session_start();

if(!isset($_SESSION['usuario_id'])){
    header("Location: /Proyecto_Grado");
}

if($_SESSION["type_user"] != "1"){
    header("location: /Proyecto_Grado/index.php");
}

include '../../conexion.php';



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
            <section class="profile-sidebar">
                <img src="../../static/imgstest/One-Caveman-Selfie.png" alt="Profile Picture">
                <h2>(Username)</h2>
                <p>(Información acerca del usuario, como su carrera edad, telefono, etc...):</h3>
                <ul>
                    <li><a href="#">(Ejemplo de reporte: L918 - Saco gris)</a></li>
                    <li><a href="#">(Ejemplo de reporte: L918 - Saco gris)</a></li>
                </ul>
                <div class="social-media">
                <button id="btn_reportar_est">Reportar</button>
                    <script>
                        document.getElementById("btn_reportar_est").onclick = function(){
                            location.href = "reportar_estudiantes.php";
                        };
                    </script>
                    
                </div>
            </section>
            <main>
                <section class="posts">
                    <article>
                        <img src="../static/imgstest/1.jpg" alt="Post Image">
                        <div class="post-info">
                            <h2>Coffee sugar, chicory seasonal espresso barista americano</h2>
                            <p>JUNIO 25, 2020 | MÚSICA</p>
                            <p>Arista, percolator, cream, aromatic, fair trade, breve body instant lungo blue mountain cappuccino... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                    <article>
                        <img src="../static/imgstest/2.jpg" alt="Post Image">
                        <div class="post-info">
                            <h2>Coffee variety macchiato, as organic ut variety caffeine americano</h2>
                            <p>JUNIO 25, 2020 | VIAJES</p>
                            <p>Saucer, crema carajillo, bar, mocha medium, latte cappuccino... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                    <article>
                        <img src="../static/imgstest/3.jpg" alt="Post Image">
                        <div class="post-info">
                            <h2>According a funnily until pre-set or arrogant well cheerful</h2>
                            <p>JUNIO 25, 2020 | MÚSICA</p>
                            <p>Single shot cultivar beans as chicory caffeine... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                    <article>
                        <img src="../static/imgstest/4.jpg" alt="Post Image">
                        <div class="post-info">
                            <h2>Coffee sugar, chicory seasonal espresso barista americano</h2>
                            <p>JUNIO 25, 2020 | MÚSICA</p>
                            <p>Arista, percolator, cream, aromatic, fair trade, breve body instant lungo blue mountain cappuccino... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                    <article>
                        <img src="../static/imgstest/5.jpeg" alt="Post Image">
                        <div class="post-info">
                            <h2>Coffee variety macchiato, as organic ut variety caffeine americano</h2>
                            <p>JUNIO 25, 2020 | VIAJES</p>
                            <p>Saucer, crema carajillo, bar, mocha medium, latte cappuccino... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                    <article>
                        <img src="../static/imgstest/6.jpg" alt="Post Image">
                        <div class="post-info">
                            <h2>According a funnily until pre-set or arrogant well cheerful</h2>
                            <p>JUNIO 25, 2020 | MÚSICA</p>
                            <p>Single shot cultivar beans as chicory caffeine... <a href="#">Leer más</a></p>
                        </div>
                    </article>
                </section>
            </main>
            <footer>

            </footer>
    </div>    
</body>
</html>