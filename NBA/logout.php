<?php
    session_start();

    // Eliminar todas las variables de sesión
    session_unset();

    // Destruir la sesión
    session_destroy();

    // Redirigir al usuario a la página de inicio o donde desees
    header("Location: index.php");
    exit; // Asegúrate de que el script se detenga después de la redirección
