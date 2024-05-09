<?php 
require_once "autenticarUsuario.php";
include "connection.php";
$conn = connect();
$query = "SELECT * FROM final_games";
$result = mysqli_query($conn, $query);

function getTeamDetails($team_id) {
    global $conn;
    $query = "SELECT * FROM final_teams WHERE id = $team_id";
    $result = mysqli_query($conn, $query);
    
    return mysqli_fetch_assoc($result);
}

$_SESSION['games'] = []; 
while ($row = $result->fetch_assoc()) {
    $home_team = getTeamDetails((int)$row['home_team_id']);
    $visitor_team = getTeamDetails((int)$row['visitor_team_id']);
    echo $home_team['name']."-----------".$visitor_team['name'];
    $row['home_team_id'] = $home_team['name'];
    $row['visitor_team_id'] = $visitor_team['name'];

    $_SESSION['games'][] = $row;
}