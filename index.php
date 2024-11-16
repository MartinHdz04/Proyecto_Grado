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
    <title>Lost & Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: 100%;
            overflow-x: hidden;
        }

        .top-header {
            width: 100%;
            background-color: #006341;
            padding: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        .top-header h1 {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            margin-left: clamp(0.5rem, 2vw, 1rem);
        }

        .top-header button {
            background-color: transparent;
            color: white;
            border: none;
            padding: clamp(0.3rem, 1.5vw, 0.5rem) clamp(0.5rem, 2vw, 1rem);
            cursor: pointer;
            font-size: clamp(0.8rem, 2vw, 1rem);
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-grow: 1;
            position: relative;
            background: white;
        }
        .top-header button {
    background-color: transparent;
    color: white;
    border: 2px solid white;
    border-radius: 4px;
    padding: clamp(0.3rem, 1.5vw, 0.5rem) clamp(0.5rem, 2vw, 1rem);
    cursor: pointer;
    font-size: clamp(0.8rem, 2vw, 1rem);
    transition: all 0.3s ease;
}

.top-header button:hover {
    background-color: white;
    color: #006341;
}

@media (max-width: 480px) {
    .top-header button {
        font-size: 0.8rem;
        padding: 0.3rem 0.5rem;
    }
}
        .header-image {
            width: 100%;
            height: 250px;
            overflow: hidden;
        }

        .header-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .login-section {
            width: min(90%, 400px);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            background: white;
        }

        .login-title {
            color: #006341;
            font-size: clamp(1.8rem, 4vw, 2.2rem);
            text-align: center;
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: clamp(0.8rem, 2vw, 1rem);
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .input-container {
            width: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 15px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background-color: #006341;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }


        .footer {
            width: 100%;
            background-color: #006341;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .header-image {
                height: 200px;
            }
            
        }

        @media (max-width: 480px) {
            .login-section {
                width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="top-header">
        <h1>LOST&FOUND</h1>
        <button onclick="scrollToLogin()">INICIAR SESION</button>
    </div>

    <div class="main-container">
        <div class="header-image">
            <img src="imagen/inc.jpg" alt="Mike and Sully">
        </div>

        <div class="login-section">
            <h1 class="login-title">Lost & Found</h1>
            <p class="login-subtitle">INICIAR SESIÓN EN LOST & FOUND</p>
            
            <form action="login.php" method="POST" onsubmit="return validateForm()" class="input-container">
                <input type="text" name="usuario_red" id="usuario_red" placeholder="USUARIO DE RED" required>
                <input type="password" name="clave" id="clave" placeholder="CONTRASEÑA" required>
                <button type="submit" class="login-btn">INICIAR SESION</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        Lost & Found ean copy Right 2024
    </footer>
    <script>
        function scrollToLogin() {
            const loginSection = document.querySelector('.login-section');
            loginSection.scrollIntoView({ behavior: 'smooth' });
        }
    </script>
    <script>
        function validateForm() {
            var usuario = document.getElementById('usuario_red').value;
            var clave = document.getElementById('clave').value;
            
            if (usuario.trim() === '' || clave.trim() === '') {
                alert('Por favor, complete todos los campos');
                return false;
            }
            return true;
        }
    </script>
    <script src="script.js"></script>
</body>
</html>

