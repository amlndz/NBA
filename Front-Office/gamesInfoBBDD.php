<?php 
require_once "autenticarUsuario.php";
include "connection.php";
$conn = connect();

// Define la cantidad de partidos por página
$partidos_por_pagina = 10;

// Obtiene la página actual desde los parámetros GET, o establece la página a 1 si no se proporciona ningún parámetro
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;

// Calcula el inicio de los partidos para la consulta SQL
$inicio = ($pagina_actual - 1) * $partidos_por_pagina;

// Modifica la consulta para limitar los resultados basado en la página actual
$query = "SELECT * FROM final_games LIMIT $inicio, $partidos_por_pagina";
$result = mysqli_query($conn, $query);

function getTeamDetails($team_id) {
    global $conn;
    $query = "SELECT * FROM final_teams WHERE id = $team_id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}


$query_pdf = "SELECT * FROM final_games";
$result_pdf = mysqli_query($conn, $query_pdf);


$_SESSION['games'] = []; 
while ($row = $result_pdf->fetch_assoc()) {
    $home_team = getTeamDetails((int)$row['home_team_id']);
    $visitor_team = getTeamDetails((int)$row['visitor_team_id']);
    $row['home_team_id'] = $home_team['name'];
    $row['visitor_team_id'] = $visitor_team['name'];

    $_SESSION['games'][] = $row;
}