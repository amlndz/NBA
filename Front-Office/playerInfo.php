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
            <img src="./assets/img/teams/<?php echo $team_id; ?>.svg" alt="imagen logo equipo" width=170rem>        
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
                        <?php $url = "teamInfo.php?id=".urlencode($team_id); ?>
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
                                <p><?php echo "<a href=$url>$equipoNombre</a>" ?></p>
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
                                <td><?php echo $avg_fg_pct; ?></td>
                                <td><?php echo $avg_fg3m; ?></td>
                                <td><?php echo $avg_fg3a; ?></td>
                                <td><?php echo $avg_fg3_pct; ?></td>
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
                                <td><?php echo $avg_ft_pct; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>




    <!-- Content End -->


    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

