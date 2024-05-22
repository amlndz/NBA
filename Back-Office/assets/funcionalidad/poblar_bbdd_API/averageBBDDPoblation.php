<?php
function reload_average_table()
{
    sleep(4);
    require ("credentials.php");
    error_reporting(E_ALL);
    set_time_limit(600);

    // Establecer conexión a la base de datos
    $con = connect();
    if ($con->connect_error) {
        die("Error de conexión: " . $con->connect_error);
    }

    $urlAPIaverages = "https://api.balldontlie.io/v1/season_averages";
    $headers = array('Authorization: ' . $token);

    $sql_players = "SELECT id FROM final_players";
    $result_players = $con->query($sql_players);
    if (!$result_players) {
        die("Error al ejecutar la consulta: " . $con->error);
    }

    $player_ids = array();
    while ($row = $result_players->fetch_assoc()) {
        $player_ids[] = $row['id'];
    }

    $con->close();
    $con = connect();
    if ($con->connect_error) {
        die("Error de conexión: " . $con->connect_error);
    }


    $ch = curl_init();
    foreach ($player_ids as $player_id) {

        // Construir la URL con el jugador actual
        $url = $urlAPIaverages . "?player_ids[]=$player_id&season=2023";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error al realizar la petición: ' . curl_error($ch);
            exit;
        }

        $data = json_decode($response, true);


        if (isset($data['data'][0]) && is_array($data['data'][0])) {
            $average = $data['data'][0];

            try {
                $sql = "INSERT INTO final_averages 
                        (player_id, season, pts, ast, turnover, pf, fga, fgm, fta, ftm, fg3a, fg3m, reb, oreb, dreb, stl, blk, fg_pct, fg3_pct, ft_pct, min, games_played) 
                        VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt = $con->prepare($sql);

                $stmt->bind_param(
                    "isiiiiiiiiiiiiiiidddss",
                    $average['player_id'],
                    $average['season'],
                    $average['pts'],
                    $average['ast'],
                    $average['turnover'],
                    $average['pf'],
                    $average['fga'],
                    $average['fgm'],
                    $average['fta'],
                    $average['ftm'],
                    $average['fg3a'],
                    $average['fg3m'],
                    $average['reb'],
                    $average['oreb'],
                    $average['dreb'],
                    $average['stl'],
                    $average['blk'],
                    $average['fg_pct'],
                    $average['fg3_pct'],
                    $average['ft_pct'],
                    $average['min'],
                    $average['games_played']
                );

                $stmt->execute();

            } catch (Exception $e) {
            }
        }

        sleep(2.5);
    }

    curl_close($ch);
    $stmt->close();
    $con->close();

    echo "[+] Los datos de los AVERAGES se insertaron correctamente [+]\n";
}
?>