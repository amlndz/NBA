<?php
include ("connection.php");
include ("truncate_tables.php");

include ("./poblar_bbdd_API/teamsBBDDPoblation.php");
include ("./poblar_bbdd_API/playersBBDDPoblation.php");
include ("./poblar_bbdd_API/gamesBBDDPoblation.php");
include ("./poblar_bbdd_API/statsBBDDPoblation.php");
include ("./poblar_bbdd_API/averageBBDDPoblation.php");
include ("./poblar_bbdd_API/eliminar_jugadores_sin_stats.php");


function reload_tables()
{
    truncate_tables();

    reload_teams_table();
    reload_players_table();
    reload_games_table();
    reload_stats_table();
    eliminar_jugadores_sin_stats();
    reload_average_table();

    echo "[+] Tablas recargadas correctamente [+]\n";
}
reload_tables();
