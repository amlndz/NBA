<?php
    // function connect() {

    //     $server = "dbserver";
    //     $username = "grupo25";
    //     $db = "grupo_25";
    //     $password = "fex1roMi4j";       


        //$conn = mysqli_connect($server, $user, $password, $db);
    //     $conn = new mysqli($server, $username, $password, $db);

    //     if (!$conn) {
    //         die('<p>Error al conectar con servidor MySQL: '. $conn->connect_error .'</p>');
            // echo "[!] Error de conexión a la Base de Datos\n";
            // echo "Erro número: " . mysqli_connect_error();
            // echo "Texto error: " . mysqli_connect_errno();
    //     }
    //     return $conn;
    // }
    function connect() {
        require 'credentials.php';

        //$conn = mysqli_connect($server, $user, $password, $db);
        $conn = new mysqli($hostname, $username, $password, $database);

        if (!$conn) {
            die('<p>Error al conectar con servidor MySQL: '. $conn->connect_error .'</p>');
            // echo "[!] Error de conexión a la Base de Datos\n";
            // echo "Erro número: " . mysqli_connect_error();
            // echo "Texto error: " . mysqli_connect_errno();
        }
        return $conn;
    }

    
    