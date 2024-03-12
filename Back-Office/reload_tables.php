<?php
    require_once("conection.php");
    include ("truncate_tables.php");

    include ("./poblar_bbdd_API/teamsBBDDPoblation.php");
    include ("./poblar_bbdd_API/playersBBDDPoblation.php");
    include ("./poblar_bbdd_API/gamesBBDDPobaltion.php");
    include ("./poblar_bbdd_API/statsBBDDPoblation.php");


    function reload_tables() {
        truncate_tables();

        reload_teams_table();
        reload_players_table();
        reload_games_table();
        reload_stats_table();
    }
    reload_tables();
?>