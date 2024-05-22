<?php
try {
    session_start();
} catch (Exception $e) {
}

if ($_SESSION['administrador'] != 1) {
    header("Location: ../Front-Office/index.php");
    exit();
}

include_once ("truncate_tables.php");

require_once ("./poblar_bbdd_API/teamsBBDDPoblation.php");
require_once ("./poblar_bbdd_API/playersBBDDPoblation.php");
require_once ("./poblar_bbdd_API/gamesBBDDPoblation.php");
require_once ("./poblar_bbdd_API/statsBBDDPoblation.php");
require_once ("./poblar_bbdd_API/averageBBDDPoblation.php");
require_once ("./poblar_bbdd_API/eliminar_jugadores_sin_stats.php");


reload_teams_table();
reload_players_table();
reload_games_table();
reload_stats_table();
eliminar_jugadores_sin_stats();
reload_average_table();

echo "[+] Tablas recargadas correctamente [+]\n";

header("Location: ../../resumenTablas.php");
exit();

