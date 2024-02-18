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

    $playerInfo = $_POST['playerInfo'] ?? '';

    $playerName = '';
    $playerSurname = '';

    if(!empty($playerInfo)){
        $playerInfo = strtolower($playerInfo);
        $parts = explode(' ', $playerInfo, 2);
        $playerName = $parts[0];
        $playerSurname = isset($parts[1]) ? $parts[1] : '';

        if(count($parts) == 2){
            $sql = "SELECT * FROM PLAYERS WHERE LOWER(first_name) LIKE '%$nombre%' AND LOWER(last_name) LIKE '%$nombre%'";
        }
        else{
            $sql = "SELECT * FROM PLAYERS WHERE LOWER(first_name) LIKE '%$nombre%' OR LOWER(first_name) LIKE '%$apellido%' OR LOWER(last_name) LIKE '%$nombre%' OR LOWER(last_name) LIKE '%$apellido%'";
        }

        // Ejecutar la consulta SQL
        $result = $conn->query($sql);

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            // Mostrar los datos de los jugadores encontrados
            echo "<h1>Jugadores encontrados</h1>";
            while ($row = $result->fetch_assoc()) {
                // Guardar los valores en variables
                $id = $row['id'];
                $nombreJugador = $row['first_name'];
                $apellidoJugador = $row['last_name'];
                $posicion = $row['position'];
                $altura = $row['height'];
                $peso = $row['weight'];
                $equipoId = $row['team_id'];
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
                echo "Equipo ID: " . $equipoId . "<br>";
                echo "Número: " . $numero . "<br>";
                echo "Draft: " . $draft . "<br>";
                echo "Ronda de Draft: " . $rondaDraft . "<br>";
                echo "País: " . $pais . "<br>";
                echo "Número de Draft: " . $numeroDraft . "<br>";
                echo "<hr>";
            }
            echo "No se encontraron jugadores para el nombre proporcionado.";
        }
    }  else {
        // Si no se proporciona ningún valor en el campo jugador, redirigir al usuario de vuelta a la página anterior
        echo "<script>alert('Por favor, ingrese el nombre o apellido de un jugador.');window.history.back();</script>";
    }
    // Cerrar la conexión
    $conn->close();
    

    