<?php


    $servername="http://webalumnos.tlm.unavarra.es:10800";
    $username="grupo25";
    $password="fex1roMi4j";
    $database = "db_grupo25";

    // Creamos conexion
    $conn = new mysqli("dbserver", $username, $password, $database);


    // Verificamos conexion
    if ($conn->connect_error) {
        die("La conexion fallo: ".$conn->connect_error);
    }


    $urlAPIplayers = "https://api.balldontlie.io/v1/players";

    $token = "ae6447b0-567a-4d62-9760-a4acdbe1eed9";
    $header = array('Authorization: '.$token);

    $ch = curl_init();

    // Variables para el cursor y el límite de resultados por página
    $cursor = 0;
    $per_page = 25;

    do {
        $url = "$urlAPIplayers?per_page=$per_page&cursor=$cursor";
        

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
        $sql = "INSERT INTO players (id, first_name, last_name, position, height, weight, team_id, number, draft) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        // Preparar la consulta
        $stmt = $conn->prepare($sql);
    
        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }


        // Recorrer los datos y realizar inserciones
        foreach ($data['data'] as $player) {
            // Extraer la información del equipo
            $team_id = $player['team']['id'];

            // Asignar valores a los parámetros de la consulta
            $stmt->bind_param("isssddiii", $player['id'], $player['first_name'], $player['last_name'], $player['position'], $player['height'], $player['weight'], $team_id, $player['jersey_number'], $player['draft_year']);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si ocurrió algún error al ejecutar la consulta
            if ($stmt->errno) {
                echo "Error al insertar datos: " . $stmt->error;
            }
            $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;
        }
    }while ($cursor !== null);

    // Cerrar la sesión cURL
    curl_close($ch);

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conn->close();

    echo "Los datos se insertaron correctamente.";
