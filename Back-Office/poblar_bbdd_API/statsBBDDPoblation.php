<?php
    require_once("conection.php");

   function reload_stats_table() {
        $con = conection_for_api();

        $urlAPIstats = "https://api.balldontlie.io/v1/stats";
        $headers = array('Authorization: ae6447b0-567a-4d62-9760-a4acdbe1eed9');

        // Consulta SQL para obtener los IDs de los jugadores
        $sql_players = "SELECT id FROM PLAYERS";
        $result_players = $con->query($sql_players);

        // Verificar si la consulta se ejecutó correctamente
        if (!$result_players) {
            die("Error al ejecutar la consulta: " . $con->error);
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

        foreach ($player_ids as $player_id){

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
                
                // Mostrar la respuesta por pantalla
                echo "Respuesta de la API para el jugador ID $player_id:\n";
                print_r($response);
                echo "\n";

                // Decodificar la respuesta JSON
                $data = json_decode($response, true);
                
                

                if (isset($data['data']) && is_array($data['data'])) {
                    // Insertar los datos de las estadísticas en la base de datos
                    foreach ($data['data'] as $stat) {
                        // Verificar si existen las claves necesarias en el elemento de datos actual
                        $team_id = isset($stat['team']['id']) ? $stat['team']['id'] : null;
                        $game_id = isset($stat['game']['id']) ? $stat['game']['id'] : null;
                        
                        // Asignar valores a los parámetros de la consulta
                        $stmt->bind_param("iiiidiidiiddiiiiiiiiii", $stat['id'], $player_id, $team_id, $game_id, $stat['min'], $stat['fgm'], $stat['fga'], $stat['fg_pct'], $stat['fg3m'], $stat['fg3a'], $stat['fg3_pct'], $stat['ft_pct'], $stat['ftm'], $stat['fta'], $stat['oreb'], $stat['dreb'], $stat['reb'], $stat['ast'], $stat['stl'], $stat['blk'], $stat['pf'], $stat['pts']);
                        
                        // Ejecutar la consulta
                        $stmt->execute();
                        
                        // Verificar si ocurrió algún error al ejecutar la consulta
                        if ($stmt->errno) {
                            echo "Error al insertar datos: " . $stmt->error;
                        }
                    }
                } else {
                    // Si no hay datos válidos en la respuesta JSON, mostrar un mensaje de advertencia
                    echo "No se encontraron datos válidos en la respuesta JSON.";
                }
                
                // Actualizar el cursor
                $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
                
                sleep(2); // Esto limita el número de solicitudes a 30 por minuto

            } while ($cursor !== null);
        }
            
        // Cerrar la sesión cURL
        curl_close($ch);

        // Cerrar la conexión y liberar recursos
        $stmt->close();

        echo "<br>[+] Los datos de las ESTADISITICAS se insertaron correctamente.<br>";

    }
?>