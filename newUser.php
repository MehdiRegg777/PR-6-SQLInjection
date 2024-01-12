<?php
session_start()
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title>
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <form method="post">
        <h2>Crear Usuario</h2>
        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Crear Usuario</button>
    </form>
    <button id="panelButton">Panel de control</button>

    <?php
    //phpinfo();
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
        $usuario = $_POST["username"];
        $contraseña = $_POST["password"];

        // Hashear la contraseña antes de almacenarla en la base de datos
        $contraseñaHash = hash('sha256', $contraseña);

        // Insertar el nuevo usuario en la base de datos
        $querystr = "INSERT INTO usuarios (usuario, password_sha) VALUES (:usuario, :contrasena)";
        $query = $pdo->prepare($querystr);

        $query->bindParam(':usuario', $usuario, PDO::PARAM_STR);
        $query->bindParam(':contrasena', $contraseñaHash, PDO::PARAM_STR);

        $query->execute();

        // Verificar si la inserción fue exitosa
        if ($query->rowCount() > 0) {
            echo "Usuario creado exitosamente.";
        } else {
            echo "Error al crear el usuario. Puede ser que el nombre de usuario ya exista.";
        }

        unset($pdo);
        unset($query);
    }
    ?>
    <script>
        document.getElementById("panelButton").addEventListener("click", function() {
            window.location.href = "dashBoard.php";
        });
    </script>
</body>
</html>
