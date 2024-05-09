<?php
    function conection() {
        $server = "dbserver";
        $user = "root";
        $password = "fex1roMi4j"; // fex1roMi4j
        $db = "siw";

        $con = mysqli_connect($server, $user, $password, $db);

        if (!$con) {
            echo "[!] Error de conexión a la Base de Datos\n";
            echo "Erro número: " . mysqli_connect_error();
            echo "Texto error: " . mysqli_connect_errno();
            return -1;
        } else {
            echo "[+] Conexión establecida con éxito<br>";
            return $con;
        }
    }
?>