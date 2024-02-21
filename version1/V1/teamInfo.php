<?php
    $servername = "http://webalumnos.tlm.unavarra.es:10800";
    $username = "grupo25";
    $password = "fex1roMi4j";
    $database = "db_grupo25";

    // Creamos conexión
    $conn = new mysqli("dbserver", $username, $password, $database);

    // Verificamos conexión
    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }

    // Verificar si se ha enviado información del jugador a través de GET
    if (isset($_GET['teamInfo'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $teamInfo = $_GET['teamInfo'];
        if (!empty($teamInfo)) {
            $teamInfo = strtolower($teamInfo);
            $sql = "SELECT * FROM TEAMS t WHERE LOWER(t.abbreviation) LIKE ? or LOWER(t.full_name) LIKE ? or LOWER(t.name) LIKE ?";

            $stmt = $conn->prepare($sql);

            // Verificar si la consulta se preparó correctamente
            if ($stmt === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            $teamInfo = strtolower($teamInfo);
            $stmt->bind_param("sss", $teamInfo, $teamInfo, $teamInfo);
            $stmt->execute();
            // Obtener el resultado de la consulta
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $abbreviation = $row['abbreviation'];
            $city = $row['city'];
            $conference = $row['conference'];
            $division = $row['division'];
            $full_name = $row['full_name'];
            $name = $row['name'];

            echo "EQUIPO: ".$full_name." (".$abbreviation.")"."<br>";
            echo "CIUDAD: ".$city."<br>";
            echo "CONFERENCIA: ".$conference."<br>";
            echo "DIVISION: ".$division."<br>";
        }

        

    }
    else {
            echo "error";
        }
