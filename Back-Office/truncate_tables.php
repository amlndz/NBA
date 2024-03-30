<?php  
    require_once("conection.php");

    function truncate_tables() {
        $con = connect();
        $result = $con->query("set foreign_key_checks = 0");
        $result = $con->query("truncate table final_games");
        $result = $con->query("truncate table final_players");
        $result = $con->query("truncate table final_stats");
        $result = $con->query("truncate table final_teams");
        $result = $con->query("set foreign_key_checks = 1");

        echo "<br>[+] Tablas truncadas<br>";
    }
    truncate_tables();
        
?>