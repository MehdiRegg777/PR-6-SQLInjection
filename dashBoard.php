<?php
session_start()
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Panel</title>
</head>
<body>
    <?php 
        echo '<h4>Usuario: ' . $_SESSION['usuario']. '</h4>';

    ?>
    <h2>Panel de control</h2>
    <button id="passwordButton">Canviar Contraseña</button>
    <button id="userButton">Crear Usuario</button>
    <button id="logoutButton">Cerrar Sesion</button>

    <script>
        document.getElementById("logoutButton").addEventListener("click", function() {
            window.location.href = "login.php";
        });
        document.getElementById("userButton").addEventListener("click", function() {
            window.location.href = "newUser.php";
        });
        document.getElementById("passwordButton").addEventListener("click", function() {
            window.location.href = "newPassword.php";
        });
    </script>
</body>
</html>