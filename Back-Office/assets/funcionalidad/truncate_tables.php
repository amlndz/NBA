<?php
try {
    session_start();
} catch (Exception $e) {
}

if ($_SESSION['administrador'] != 1) {
    header("Location: ../Front-Office/index.php");
    exit();
}
require_once ("connection.php");

function truncate_tables()
{
    $con = connect();
    $con->query("set foreign_key_checks = 0");
    $con->query("truncate table final_games");
    $con->query("truncate table final_players");
    $con->query("truncate table final_stats");
    $con->query("truncate table final_teams");
    $con->query("truncate table final_averages");
    $con->query("set foreign_key_checks = 1");

    echo "[+] Tablas truncadas correctamente [+]\n";

    $con->close();
}
truncate_tables();

?>