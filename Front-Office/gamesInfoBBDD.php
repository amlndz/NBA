<?php 
require_once     "autenticarUsuario.php";

include "connection.php";
$conn = connect();
$query = "SELECT * FROM final_games";
$result = mysqli_query($conn, $query);
while ($row = $result->fetch_assoc()) {
    $_SESSION['games'][] = $row;
}

$result->data_seek(0);
// Iterar sobre los resultados

// Función para obtener los detalles del equipo
function getTeamDetails($team_id) {
    global $conn;
    $query = "SELECT * FROM final_teams WHERE id = $team_id";
    $result = mysqli_query($conn, $query);
    $_SESSION['games'] = [];
    
    return mysqli_fetch_assoc($result);
}
?>