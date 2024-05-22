<?php

function reload_players_table()
{
    sleep(4);
    require ("credentials.php");
    $con = connect();
    $urlAPIplayers = "https://api.balldontlie.io/v1/players";
    $header = array('Authorization: ' . $token);
    $ch = curl_init();
    set_time_limit(900);

    // Variables para el cursor y el límite de resultados por página
    $cursor = 0;
    do {
        $url = "$urlAPIplayers?&cursor=$cursor";

        // Establecer opciones de cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            exit;
        }

        $data = json_decode($response, true);

        // Verificar si la respuesta es válida y contiene datos
        if (is_null($data) || !isset($data['data']) || !is_array($data['data'])) {
            sleep(2.5);
            continue;
        }

        $sql = "INSERT INTO final_players (id, first_name, last_name, position, height, weight, team_id, number, draft, draft_round, country, draft_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $con->error);
        }

        foreach ($data['data'] as $player) {
            try {
                if ($player['jersey_number'] === NULL) {
                    continue;
                }

                $team_id = $player['team']['id'];
                $numero_transformado = str_replace('-', '.', $player['height']);

                try {
                    $stmt->bind_param(
                        "isssddiiiisi",
                        $player['id'],
                        $player['first_name'],
                        $player['last_name'],
                        $player['position'],
                        $numero_transformado,
                        $player['weight'],
                        $team_id,
                        $player['jersey_number'],
                        $player['draft_year'],
                        $player['draft_round'],
                        $player['country'],
                        $player['draft_number']
                    );
                    $stmt->execute();
                } catch (Exception $e) {
                }
            } catch (Exception $e) {
            }
        }

        $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
        sleep(2.5);
    } while ($cursor !== null);

    curl_close($ch);
    $stmt->close();
    $con->close();

    echo "[+] Los datos de los JUGADORES se insertaron correctamente [+]\n";
}
?>