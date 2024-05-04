<?php

function reload_players_table()
{
    require ("credentials.php");
    $con = connect();
    $urlAPIplayers = "https://api.balldontlie.io/v1/players";
    $header = array('Authorization: ' . $token);
    $ch = curl_init();
    set_time_limit(600);

    // Variables para el cursor y el límite de resultados por página
    $cursor = 0;
    do {
        $url = "$urlAPIplayers?&cursor=$cursor";


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

        // Consulta SQL para insertar datos de jugadores
        $sql = "INSERT INTO final_players (id, first_name, last_name, position, height, weight, team_id, number, draft, draft_round, country,	draft_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $con->prepare($sql);

        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $con->error);
        }

        // Recorrer los datos y realizar inserciones
        try {
            foreach ($data['data'] as $player) {

                if ($player['jersey_number'] === NULL) {
                    continue;
                }

                // Extraer la información del equipo
                $team_id = $player['team']['id'];

                $numero_transformado = str_replace('-', '.', $player['height']);


                // Asignar valores a los parámetros de la consulta
                $stmt->bind_param("isssddiiiisi", $player['id'], $player['first_name'], $player['last_name'], $player['position'], $numero_transformado, $player['weight'], $team_id, $player['jersey_number'], $player['draft_year'], $player['draft_round'], $player['country'], $player['draft_number']);

                // Ejecutar la consulta
                $stmt->execute();

                // Verificar si ocurrió algún error al ejecutar la consulta
                if ($stmt->errno) {
                    echo "Error al insertar datos: " . $stmt->error;
                }
                $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
            }
        } catch (Exception $e) {
            continue;
        }
        sleep(2.5);
    } while ($cursor !== null);

    // Cerrar la sesión cURL
    curl_close($ch);

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $con->close();
    echo "[+] Los datos de los JUGADORES se insertaron correctamente [+]\n";
}
