<?php
    require "autenticarUsuario.php";
    checkSessionTimeout();
    $usuario_autenticado = autenticar();
    if(!$usuario_autenticado){
        $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php");
        exit;
    }
    
    include "connection.php";
    getUserInfo();
    $conn = connect();
    // Verificar si se ha enviado información del jugador a través de GET
    include "team.php";
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
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                <a href="index.php" class="nav-item nav-link">Inicio</a>
                    <a href="players.php" class="nav-item nav-link">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link active">Equipos</a>
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
                                <a href="user.php" class="dropdown-item"><?php echo $_SESSION['username'] ?></a>
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
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
            <img src="./assets/img/teams/<?php echo $id; ?>.svg" alt="imagen logo equipo" width=220rem>        
        </div>
    </div>
    <!-- Page Header End -->
    
    <!-- Content Start -->

    <!-- Team Content -->
    <div class="container teams-stats-graphs">
        <div class="container-fluid py-5 d-flex justify-content-center "> <!-- Añade flexbox para centrar -->
            <h1 class="text-primary text-uppercase" style="letter-spacing: 5px;"><?php echo $full_name." (".$abbreviation.")"?></h1>
            <button type="submit" class="fav-btn fav-btn-team btn btn-primary text-white" data-id="<?php echo $id ?>" data-tipo="equipo">
                <?php if ($_SESSION['fav_team'] != $id) { ?>
                    <img src="./assets/img/nonfav.avif" alt="icono corazon">
                <?php } else { ?>
                    <img src="./assets/img/fav.avif" alt="icono corazon">
                <?php } ?>
            </button>
        </div>
        <div class="container-fluid d-flex justify-content-center "> <!-- Añade flexbox para centrar -->    
            <table class="styled-table">
                <tr>
                    <th>Nombre completo</th>
                    <th>Abreviatura</th>
                    <th>Ciudad</th>
                    <th>Conferencia</th>
                    <th>División</th>
                </tr>
                <tr>
                    <td><?php echo $full_name; ?></td>
                    <td><?php echo $abbreviation; ?></td>
                    <td><?php echo $city; ?></td>
                    <td><?php echo $conference; ?></td>
                    <td><?php echo $division; ?></td>
                </tr>
            </table>
        </div>
        <div class="container-fluid py-5 d-flex justify-content-center text-center teams-stats-graphs-2 team-history"> <!-- Añade flexbox para centrar -->
            <?php echo $team_history; ?>
        </div>
        
        <div class="container teams-stats-graphs-2">
            <div class="container-fluid py-8 d-flex justify-content-center"> <!-- Añade flexbox para centrar -->
                <img class="team-uniform" src=<?php echo "./assets/img/uniform/".$id.".avif" ?> alt="" width="300px" height="300px">
                <img src=<?php echo "./assets/img/court/".$id.".avif" ?> alt="" width="600px" height="300px">
            </div>
        </div>       
    </div>
    
    <!-- Graficas con estadisticas de los equipos -->
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Estadísticas</h4>
        </div>
        <div class="container-fluid py-8 d-flex justify-content-center teams-stats-graphs-2 "> <!-- Añade flexbox para centrar -->
            <div class="graphs-teams" id="barchart"></div>
            <div class="graphs-teams" id="linechart"></div>
            <div class="graphs-teams" id="piechart"></div>
        </div>
    </div>


    <script type="text/javascript">
        // Cargar la biblioteca de visualización de Google
        google.charts.load('current', {'packages':['corechart']});

        // Dibujar los gráficos cuando la biblioteca esté cargada
        google.charts.setOnLoadCallback(drawPieChart);
        google.charts.setOnLoadCallback(drawColumnChart);
        google.charts.setOnLoadCallback(drawLineChart);

        // Función para dibujar el gráfico de tarta
        function drawPieChart() {
            var data = google.visualization.arrayToDataTable([
            ['Resultado', 'Número de partidos'],
            ['Victorias', <?php echo $victorias; ?>],
            ['Derrotas', <?php echo $derrotas; ?>]
            ]);

            var options = {
                'title':'Victorias/Derrotas', 
                'width':450, 
                'height':400, 
                'backgroundColor': 'transparent', 
                'colors': ['#DA9F5B', '#33211D'],
                'pieSliceText': 'none' // No muestra texto en las rebanadas
                
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }

        // Función para dibujar el gráfico de columnas
        function drawColumnChart() {
            var data = google.visualization.arrayToDataTable([
            ['Resultado', 'Local', 'Visitante'],
            ['Victorias', <?php echo $victorias_local; ?>, <?php echo $victorias_visitante; ?>],
            ['Derrotas', <?php echo $derrotas_local; ?>, <?php echo $derrotas_visitante; ?>]
            ]);

            var options = {'title':'Relacion Victoria/Derrota Local/Visitante', 'width':450, 'height':400, 'backgroundColor': 'transparent', 'colors': ['#DA9F5B', '#33211D']};

            var chart = new google.visualization.ColumnChart(document.getElementById('barchart'));
            chart.draw(data, options);
        }

        // Función para dibujar el gráfico lineal
        function drawLineChart() {
            var data = google.visualization.arrayToDataTable([
            ['Fecha', 'Puntuación'],
            <?php foreach ($evolucion as $fecha => $puntuacion) {
                echo "['".date('Y/m/d', strtotime($fecha))."', $puntuacion],";
            } ?>
            ]);

            var options = {'title':'Puntos/Partido', 'width':450, 'height':400, 'backgroundColor': 'transparent', 'colors': ['#DA9F5B']};

            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
            chart.draw(data, options);
        }
    </script>

    <!-- Plantilla -->
    <div class="container teams-stats-graphs teams-stats-graphs">
        <div class="container-fluid py-8 d-flex justify-content-center teams-stats-graphs-2"> <!-- Añade flexbox para centrar -->
            <h1 class="text-primary text-uppercase" style="letter-spacing: 5px;">Plantilla</h1>
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


