<?php
require_once ("connection.php");

function truncate_tables()
{
    $con = connect();
    $result = $con->query("set foreign_key_checks = 0");
    $result = $con->query("truncate table final_games");
    $result = $con->query("truncate table final_players");
    $result = $con->query("truncate table final_stats");
    $result = $con->query("truncate table final_teams");
    $result = $con->query("truncate table final_averages");
    $result = $con->query("set foreign_key_checks = 1");

    echo "Tablas truncadas correctamente";
}
truncate_tables();

?>