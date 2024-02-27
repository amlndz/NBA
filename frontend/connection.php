<?php
    function connect() {
        $server = "localhost";   // $server = "dbserver";
        $user = "root";          // $username = "grupo25";
        $password = "";          // $password = "fex1roMi4j";
        $db = "siw";             // $db = "grupo_25";


        $conn = mysqli_connect($server, $user, $password, $db);

        if (!$conn) {
            echo "[!] Error de conexión a la Base de Datos\n";
            echo "Erro número: " . mysqli_connect_error();
            echo "Texto error: " . mysqli_connect_errno();
        }
        return $conn;
    }

    