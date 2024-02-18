<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Jugadores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
        .player-name {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
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

    // Verificar si se ha enviado información del jugador a través de GET
    if (isset($_GET['playerInfo'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $playerInfo = $_GET['playerInfo'];

        // Preparar la consulta SQL
        $sql = "SELECT p.first_name, p.last_name, p.number, t.full_name as team_name 
                FROM PLAYERS p
                INNER JOIN TEAMS t ON p.team_id = t.id
                WHERE LOWER(p.first_name) LIKE ? OR LOWER(p.last_name) LIKE ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros y ejecutar la consulta
        $playerInfo = '%' . $playerInfo . '%';
        $stmt->bind_param("ss", $playerInfo, $playerInfo);
        $stmt->execute();

        // Obtener el resultado de la consulta
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            echo "<h2>Lista de jugadores:</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                // Construir el enlace con nombre y apellido como parámetros GET
                $playerInfoUrl=$row['first_name']." ". $row['last_name'];
                $url = "playerInfo.php?playerInfo=" . urlencode($playerInfoUrl);
                echo "<li>Nombre: <a class='player-name' href=$url>" . $row['first_name'] . " " . $row['last_name'] . "</a> - Dorsal: " . $row['number'] . " - Equipo: " . $row['team_name'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<script>alert('Por favor, ingrese el nombre o apellido de un jugador.');window.history.back();</script>";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        // Si no se proporciona ningún valor en el campo jugador, redirigir al usuario de vuelta a la página anterior
        echo "<script>alert('Por favor, ingrese el nombre o apellido de un jugador.');window.history.back();</script>";
    }

    // Cerrar la conexión
    $conn->close();
    ?>
</body>
</html>
