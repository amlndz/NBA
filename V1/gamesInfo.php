<?php
    $servername = "http://webalumnos.tlm.unavarra.es:10800";
    $username = "grupo25";
    $password = "fex1roMi4j";
    $database = "db_grupo25";

    // Creamos conexión
    $conn = new mysqli("dbserver", $username, $password, $database);

    // Verificamos conexión
    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }

    echo "Conexion establecida con la BBDD";