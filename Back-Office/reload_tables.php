<?php
    require_once("conection.php");
    include ("truncate_tables.php");

    include ("./poblar_bbdd_API/teams.php");
    include ("./poblar_bbdd_API/active_players.php");
    include ("./poblar_bbdd_API/games.php");
    include ("./poblar_bbdd_API/stats.php");


    function reload_tables() {
        truncate_tables();

        reload_teams_table();
        reload_players_table();
        reload_games_table();
        reload_stats_table();
    }
    reload_tables();
?>