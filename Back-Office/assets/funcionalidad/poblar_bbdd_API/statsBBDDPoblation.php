<?php
function reload_stats_table()
{
    sleep(4);
    require ("credentials.php");
    $con = connect();
    $urlAPIstats = "https://api.balldontlie.io/v1/stats";
    $headers = array('Authorization:' . $token);
    set_time_limit(1000000);

    // Consulta SQL para obtener los IDs de los jugadores
    $sql_players = "SELECT id FROM final_players";
    $result_players = $con->query($sql_players);

    // Verificar si la consulta se ejecutó correctamente
    if (!$result_players) {
        die("Error al ejecutar la consulta sobre la tabla de jugadores en el programa de recarga de las estadisticas: " . $con->error);
    }
    // Inicializar array para almacenar los IDs de los jugadores
    $player_ids = array();

    // Obtener los IDs de los jugadores y agregarlos al array
    while ($row = $result_players->fetch_assoc()) {
        $player_ids[] = $row['id'];
    }

    // Consulta SQL para insertar datos de estadísticas
    $sql = "INSERT INTO final_stats (id, player_id, team_id, game_id, min, fgm, fga, fg_pct, fg3m, fg3a, fg3_pct, ft_pct, ftm, fta, oreb, dreb, reb, ast, stl, blk, pf, pts) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $con->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $con->error);
    }

    $ch = curl_init();

    foreach ($player_ids as $player_id) {

        $cursor = 0;

        do {
            // Construir la URL con el cursor actual
            $url = "$urlAPIstats?seasons[]=2023&player_ids[]=$player_id&per_page=25&cursor=$cursor";

            // Establecer opciones de cURL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Realizar la petición
            $response = curl_exec($ch);

            // Verificar si hubo algún error
            if (curl_errno($ch)) {
                echo 'Error al realizar la petición: ' . curl_error($ch);
                exit;
            }

            $data = json_decode($response, true);

            if (isset($data['data']) && is_array($data['data'])) {
                foreach ($data['data'] as $stat) {

                    $team_id = isset($stat['team']['id']) ? $stat['team']['id'] : null;
                    $game_id = isset($stat['game']['id']) ? $stat['game']['id'] : null;

                    try {
                        $stmt->bind_param("iiiidiidiiddiiiiiiiiii", $stat['id'], $player_id, $team_id, $game_id, $stat['min'], $stat['fgm'], $stat['fga'], $stat['fg_pct'], $stat['fg3m'], $stat['fg3a'], $stat['fg3_pct'], $stat['ft_pct'], $stat['ftm'], $stat['fta'], $stat['oreb'], $stat['dreb'], $stat['reb'], $stat['ast'], $stat['stl'], $stat['blk'], $stat['pf'], $stat['pts']);
                        $stmt->execute();
                    } catch (Exception $e) {
                    }
                }
            }

            $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
            sleep(2.5);

        } while ($cursor !== null);
    }

    curl_close($ch);
    $stmt->close();
    $con->close();

    echo "[+] Los datos de las ESTADISITICAS se insertaron correctamente [+]\n";

}
?>