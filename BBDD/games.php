<?php
$servername = "http://webalumnos.tlm.unavarra.es:10800";
$username = "grupo25";
$password = "fex1roMi4j";
$database = "db_grupo25";

// Crear conexión
$conn = new mysqli("dbserver", $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

$urlAPIgames = "https://api.balldontlie.io/v1/games";
$token = "ae6447b0-567a-4d62-9760-a4acdbe1eed9";
$header = array('Authorization: '.$token);

// Consulta SQL para insertar datos de partidos
$sql = "INSERT INTO GAMES (id, date, season, status, period, time, postseason, home_team_score, visitor_team_score, home_team_id, visitor_team_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Preparar la consulta
$stmt = $conn->prepare($sql);

// Verificar si la consulta se preparó correctamente
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conn->error);
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

    // Verificar si hay datos y si es un array
    if (is_array($data['data'])) {
        // Insertar los datos de los partidos en la base de datos
        foreach ($data['data'] as $game) {
            // Extraer la información del partido
            $home_team_id = $game['home_team']['id'];
            $visitor_team_id = $game['visitor_team']['id'];

            // Asignar valores a los parámetros de la consulta
            $stmt->bind_param("isssisiiiii", $game['id'], $game['date'], $game['season'], $game['status'], $game['period'], $game['time'], $game['postseason'], $game['home_team_score'], $game['visitor_team_score'], $home_team_id, $visitor_team_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si ocurrió algún error al ejecutar la consulta
            if ($stmt->errno) {
                echo "Error al insertar datos: " . $stmt->error;
            }
        }
    } else {
        // Si no hay datos o no es un array, muestra un mensaje de advertencia
        echo "No se encontraron datos válidos en la respuesta JSON.";
    }

    // Actualizar el cursor
    $cursor = isset($data['meta']['next_cursor']) ? $data['meta']['next_cursor'] : null;

} while ($cursor !== null);

// Cerrar la sesión cURL
curl_close($ch);

// Cerrar la conexión y liberar recursos
$stmt->close();
$conn->close();

echo "Todos los partidos han sido cargados.";
?>
