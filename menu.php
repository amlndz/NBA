<?php
    session_start();

    // Verificar si el usuario está autenticado
    $usuario_autenticado = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>NBA</title>
    <link rel="stylesheet" href="estilos.css">
    <script src="./assets/jss/script.js" defer></script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="btn-menu">
                <label for="btn-menu">☰</label>
            </div>
            <div class="logo-img">
                <img src="../assets/images/logoNBA.png" alt="logo">
            </div>
            <div class="btn-user">
                <!-- Mostrar lista de usuarios si el usuario está autenticado -->
                <?php if ($usuario_autenticado): ?>
                    <!-- Botón para mostrar la lista -->
                    <a href="#" id="btn-user-link"><img src="../assets/images/user.png" alt=""></a>
                    <!-- Aquí va tu código para mostrar la lista de usuarios -->
                    <ul id="user-list" style="display: none;">
                        <li>Usuario 1</li>
                        <li>Usuario 2</li>
                        <!-- Puedes agregar más elementos de lista aquí -->
                    </ul>
                    <!-- Botón para cerrar sesión -->
                    <form action="logout.php" method="post">
                        <input type="submit" value="Cerrar sesión">
                    </form>
                <?php else: ?>
                    <!-- Si el usuario no está autenticado, enlazar al formulario de registro -->
                    <a href="login.php"><img src="../assets/images/user.png" alt=""></a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="capa"></div>
    <input type="checkbox" id="btn-menu">
    <div class="container-menu" id="menu-container">
        <div class="cont-menu">
            <nav>
                <a href="#">Jugadores</a>
                <a href="#">Equipos</a>
                <a href="#">Partidos</a>
            </nav>
            <label for="btn-menu">✖️</label>
        </div>
    </div>

    <script>
        // Agregar evento de clic al botón de usuario
        document.getElementById('btn-user-link').addEventListener('click', function(event) {
            // Prevenir la acción predeterminada del enlace
            event.preventDefault();
            
            // Verificar si el usuario está autenticado antes de mostrar la lista
            <?php if ($usuario_autenticado): ?>
                // Mostrar la lista de usuarios
                document.getElementById('user-list').style.display = 'block';
            <?php else: ?>
                // Redirigir a login.php si el usuario no está autenticado
                window.location.href = 'login.php';
            <?php endif; ?>
        });
    </script>
</body>
</html>
