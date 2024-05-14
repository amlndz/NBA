<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("gamesInfoBBDD.php");
$usuario_autenticado = autenticar();
checkSessionTimeout();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];

$partidos_por_jornada = 15;
$jornada_actual = isset($_GET['jornada']) ? $_GET['jornada'] : 1;
$inicio = ($jornada_actual - 1) * $partidos_por_jornada;

$query = "SELECT * FROM final_games LIMIT $inicio, $partidos_por_jornada";
$result = mysqli_query($conn, $query);

$query_total = "SELECT COUNT(*) as total FROM final_games";
$result_total = mysqli_query($conn, $query_total);
$row_total = mysqli_fetch_assoc($result_total);
$total_partidos = $row_total['total'];
$total_jornadas = ceil($total_partidos / $partidos_por_jornada);
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>NBA</title>
    <link rel="icon" href="./assets/img/nba.avif">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Plantilla de sitio web gratuita" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Hoja de estilos personalizada -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Hoja de estilos de Bootstrap personalizada -->
    <link href="assets/css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- Barra de navegación -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-1 m-0">
                <img src="assets/img/logoNBA.png" id="logo-menu-image" alt="nba" width=20% height=20%><!-- Logo -->
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link">Inicio</a>
                    <a href="players.php" class="nav-item nav-link">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link">Equipos</a>
                    <a href="games.php" class="nav-item nav-link active">Partidos</a>
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
                                <?php if ($_SESSION['administrador'] == 1){ ?>
                                    <a href="../Back-Office/perfil.php" class="dropdown-item">admin</a>
                                <?php } ?>
                                <a href="logout.php" class="dropdown-item">Log Out</a> 
                            </div>                            
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Fin de la barra de navegación -->

    <!-- Encabezado de la página -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
            <h1 class="display-4 mb-5 mt-2 mt-lg-5 text-white text-uppercase">PARTIDOS 2023/24</h1>
            <?php if ($usuario_autenticado) {?>
                <form method="post" action="gamesPDF.php" class=" mb-lg-5">
                    <button type="submit" name="generate_pdf" class="pdf-button fav-btn btn btn-primary text-white">
                        <img src="./assets/img/pdf.avif" alt="icono pdf"> Descargar PDF
                    </button>
                </form>
                <?php } ?>
        </div>
    </div>

    <!-- Estilos personalizados -->
    <style>
        .game {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem;
            padding: 1rem;
            border: 2px solid #33211D;
            border-radius: 10px;
        }

        .team {
            flex: 1;
            text-align: center;
        }

        .team img {
            max-width: 60px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .details {
            flex: 1;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <div class="container-fluid p-0 nav-bar">
        <!-- Código de la barra de navegación -->
    </div>
    <!-- Fin de la barra de navegación -->

    <!-- Encabezado de la página -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <!-- Código del encabezado de la página -->
    </div>

    <div id="players-container" class="container-fluid pt-5">
        <div class="container">
            <?php
                // Iterar sobre los resultados
                while($row = mysqli_fetch_assoc($result)) {
                    // Obtener detalles del equipo local y visitante
                    $home_team = getTeamDetails((int)$row['home_team_id']);
                    $visitor_team = getTeamDetails((int)$row['visitor_team_id']);
            
                    // Mostrar los datos
                    echo '<div class="game">';
                    echo '<div class="team">';
                    echo '<img src="./assets/img/teams/' . $home_team['id'] . '.svg" alt="team-logo">';
                    echo '<p>' . $home_team['name'] . '</p>';
                    echo '<p>' . $row['home_team_score'] . '</p>';
                    echo '</div>';
                    echo '<div class="details">';
                    // Convertir fecha de formato completo a solo fecha
                    $date = date_create($row['date']);
                    echo '<p>' . date_format($date, 'Y-m-d') . '</p>';
                    // Cambiar estado según el valor
                    if ($row['status'] == 'Final') {
                        echo '<p>Finalizado</p>';
                    } else {
                        echo '<p>' . $row['status'] . '</p>';
                    }
                    // Cambiar periodo
                    if ($row['period'] == 'final') {
                        echo '<p>Cuartos</p>';
                    } else {
                        echo '<p>' . $row['period'] . '</p>';
                    }
                    echo '</div>';
                    echo '<div class="team">';
                    echo '<img src="./assets/img/teams/' .$visitor_team['id'] . '.svg" alt="team-logo">';
                    echo '<p>' . $visitor_team['name'] . '</p>';
                    echo '<p>' . $row['visitor_team_score'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                $result->data_seek(0);
                $conn->close();
            ?>
        </div>
    </div>

    <div class="pagination-container" id="pagination-container">
        <div class="pagination">
            <?php
            for ($i = 1; $i <= $total_jornadas; $i++) {
                if ($i == $jornada_actual) {
                    echo "<a class='active' href=\"?jornada=$i\">$i</a> ";
                } else {
                    if ($i == 1 || $i == $total_jornadas || ($i >= $jornada_actual - 3 && $i <= $jornada_actual + 3)) {
                        echo "<a href=\"?jornada=$i\">$i</a> ";
                    } else if ($i < $jornada_actual) {
                        if ($i > 1) {
                            echo "<a class='disabled'>...</a> ";
                        }
                        $i = $jornada_actual - 4; // Saltar a las jornadas cercanas a la actual
                    } else if ($i > $jornada_actual) {
                        if ($i < $total_jornadas) {
                            echo "<a class='disabled'>...</a> ";
                        }
                        $i = $total_jornadas - 1; // Saltar a la última jornada
                    }
                }
            }
            ?>
        </div>
    </div>
    <!-- Pie de página -->
    <?php include 'footer.php'; ?>
    <!-- Fin del pie de página -->


</body>
</html>

