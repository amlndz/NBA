<?php  
    require_once("conection.php");

    function truncate_tables() {
        $con = conection();
        $result = $con->query("set foreign_key_checks = 0");
        $result = $con->query("truncate table games");
        $result = $con->query("truncate table players");
        $result = $con->query("truncate table stats");
        $result = $con->query("truncate table teams");
        $result = $con->query("set foreign_key_checks = 1");

        echo "<br>[+] Tablas truncadas<br>";
    }
    truncate_tables();
        
?>