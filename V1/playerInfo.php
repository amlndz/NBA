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
    if (isset($_GET['playerInfo'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $playerInfo = $_GET['playerInfo'];

        $playerName = '';
        $playerSurname = '';

        if (!empty($playerInfo)) {
            $playerInfo = strtolower($playerInfo);
            $parts = explode(' ', $playerInfo, 2);
            $playerName = $parts[0];
            $playerSurname = $parts[1];

            $sql = "SELECT p.*, t.full_name AS team_name FROM PLAYERS p JOIN TEAMS t ON p.team_id = t.id WHERE LOWER(p.first_name) LIKE ? AND LOWER(p.last_name) LIKE ? LIMIT 1";

            $stmt = $conn->prepare($sql);

            // Verificar si la consulta se preparó correctamente
            if ($stmt === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            // Vincular parámetros y ejecutar la consulta
            $playerName = '%' . strtolower($playerName) . '%';
            $playerSurname = '%' . strtolower($playerSurname) . '%';
            $stmt->bind_param("ss", $playerName, $playerSurname);
            $stmt->execute();

            // Obtener el resultado de la consulta
            $result = $stmt->get_result();

            // Verificar si se encontraron resultados
            if ($result->num_rows == 1) {
                // Guardar los valores en variables
                $row = $result->fetch_assoc();
                $id = $row['id'];
                $nombreJugador = $row['first_name'];
                $apellidoJugador = $row['last_name'];
                $posicion = $row['position'];
                $altura = $row['height'];
                $peso = $row['weight'];
                $equipoNombre = $row['team_name'];
                $numero = $row['number'];
                $draft = $row['draft'];
                $rondaDraft = $row['draft_round'];
                $pais = $row['country'];
                $numeroDraft = $row['draft_number'];

                // Mostrar los valores de los jugadores
                echo "ID: " . $id . "<br>";
                echo "Nombre: " . $nombreJugador . "<br>";
                echo "Apellido: " . $apellidoJugador . "<br>";
                echo "Posición: " . $posicion . "<br>";
                echo "Altura: " . $altura . "<br>";
                echo "Peso: " . $peso . "<br>";
                echo "Equipo: " . $equipoNombre . "<br>";
                echo "Número: " . $numero . "<br>";
                echo "Draft: " . $draft . "<br>";
                echo "Ronda de Draft: " . $rondaDraft . "<br>";
                echo "País: " . $pais . "<br>";
                echo "Número de Draft: " . $numeroDraft . "<br>";
                echo "<hr>";
            } else {
                echo "<script>alert('Por favor, ingrese el nombre o apellido de un jugador.');window.history.back();</script>";
            }

            // Cerrar la conexión
            $stmt->close();
        }
    } else {
        echo "¿A dónde vas pillín?";
    }
