<?php

    include 'connection.php';
    require 'credentials.php';
    $con = connect();;
    echo "conectado";

    $urlAPIaverages = "https://api.balldontlie.io/v1/season_averages";
    $headers = array('Authorization: '. $token);

    $sql_players = "SELECT id FROM final_players";
    $result_players = $con->query($sql_players);
    // Verificar si la consulta se ejecutó correctamente
    echo "consulta ejecutada";
    if (!$result_players) {
        die("Error al ejecutar la consulta: " . $con->error);
    }
    echo "consulta ejecutada correctamente";
    // Inicializar array para almacenar los IDs de los jugadores
    $player_ids = array();

    // Obtener los IDs de los jugadores y agregarlos al array
    while ($row = $result_players->fetch_assoc()) {
        $player_ids[] = $row['id'];
    }
    // Consulta SQL para insertar datos de estadísticas
    $sql = "INSERT INTO final_averages (player_id, season, pts, ast, turnover, pf, fga, fgm, fta, ftm, fg3a, fg3m, reb, oreb, dreb, stl, blk, fg_pct, fg3_pct, ft_pct, min, games_played) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


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
                $url = "$urlAPIaverages?season[]=2023&player_ids[]=$player_id&per_page=25&cursor=$cursor";
                
                // Establecer opciones de cURL
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                
                // Realizar la petición
                $response = curl_exec($ch);
                echo '<br>'.$response;
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
                    foreach ($data['data'] as $average) {
                        
                        // Asignar valores a los parámetros de la consulta
                        $stmt->bind_param("iidddddddddddddddds", $averages['player_id'], $averages['season'], $averages['pts'], $averages['ast'], $averages['turnover'], $averages['pf'], $averages['fga'], $averages['fgm'], $averages['fta'], $averages['ftm'], $averages['fg3a'], $averages['fg3m'], $averages['reb'], $averages['oreb'], $averages['dreb'], $averages['stl'], $averages['blk'], $averages['fg_pct'], $averages['fg3_pct'], $averages['ft_pct'], $averages['min'], $averages['games_played']);
                        
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
        $con->close();
        echo "<br>[+] Los datos de las ESTADISITICAS se insertaron correctamente.<br>";


