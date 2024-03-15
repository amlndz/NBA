<?php
    function connect() {
        $server = "db5015536478.hosting-data.io";   // $server = "dbserver";
        $user = "dbu1594629";          // $username = "grupo25";
        $password = "gb01xJkqA5dXvWQ";          // $password = "fex1roMi4j";
        $db = " dbs12691421";             // $db = "grupo_25";


        $conn = mysqli_connect($server, $user, $password, $db);

        if (!$conn) {
            echo "[!] Error de conexión a la Base de Datos\n";
            echo "Erro número: " . mysqli_connect_error();
            echo "Texto error: " . mysqli_connect_errno();
        }
        return $conn;
    }

    