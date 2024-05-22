<?php
function eliminar_jugadores_sin_stats()
{
    require ("credentials.php");
    $con = connect();

    try {
        // Obtener los IDs de los jugadores que no están en final_stats
        $sql = 'SELECT id FROM final_players WHERE id NOT IN (SELECT player_id FROM final_stats)';
        $result_players = $con->query($sql);

        if (!$result_players) {
            throw new Exception('Error al ejecutar la subconsulta: ' . mysqli_error($conn));
        }

        $playerIds = [];
        while ($row = mysqli_fetch_assoc($result_players)) {
            $playerIds[] = $row['id'];
        }

        if (!empty($playerIds)) {
            $playerIdsList = implode(',', $playerIds);

            // Eliminar registros en final_averages relacionados con los player_id que se van a eliminar
            $sql = "DELETE FROM final_averages WHERE player_id IN ($playerIdsList)";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            // Eliminar registros en final_players
            $sql = "DELETE FROM final_players WHERE id IN ($playerIdsList)";
            $stmt = $con->prepare($sql);
            $stmt->execute();
        }

        echo 'Registros eliminados exitosamente.';
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }

    $stmt->close();
    $con->close();
}
?>