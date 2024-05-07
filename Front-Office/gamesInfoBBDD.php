<?php 
require_once "autenticarUsuario.php";

include "connection.php";
$conn = connect();
$query = "SELECT * FROM final_games";
$result = mysqli_query($conn, $query);

// Iterar sobre los resultados

// Función para obtener los detalles del equipo
function getTeamDetails($team_id) {
    global $conn;
    $query = "SELECT * FROM final_teams WHERE id = $team_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}
?>