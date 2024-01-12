<?php
session_start()
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="style.css" rel="stylesheet">
</head>

<body>
    <?php 
        echo '<h4>Usuario: ' . $_SESSION['usuario']. '</h4>';

    ?>
    <form method="post" >
        <h2>Cambiar Contraseña</h2>
        <label for="current_password">Contraseña Actual:</label>
        <input type="password" id="current_password" name="current_password" required>
        <br>
        <label for="new_password">Nueva Contraseña:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>
        <label for="confirm_password">Confirmar Nueva Contraseña:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <button type="submit">Cambiar Contraseña</button>
    </form>
    <button id="panelButton">Panel de control</button>

    <?php
    // Tu código PHP para la conexión a la base de datos y verificar la contraseña actual irá aquí
    // ...
    try {
        $hostname = "localhost";
        $dbname = "DatosUsuarios";
        $username = "mehdi";
        $pw = "kingmehdi";
        $pdo = new PDO ("mysql:host=$hostname;dbname=$dbname","$username","$pw");
    } catch (PDOException $e) {
        echo "Failed to get DB handle: " . $e->getMessage() . "\n";
        exit;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_SESSION['usuario'];
        $contraseña_actual = $_POST["current_password"];
        $nueva_contraseña = $_POST["new_password"];
        $confirmar_contraseña = $_POST["confirm_password"];

        // Validar que las nuevas contraseñas coincidan
        if ($nueva_contraseña != $confirmar_contraseña) {
            echo "Las contraseñas no coinciden. Inténtelo de nuevo.";
            exit();
        }

        function verificarContraseña($usuario,$contraseña,$pdo) {
            $querystr = "SELECT usuario FROM usuarios WHERE usuario=:usuario AND password_sha=SHA2(:contrasena, 256)";
            $query = $pdo->prepare($querystr);

            $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $query->bindParam(':contrasena', $contraseña, PDO::PARAM_STR);

            $query->execute();

            
            $filas = $query->rowCount();
            if ($filas > 0) {
                return true;
            } else {
                return false;
                
            }
            unset($pdo);
            unset($query);
        }

        $resultado = verificarContraseña($usuario,$contraseña_actual,$pdo);

        function caviarContraseña($usuario,$contraseña,$pdo) {
            // Hashear la nueva contraseña antes de almacenarla en la base de datos
            $nuevaContraseñaHash = hash('sha256', $contraseña);

            // Actualizar la contraseña en la base de datos
            $querystr = "UPDATE usuarios SET password_sha = :nuevaContrasena WHERE usuario = :usuarioP";
            $query = $pdo->prepare($querystr);

            $query->bindParam(':nuevaContrasena', $nuevaContraseñaHash, PDO::PARAM_STR);
            $query->bindParam(':usuarioP', $usuario, PDO::PARAM_STR);

            $query->execute();

            // Verificar si la actualización fue exitosa
            if ($query->rowCount() > 0) {
                return true; // El cambio de contraseña fue exitoso
                
            } else {
                return false; // No se pudo cambiar la contraseña
            }
        }

        if ($resultado) {
            $contraseñaCanviada = caviarContraseña($usuario,$nueva_contraseña,$pdo);
        } else {
            echo "Usuario o contraseña incorrectos";
            exit();
        }

        

        if ($contraseñaCanviada) {
            echo 'Contraseña cambiada exitosamente.';
            exit();
        } else {
            echo "No se pudo canviar la Contraseña";
        }
        
    }
    ?>
  <script>
        document.getElementById("panelButton").addEventListener("click", function() {
            window.location.href = "dashBoard.php";
        });
    </script>
</body>
</html>
