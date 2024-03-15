<?php
    function connect() {

        $credentials = include('credentials.php');
        $host_name = $credentials['host_name'];
        $database = $credentials['database'];
        $user_name = $credentials['user_name'];
        $password = $credentials['password'];;        


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

    