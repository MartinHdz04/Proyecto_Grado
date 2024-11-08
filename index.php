<?php 

session_start();
if(isset($_SESSION['usuario_id'])){
    header("location: login.php");
}


?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión - Lost & Found</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>Lost & Found</h1>
        </div>
        <h2>Iniciar sesión en Lost & Found</h2>
        <form action="login.php" method="POST" onsubmit="return validateForm()">
            <div class="input-group">
                <input type="text" name="usuario_red" id="usuario_red" placeholder="Usuario de Red" required>
            </div>
            <div class="input-group">
                <input type="password" name="clave" id="clave" placeholder="Contraseña" required>
            </div>
            <button type="submit">Iniciar sesión</button>
            <a href="#" class="forgot-password">¿Olvidaste la contraseña?</a>
        </form>
    </div>

    <script src="script.js"></script>
</body>
</html>

