<?php

    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    if(!$usuario_autenticado){
        $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php");
        exit;
    }
    include "connection.php";
    $conn = connect();

    // Verificar si se ha enviado información del jugador a través de GET
    if (isset($_GET['id'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $id = $_GET['id'];

        if (!empty($id)) {

            $sql = "SELECT p.*,t.full_name AS team_name, ROUND(AVG(s.min), 1) AS avg_min, ROUND(AVG(s.fgm), 1) AS avg_fgm, ROUND(AVG(s.fga), 1) AS avg_fga, ROUND(AVG(s.fg_pct), 1) AS avg_fg_pct, ROUND(AVG(s.fg3m), 1) AS avg_fg3m, ROUND(AVG(s.fg3a), 1) AS avg_fg3a, ROUND(AVG(s.fg3_pct), 1) AS avg_fg3_pct, ROUND(AVG(s.ftm), 1) AS avg_ftm, ROUND(AVG(s.fta), 1) AS avg_fta, ROUND(AVG(s.ft_pct), 1) AS avg_ft_pct, ROUND(AVG(s.oreb), 1) AS avg_oreb, ROUND(AVG(s.dreb), 1) AS avg_dreb, ROUND(AVG(s.reb), 1) AS avg_reb, ROUND(AVG(s.ast), 1) AS avg_ast, ROUND(AVG(s.stl), 1) AS avg_stl, ROUND(AVG(s.blk), 1) AS avg_blk, ROUND(AVG(s.turnover), 1) AS avg_turnover, ROUND(AVG(s.pf), 1) AS avg_pf, ROUND(AVG(s.pts), 1) AS avg_pts
            FROM final_players p 
            JOIN final_stats s ON p.id = s.player_id
            JOIN final_teams t ON p.team_id = t.id
            WHERE p.id LIKE ?
            LIMIT 1";
            $stmt = $conn->prepare($sql);

            // Verificar si la consulta se preparó correctamente
            if ($stmt === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            // Vincular parámetros y ejecutar la consulta
            
            $stmt->bind_param("i", $id);
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

                // Estadísticas promedio
                $avg_min = $row['avg_min'];
                $avg_fgm = $row['avg_fgm'];
                $avg_fga = $row['avg_fga'];
                $avg_fg_pct = $row['avg_fg_pct'];
                $avg_fg3m = $row['avg_fg3m'];
                $avg_fg3a = $row['avg_fg3a'];
                $avg_fg3_pct = $row['avg_fg3_pct'];
                $avg_ftm = $row['avg_ftm'];
                $avg_fta = $row['avg_fta'];
                $avg_ft_pct = $row['avg_ft_pct'];
                $avg_oreb = $row['avg_oreb'];
                $avg_dreb = $row['avg_dreb'];
                $avg_reb = $row['avg_reb'];
                $avg_ast = $row['avg_ast'];
                $avg_stl = $row['avg_stl'];
                $avg_blk = $row['avg_blk'];
                $avg_turnover = $row['avg_turnover'];
                $avg_pf = $row['avg_pf'];
                $avg_pts = $row['avg_pts'];


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
                echo "Estadísticas Promedio: <br>";
                echo "Minutos: " . $avg_min . "<br>";
                echo "FGM: " . $avg_fgm . "<br>";
                echo "FGA: " . $avg_fga . "<br>";
                echo "FG%: " . $avg_fg_pct . "<br>";
                echo "FG3M: " . $avg_fg3m . "<br>";
                echo "FG3A: " . $avg_fg3a . "<br>";
                echo "FG3%: " . $avg_fg3_pct . "<br>";
                echo "FTM: " . $avg_ftm . "<br>";
                echo "FTA: " . $avg_fta . "<br>";
                echo "FT%: " . $avg_ft_pct . "<br>";
                echo "OREB: " . $avg_oreb . "<br>";
                echo "DREB: " . $avg_dreb . "<br>";
                echo "REB: " . $avg_reb . "<br>";
                echo "AST: " . $avg_ast . "<br>";
                echo "STL: " . $avg_stl . "<br>";
                echo "BLK: " . $avg_blk . "<br>";
                echo "Turnover: " . $avg_turnover . "<br>";
                echo "PF: " . $avg_pf . "<br>";
                echo "PTS: " . $avg_pts . "<br>";

            } else {
                echo "<script>alert('Por favor, ingrese el nombre o apellido de un jugador.');window.history.back();</script>";
            }

            // Cerrar la conexión
            $stmt->close();
        }
    } else {
        header("Location: players.php");
        exit;
    }
