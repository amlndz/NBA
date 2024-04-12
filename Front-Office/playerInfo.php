<?php

    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    checkSessionTimeout();

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
        // Consulta Jugador y equipo
        $player_sql = "SELECT p.*, t.full_name AS team_name, t.id AS team_id
                    FROM final_players p
                    JOIN final_teams t ON p.team_id = t.id
                    WHERE p.id = ?
                    LIMIT 1";

        $player_stmt = $conn->prepare($player_sql);
    
        if ($player_stmt === false) {
            die("Error al preparar la consulta de jugador y equipo: " . $conn->error);
        }
    
        $player_stmt->bind_param("i", $id);
        $player_stmt->execute();
        $player_result = $player_stmt->get_result();
    
        if ($player_result->num_rows == 1) {
            $player_row = $player_result->fetch_assoc();
            $id = $player_row['id'];
            $team_id = $player_row['team_id'];
            $nombreJugador = $player_row['first_name'];
            $apellidoJugador = $player_row['last_name'];
            $posicion = $player_row['position'];
            $altura = $player_row['height'];
            $peso = $player_row['weight'];
            $equipoNombre = $player_row['team_name'];
            $numero = $player_row['number'];
            $draft = $player_row['draft'];
            $rondaDraft = $player_row['draft_round'];
            $pais = $player_row['country'];
            $numeroDraft = $player_row['draft_number'];

            $team_url = "teamInfo.php?id=".urlencode($team_id);

            $average_sql = "SELECT a.*
                        FROM final_averages a 
                        WHERE a.player_id = ?
                        LIMIT 1";

            $average_stmt = $conn->prepare($average_sql);

            if ($average_stmt === false) {
                die("Error al preparar la consulta de estadísticas promedio: " . $conn->error);
            }

            $average_stmt->bind_param("i", $id);
            $average_stmt->execute();
            $average_result = $average_stmt->get_result();
            $exist_averages = false;
            if ($average_result->num_rows == 1) {
                $exist_averages = true;
                $average_row = $average_result->fetch_assoc();
                // Guardar los valores en variables
                $avg_min = $average_row['min'];
                $avg_fgm = $average_row['fgm'];
                $avg_fga = $average_row['fga'];
                $avg_fg_pct = $average_row['fg_pct'];
                $avg_fg3m = $average_row['fg3m'];
                $avg_fg3a = $average_row['fg3a'];
                $avg_fg3_pct = $average_row['fg3_pct'];
                $avg_ftm = $average_row['ftm'];
                $avg_fta = $average_row['fta'];
                $avg_ft_pct = $average_row['ft_pct'];
                $avg_oreb = $average_row['oreb'];
                $avg_dreb = $average_row['dreb'];
                $avg_reb = $average_row['reb'];
                $avg_ast = $average_row['ast'];
                $avg_stl = $average_row['stl'];
                $avg_blk = $average_row['blk'];
                $avg_turnover = $average_row['turnover'];
                $avg_pf = $average_row['pf'];
                $avg_pts = $average_row['pts'];
                $avg_gms = $average_row['games_played'];
                // Cerrar la conexión
                $average_stmt->close();


                $stats_sql = "SELECT g.date AS game_date, s.pts, s.ast, s.reb
                            FROM final_stats s 
                            JOIN final_games g ON s.game_id = g.id 
                            WHERE s.player_id = ? 
                            ORDER BY g.date";

                $stats_stmt = $conn->prepare($stats_sql);

                if ($stats_stmt === false) {
                    die("Error al preparar la consulta de estadísticas de puntos, asistencias y rebotes: " . $conn->error);
                }

                $stats_stmt->bind_param("i", $id);
                $stats_stmt->execute();
                $stats_result = $stats_stmt->get_result();

                // Inicializar arrays para almacenar los datos del progreso de puntos, asistencias, rebotes y las fechas de los partidos
                $game_dates = array();
                $points = array();
                $assists = array();
                $rebounds = array();

                // Procesar los resultados de la consulta
                while ($row = $stats_result->fetch_assoc()) {
                    // Almacenar las fechas de los partidos, los puntos anotados, las asistencias y los rebotes en arrays separados
                    $game_dates[] = date('Y-m-d', strtotime($row['game_date']));
                    $points[] = $row['pts'];
                    $assists[] = $row['ast'];
                    $rebounds[] = $row['reb'];
                }

                $stats_stmt->close();

                $points_home = array();
                $points_away = array();

                // Consulta SQL para obtener los puntos anotados por partido como local y como visitante
                $points_sql = "SELECT g.home_team_id, g.visitor_team_id, s.pts
                                FROM final_stats s 
                                JOIN final_games g ON s.game_id = g.id 
                                WHERE s.player_id = ?";

                $points_stmt = $conn->prepare($points_sql);

                if ($points_stmt === false) {
                    die("Error al preparar la consulta de puntos anotados por partido: " . $conn->error);
                }

                $points_stmt->bind_param("i", $id);
                $points_stmt->execute();
                $points_result = $points_stmt->get_result();

                // Procesar los resultados de la consulta
                while ($row = $points_result->fetch_assoc()) {
                    // Verificar si el jugador es local o visitante en el partido y almacenar los puntos correspondientes
                    if ($row['home_team_id'] == $team_id) {
                        $points_home[] = $row['pts']; // Puntos anotados como local
                    } else if ($row['visitor_team_id'] == $team_id) {
                        $points_away[] = $row['pts']; // Puntos anotados como visitante
                    }
                }

                $points_stmt->close();

                // Calcular la media de puntos anotados como local y como visitante
                $avg_points_home = count($points_home) > 0 ? array_sum($points_home) / count($points_home) : 0;
                $avg_points_away = count($points_away) > 0 ? array_sum($points_away) / count($points_away) : 0;

                // Obtener los jugadores del equipo
                $sql_players = "SELECT * FROM final_players WHERE team_id = ? and id != ?";
                $stmt_players = $conn->prepare($sql_players);

                if ($stmt_players === false) {
                    die("Error al preparar la consulta: " . $conn->error);
                }

                $stmt_players->bind_param("ii", $team_id, $id);
                $stmt_players->execute();

                // Obtener el resultado de la consulta
                $result_players = $stmt_players->get_result();
                $players = $result_players->fetch_all(MYSQLI_ASSOC);
            }
        }else {
            header("Location: players.php");
            exit;
        } 
        // Cerrar la conexión
        $player_stmt->close();
    
    } else {
        header("Location: players.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>NBA</title>
    <link rel="icon" href="./assets/img/nba.avif">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="./assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="./assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="./assets/css/style.min.css" rel="stylesheet">

    <!-- Librerias para las graficas -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
<!-- Navbar Start -->
<div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-1 m-0">
                <img src="./assets/img/logoNBA.png" id="logo-menu-image" alt="nba" width=20% height=20%><!-- Logo -->
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                <a href="index.php" class="nav-item nav-link">Inicio</a>
                    <a href="players.php" class="nav-item nav-link active">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link">Equipos</a>
                    <!-- <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="about.php" class="nav-item nav-link">About</a> -->
                    <div class="nav-item dropdown">
                        <?php if (!$usuario_autenticado): ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" alt="user"></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="login.php" class="dropdown-item">Log in</a>
                                <a href="signin.php" class="dropdown-item">Sign in</a>
                            </div>
                        <?php else: ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" alt=""></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="logout.php" class="dropdown-item">Log Out</a> 
                            </div>                            
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5 player-spaces-diff" style="min-height: 400px">
            <?php echo "<a href='$team_url'><img src='./assets/img/teams/" . $team_id . ".svg' alt='imagen logo equipo' width='170rem'></a>"; ?>
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase "><?php echo $nombreJugador . " " . $apellidoJugador; ?></h1>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- player Content -->

    <div class="container player-spaces-diff-2">
        <div class="container-fluid py-8 d-flex justify-content-center"> <!-- Añade flexbox para centrar -->
            <div class="row">
                <div class="col-md-4"> <!-- Columna para la imagen -->
                    <img class="img-fluid mb-4 mb-sm-0 player-picture" src="<?php echo "./assets/img/players/".$id.".avif" ?>" alt="">
                </div>
                <div class="col-md-8"> <!-- Columna para la información del jugador -->
                    <div class="row">
                        <div class="col-md-6"> <!-- Columna para el primer bloque de información -->
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Nombre:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $nombreJugador; ?></p>
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Apellido:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $apellidoJugador; ?></p>
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Equipo:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo "<a href=$team_url>$equipoNombre</a>" ?></p>
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Posición:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $posicion; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6"> <!-- Columna para el segundo bloque de información -->
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Altura:</h5> <!-- Agrega clase para margen a la derecha -->
                                <?php 
                                    // Dividir la altura en pies y pulgadas
                                    list($pies, $pulgadas) = explode('.', $altura);

                                    $altura_total_pulgadas = $pies * 12 + $pulgadas;

                                    $altura_cm = $altura_total_pulgadas * 2.54;

                                    echo round($altura_cm, 2) . " cm"; 
                                    ?>
                                    
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Peso:</h5> <!-- Agrega clase para margen a la derecha -->
                                <?php 
                                    // Convertir el peso de libras a kilogramos
                                    $peso_kg = $peso * 0.453592;
                                    echo round($peso_kg, 2) . " kg"; // Redondea el resultado y muestra "kg"
                                ?>
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Número:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $numero; ?></p>
                            </div>
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">País:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $pais; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6"> <!-- Columna para el tercer bloque de información -->
                            <div class="mb-4 d-flex "> <!-- Espacio entre bloques y flexbox -->
                                <h5 class="mr-2">Draft:</h5> <!-- Agrega clase para margen a la derecha -->
                                <p><?php echo $draft." ronda ".$rondaDraft." número ".$numeroDraft; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($exist_averages){ ?>
        <div class="container player-spaces-diff justify-content-cente">
            <div class="container-fluid py-8 d-flex justify-content-center player-spaces-diff"> <!-- Añade flexbox para centrar -->
                <h1 class="text-primary text-uppercase" style="letter-spacing: 5px;">PROMEDIO</h1>
            </div>
            <div class="container-fluid  d-flex justify-content-center"> <!-- Añade flexbox para centrar -->            
                <div class="row">
                    <div class="col-md-12">
                        <table class="styled-table">
                            <tr>
                                <th title="minutos/partido">MINS</th>
                                <th title="partidos jugados">GMS</th>
                                <th title="puntos/partido">PTS</th>
                                <th title="tiros de campo anotados">FGM</th>
                                <th title="tiros de campo intentados">FGA</th>
                                <th title="porcentaje de tiros de campo">FG%</th>
                                <th title="tiros de tres puntos anotados">FG3M</th>
                                <th title="tiros de tres puntos intentados">FG3A</th>
                                <th title="porcentaje de tiros de tres puntos">FG3%</th>
                                <th title="tiros libres anotados">FTM</th>
                            </tr>
                            <tr>
                                <td><?php echo $avg_min; ?></td>
                                <td><?php echo $avg_gms; ?></td>
                                <td><?php echo $avg_pts; ?></td>
                                <td><?php echo $avg_fgm; ?></td>
                                <td><?php echo $avg_fga; ?></td>
                                <td><?php echo $avg_fg_pct*100; ?></td>
                                <td><?php echo $avg_fg3m; ?></td>
                                <td><?php echo $avg_fg3a; ?></td>
                                <td><?php echo $avg_fg3_pct*100; ?></td>
                                <td><?php echo $avg_ftm; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="container-fluid d-flex justify-content-center"> <!-- Añade flexbox para centrar -->
                <div class="row">
                    <div class="col-md-12">
                        <table class="styled-table">
                            <tr>
                                <th title="rebotes ofensivos">OREB</th>
                                <th title="rebotes defensivos">DREB</th>
                                <th title="rebotes totales">REB</th>
                                <th title="asistencias">AST</th>
                                <th title="robos">STL</th>
                                <th title="bloqueos">BLK</th>
                                <th title="pérdidas de balón">TRNOVER</th>
                                <th title="faltas personales">PF</th>
                                <th title="tiros libres intentados">FTA</th>
                                <th title="porcentaje de tiros libres">FT%</th>
                            </tr>
                            <tr>
                                <td><?php echo $avg_oreb; ?></td>
                                <td><?php echo $avg_dreb; ?></td>
                                <td><?php echo $avg_reb; ?></td>
                                <td><?php echo $avg_ast; ?></td>
                                <td><?php echo $avg_stl; ?></td>
                                <td><?php echo $avg_blk; ?></td>
                                <td><?php echo $avg_turnover; ?></td>
                                <td><?php echo $avg_pf; ?></td>
                                <td><?php echo $avg_fta; ?></td>
                                <td><?php echo $avg_ft_pct*100; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="container player-spaces-diff-2">
            <div class="section-title">
                <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Estadísticas</h4>
            </div>
            <div class="container-fluid py-8 d-flex justify-content-center player-spaces-diff "> <!-- Añade flexbox para centrar -->
                <div class="graphs-teams" id="linechart"></div>
            </div>
            <div class="container-fluid py-8 d-flex justify-content-center "> <!-- Añade flexbox para centrar -->
                <div class="graphs-teams" id="columnchart"></div>
            </div>
            <div class="container-fluid py-8 d-flex justify-content-center player-spaces-diff "> <!-- Añade flexbox para centrar -->
                <div class="graphs-teams" id="piechart"></div>
                <div class="graphs-teams" id="piechartRebounds"></div>
            </div>
        </div>

        <!-- Código JavaScript para cargar las bibliotecas de Google Charts -->
        <script type="text/javascript">
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                drawLineChart();
                drawColumnChart();
                drawPieCharts();
            }

            // Función para dibujar el gráfico de línea
            function drawLineChart() {
            var gameDates = <?php echo json_encode($game_dates); ?>;
            var points = <?php echo json_encode($points); ?>;
            var assists = <?php echo json_encode($assists); ?>;
            var rebounds = <?php echo json_encode($rebounds); ?>;

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fecha del Partido');
            data.addColumn('number', 'Puntos Anotados');
            data.addColumn('number', 'Asistencias');
            data.addColumn('number', 'Rebotes');

            for (var i = 0; i < gameDates.length; i++) {
                data.addRow([gameDates[i], points[i], assists[i], rebounds[i]]);
            }

            var options = {
                title: 'Puntos, Asistencias y Rebotes por partido',
                width: 1200,
                height: 500,
                backgroundColor: 'transparent', 
                legend: { position: 'bottom' }, // Coloca la leyenda en la parte inferior
                colors: ['#DA9F5B', '#33211D', '#1E824C']
            };

            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }


            // Función para dibujar el gráfico de columnas
            function drawColumnChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Tipo');
                data.addColumn('number', 'Media de Puntos');

                data.addRow(['Local', <?php echo $avg_points_home; ?>]);
                data.addRow(['Visitante', <?php echo $avg_points_away; ?>]);

                var options = {
                    title: 'Media de Puntos Anotados como Local y Visitante',
                    width: 500,
                    height: 500,
                    backgroundColor: 'transparent', 
                    legend: { position: 'none' },
                    colors: ['#DA9F5B', '#5B9BD5'],
                    hAxis: { minValue: 0 },
                    vAxis: { minValue: 0 }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('columnchart'));
                chart.draw(data, options);
            }

            // Función para dibujar los gráficos de tarta
            function drawPieCharts() {
                var dataPoints = google.visualization.arrayToDataTable([
                    ['Tipo', 'Puntos'],
                    ['Puntos de 2', <?php echo $avg_fgm * 2; ?>],
                    ['Triples', <?php echo $avg_fg3m * 3; ?>],
                    ['Tiros Libres', <?php echo $avg_ftm; ?>]
                ]);

                var optionsPoints = {
                    title: 'Distribución de Puntos',
                    width: 500,
                    height: 500,
                    backgroundColor: 'transparent', 
                    legend: { position: 'bottom' },
                    colors: ['#DA9F5B', '#33211D', '#1E824C'],
                    pieSliceText: 'none'
                };

                var chartPoints = new google.visualization.PieChart(document.getElementById('piechart'));
                chartPoints.draw(dataPoints, optionsPoints);

                var dataRebounds = google.visualization.arrayToDataTable([
                    ['Tipo', 'Rebotes'],
                    ['Ofensivos', <?php echo $avg_oreb; ?>],
                    ['Defensivos', <?php echo $avg_dreb; ?>],
                ]);

                var optionsRebounds = {
                    title: 'Distribución de Rebotes',
                    width: 500,
                    height: 500,
                    backgroundColor: 'transparent', 
                    legend: { position: 'bottom' },
                    colors: ['#33211D', '#DA9F5B'],
                    pieSliceText: 'none'
                };

                var chartRebounds = new google.visualization.PieChart(document.getElementById('piechartRebounds'));
                chartRebounds.draw(dataRebounds, optionsRebounds);
            }
        </script>
    <?php } ?>

    <!-- Plantilla -->
    <div class="container teams-stats-graphs teams-stats-graphs">
        <div class="container-fluid py-8 d-flex justify-content-center teams-stats-graphs-2"> <!-- Añade flexbox para centrar -->
            <h1 class="text-primary text-uppercase" style="letter-spacing: 5px;"><?php echo "<a href=$team_url>$equipoNombre</a>" ?></h1>
        </div>
        <div class="owl-carousel testimonial-carousel teams-stats-graphs">
            <?php foreach ($players as $player):
                $playerId = $player['id'];
                $url = "playerInfo.php?id=".urlencode($playerId); ?>
                <div class=" owl-item testimonial-item">
                    <a href="<?php echo $url ?>">
                        <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/players/".$playerId.".avif' alt='imagen jugador'";?> onerror="this.onerror=null;this.src='./assets/img/players/default.avif'">
                    </a>
                    <div class="player-info">
                        <a href="<?php echo $url ?>">
                            <h4><?php echo $player['first_name'] . ' ' . $player['last_name']; ?></h4>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- Content End -->


    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

