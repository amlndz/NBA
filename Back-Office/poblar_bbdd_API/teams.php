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
    
    $urlAPIteams = "https://api.balldontlie.io/v1/teams";
    $token = "ae6447b0-567a-4d62-9760-a4acdbe1eed9";
    $header = array('Authorization: '.$token);

    $ch = curl_init();

    // Establecer opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $urlAPIteams);
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

    // Cerrar la sesión cURL
    curl_close($ch);

    // Consulta SQL para insertar datos
    $sql = "INSERT INTO TEAMS (id, abbreviation, name, full_name, city, conference, division) VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Preparar la consulta
    $stmt = $conn->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        die("Error al preparar la consulta: " . $conn->error);
    }
    // Recorrer los datos y realizar inserciones
    foreach ($data['data'] as $row) {
        // Asignar valores a los parámetros de la consulta
        $stmt->bind_param("issssss", $row['id'], $row['abbreviation'], $row['name'], $row['full_name'], $row['city'], $row['conference'], $row['division']);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si ocurrió algún error al ejecutar la consulta
        if ($stmt->errno) {
            echo "Error al insertar datos: " . $stmt->error;
        }
    }   

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conn->close();

    echo "Los datos se insertaron correctamente.";