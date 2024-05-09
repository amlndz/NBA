<?php
if (isset($_GET['id'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $id = $_GET['id'];
        if (!empty($id)) {
            $sql = "SELECT * FROM final_teams t WHERE t.id LIKE ?";
            $stmt = $conn->prepare($sql);

            // Verificar si la consulta se preparó correctamente
            if ($stmt === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param("i", $id);
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
        }
        // Obtener los jugadores del equipo
        $sql_players = "SELECT * FROM final_players WHERE team_id = ?";
        $stmt_players = $conn->prepare($sql_players);

        if ($stmt_players === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt_players->bind_param("i", $id);
        $stmt_players->execute();

        // Obtener el resultado de la consulta
        $result_players = $stmt_players->get_result();
        $players = $result_players->fetch_all(MYSQLI_ASSOC);

        $sql_team_history = "SELECT history FROM final_history WHERE team_id = ?";
        $stmt_team_history = $conn->prepare($sql_team_history);
        $stmt_team_history->bind_param("i", $id);
        $stmt_team_history->execute();
        $result_team_history = $stmt_team_history->get_result();
        $row_team_history = $result_team_history->fetch_assoc();
        $team_history = $row_team_history['history'];

        $sql_victorias_local = "SELECT COUNT(*) as victorias_local FROM final_games WHERE home_team_id = ? AND home_team_score > visitor_team_score";
        $stmt_victorias_local = $conn->prepare($sql_victorias_local);
        $stmt_victorias_local->bind_param("i", $id);
        $stmt_victorias_local->execute();
        $result_victorias_local = $stmt_victorias_local->get_result();
        $row_victorias_local = $result_victorias_local->fetch_assoc();
        $victorias_local = $row_victorias_local['victorias_local'];

        $sql_victorias_visitante = "SELECT COUNT(*) as victorias_visitante FROM final_games WHERE visitor_team_id = ? AND visitor_team_score > home_team_score";
        $stmt_victorias_visitante = $conn->prepare($sql_victorias_visitante);
        $stmt_victorias_visitante->bind_param("i", $id);
        $stmt_victorias_visitante->execute();
        $result_victorias_visitante = $stmt_victorias_visitante->get_result();
        $row_victorias_visitante = $result_victorias_visitante->fetch_assoc();
        $victorias_visitante = $row_victorias_visitante['victorias_visitante'];

        $sql_derrotas_local = "SELECT COUNT(*) as derrotas_local FROM final_games WHERE home_team_id = ? AND home_team_score < visitor_team_score";
        $stmt_derrotas_local = $conn->prepare($sql_derrotas_local);
        $stmt_derrotas_local->bind_param("i", $id);
        $stmt_derrotas_local->execute();
        $result_derrotas_local = $stmt_derrotas_local->get_result();
        $row_derrotas_local = $result_derrotas_local->fetch_assoc();
        $derrotas_local = $row_derrotas_local['derrotas_local'];

        $sql_derrotas_visitante = "SELECT COUNT(*) as derrotas_visitante FROM final_games WHERE visitor_team_id = ? AND visitor_team_score < home_team_score";
        $stmt_derrotas_visitante = $conn->prepare($sql_derrotas_visitante);
        $stmt_derrotas_visitante->bind_param("i", $id);
        $stmt_derrotas_visitante->execute();
        $result_derrotas_visitante = $stmt_derrotas_visitante->get_result();
        $row_derrotas_visitante = $result_derrotas_visitante->fetch_assoc();
        $derrotas_visitante = $row_derrotas_visitante['derrotas_visitante'];

        $victorias = $victorias_local + $victorias_visitante;
        $derrotas = $derrotas_local + $derrotas_visitante;

        $sql_puntuaciones = "SELECT date, IF(home_team_id = ?, home_team_score, visitor_team_score) as score FROM final_games WHERE home_team_id = ? OR visitor_team_id = ? ORDER BY date";
        $stmt_puntuaciones = $conn->prepare($sql_puntuaciones);
        $stmt_puntuaciones->bind_param("iii", $id, $id, $id);
        $stmt_puntuaciones->execute();
        $result_puntuaciones = $stmt_puntuaciones->get_result();
        $puntuaciones = array();
        while ($row = $result_puntuaciones->fetch_assoc()) {
            $puntuaciones[$row['date']] = $row['score'];
        }

        // Puedes usar la misma consulta y datos que para el gráfico de columnas
        $evolucion = $puntuaciones;


    }
    else {
        header("Location: teams.php");
        exit;
        }