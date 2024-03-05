<?php
    function conection() {
        $server = "localhost";
        $user = "root";
        $password = "fex1roMi4j"; // fex1roMi4j
        $db = "nba";

        $con = mysqli_connect($server, $user, $password, $db);

        if (!$con) {
            echo "[!] Error de conexión a la Base de Datos\n";
            echo "Erro número: " . mysqli_connect_error();
            echo "Texto error: " . mysqli_connect_errno();
        } else {
            echo "[+] Conexión establecida con éxito<br>";
        }
        return $con;
    }
?>