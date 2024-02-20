<?php
    session_start();

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar las credenciales
        if ($_POST["usuario"] === "user" && $_POST["contrasena"] === "1234") {
            // Si las credenciales son válidas, establecer la variable de sesión
            $_SESSION['usuario_autenticado'] = true;

            // Redirigir al usuario a otra página después del inicio de sesión
            header("Location: index.php");
            exit; // Asegúrate de que el script se detenga después de la redirección
        } else {
            // Si las credenciales no son válidas, mostrar un mensaje de error
            $error = "Usuario o contraseña incorrectos";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NBA - Login</title>
</head>
<body>
    <h2>Iniciar sesión</h2>
    <?php
        // Mostrar mensaje de error si existe
        if (isset($error)) {
            echo "<p>$error</p>";
        }
    ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="usuario">Usuario:</label><br>
        <input type="text" id="usuario" name="usuario"><br>
        <label for="contrasena">Contraseña:</label><br>
        <input type="password" id="contrasena" name="contrasena"><br><br>
        <input type="submit" value="Iniciar sesión">
    </form>
</body>
</html>
