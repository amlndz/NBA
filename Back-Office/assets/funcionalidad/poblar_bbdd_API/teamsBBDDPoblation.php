<?php
function reload_teams_table()
{
    require ("credentials.php");

    $con = connect();
    $urlAPIteams = "https://api.balldontlie.io/v1/teams";
    $header = array('Authorization: ' . $token);

    $ch = curl_init();
    // Establecer opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $urlAPIteams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error al realizar la petición: ' . curl_error($ch);
        exit;
    }

    $data = json_decode($response, true);

    // Verificar si la decodificación del JSON fue exitosa
    if ($data === null) {
        echo 'Error al decodificar el JSON.';
        return;
    }

    // Consulta SQL para insertar los datos en la tabla final_teams
    $sql = "INSERT INTO final_teams (id, abbreviation, city, conference, division, full_name, name) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $con->prepare($sql);

    // Verificar si la consulta se preparó correctamente
    if ($stmt === false) {
        echo "Error al preparar la consulta: " . $con->error;
        return;
    }

    // Recorrer cada equipo en los datos decodificados
    foreach ($data['data'] as $row) {
        $id = $row['id'];

        if ($id > 31) {
            break;
        }

        $abbreviation = $row['abbreviation'];
        $city = $row['city'];
        $conference = $row['conference'];
        $division = $row['division'];
        $full_name = $row['full_name'];
        $name = $row['name'];

        // Asignar valores a los parámetros de la consulta
        $stmt->bind_param("issssss", $id, $abbreviation, $city, $conference, $division, $full_name, $name);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si ocurrió algún error al ejecutar la consulta
        if ($stmt->errno) {
        }
    }

    curl_close($ch);
    $stmt->close();
    $con->close();

    echo "[+] Los datos de los EQUIPOS se insertaron correctamente [+]\n";
}
?>