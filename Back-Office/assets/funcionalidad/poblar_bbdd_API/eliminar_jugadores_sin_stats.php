<?php
function eliminar_jugadores_sin_stats()
{
    require ("credentials.php");
    $con = connect();
    try {

        $sql = "DELETE FROM final_players WHERE id NOT IN (SELECT player_id FROM final_stats)";

        // Ejecutar la consulta
        $stmt = $con->prepare($sql);

        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $con->error);
        }

        $stmt->execute();
        if ($stmt->errno) {
            echo "Error al insertar datos: " . $stmt->error;
        }

        echo "[+] Se han eliminado los jugadores sin stats correctamente [+]\n";
    } catch (PDOException $e) {
        echo "Error al ejecutar la consulta: " . $e->getMessage();
    }
    $stmt->close();
    $con->close();
}
?>