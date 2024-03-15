<?php
    include "credentials.php";
    function connect() {
        list($host_name, $database, $user_name, $password) = getCredentials();        


        //$conn = mysqli_connect($server, $user, $password, $db);
        $conn = new mysqli($host_name, $user_name, $password, $database);

        if (!$conn) {
            die('<p>Error al conectar con servidor MySQL: '. $conn->connect_error .'</p>');
            // echo "[!] Error de conexión a la Base de Datos\n";
            // echo "Erro número: " . mysqli_connect_error();
            // echo "Texto error: " . mysqli_connect_errno();
        }
        return $conn;
    }

    