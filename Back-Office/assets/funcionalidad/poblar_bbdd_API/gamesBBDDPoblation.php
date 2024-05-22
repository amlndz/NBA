<?php

function reload_games_table()
{
    sleep(4);
    require ("credentials.php");
    $con = connect();
    $urlAPIgames = "https://api.balldontlie.io/v1/games";
    $header = array('Authorization: ' . $token);
    set_time_limit(600);

    $sql = "INSERT INTO final_games (id, date, season, status, period, time, postseason, home_team_score, visitor_team_score, home_team_id, visitor_team_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $con->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $con->error);
    }

    $ch = curl_init();

    $cursor = 0;

    do {
        // Construir la URL con el cursor actual
        $url = "$urlAPIgames?seasons[]=2023&per_page=25&cursor=$cursor";

        // Establecer opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Realizar la petición
        $response = curl_exec($ch);

        // Verificar si hubo algún error
        if (curl_errno($ch)) {
            echo 'Error al realizar la petición: ' . curl_error($ch);
            exit;
        }

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        if (is_array($data['data']) && !is_null($data)) {
            // Insertar los datos de los partidos en la base de datos
            foreach ($data['data'] as $game) {

                $home_team_id = $game['home_team']['id'];
                $visitor_team_id = $game['visitor_team']['id'];

                try {
                    $stmt->bind_param("isssisiiiii", $game['id'], $game['date'], $game['season'], $game['status'], $game['period'], $game['time'], $game['postseason'], $game['home_team_score'], $game['visitor_team_score'], $home_team_id, $visitor_team_id);

                    // Ejecutar la consulta
                    $stmt->execute();
                } catch (Exception $e) {
                }
            }
        }

        $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
        sleep(2.5);

    } while ($cursor !== null);

    curl_close($ch);
    $stmt->close();
    $con->close();

    echo "[+] Los datos de los PARTIDOS se insertaron correctamente [+]\n";
}



