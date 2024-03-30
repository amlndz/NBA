<?php
        include "connection.php";
        require "credentials.php"; 
        $con = connect();
        $urlAPIteams = "https://api.balldontlie.io/v1/teams";
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

        curl_close($ch);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Cerrar la sesión cURL

        // Consulta SQL para insertar datos
        $sql = "INSERT INTO final_teams (id, abbreviation, city,conference, division, full_name, name) VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        $stmt = $con->prepare($sql);

        // Verificar si la consulta se preparó correctamente
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $con->error);
        }
        // Recorrer los datos y realizar inserciones
        foreach ($data['data'] as $row) {
            // Asignar valores a los parámetros de la consulta
            $stmt->bind_param("issssss", $row['id'], $row['abbreviation'], $row['name'], $row['full_name'], $row['city'], $row['conference'], $row['division']);
            echo "<br>id: ".$row['id']."<br>";
            // Ejecutar la consulta
            $stmt->execute();
            // Verificar si ocurrió algún error al ejecutar la consulta
            if ($stmt->errno) {
                echo "Error al insertar datos: " . $stmt->error;
            }
        }   

        // Cerrar la conexión y liberar recursos
        $stmt->close();
        $con->close();
        echo "<br>[+] Los datos de los EQUIPOS se insertaron correctamente.<br>";
