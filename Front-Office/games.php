<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "autenticarUsuario.php"; 

    $usuario_autenticado = autenticar();
checkSessionTimeout();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
include "connection.php";
$conn = connect();
// Consulta SQL
$query = "SELECT * FROM final_games";
$result = mysqli_query($conn, $query);

// Iterar sobre los resultados

// Función para obtener los detalles del equipo
function getTeamDetails($team_id) {
    global $conn;
    $query = "SELECT * FROM final_teams WHERE id = $team_id";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
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
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Start -->
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
    <!-- Navbar End -->

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">PARTIDOS 2023/24</h1>
        </div>
    </div>

    <div id="players-container" class="container-fluid pt-5">
        <div class="container">
            <?php
                // Conexión a la base de datos
                include("playersInfoBBDD.php");

                // Consulta SQL
                $query = "SELECT * FROM final_games";
                $result = mysqli_query($conn, $query);

                // Iterar sobre los resultados
                while($row = mysqli_fetch_assoc($result)) {
                    // Obtener detalles del equipo local y visitante
                    $home_team = getTeamDetails($row['home_team_id']);
                    $visitor_team = getTeamDetails($row['visitor_team_id']);
            
                    // Mostrar los datos
                    echo '<div class="game">';
                    echo '<div class="team">';
                    echo '<img src="./assets/img/teams/' . $home_team['id'] . '.svg" alt="team-logo">';
                    echo '<p>' . $home_team['name'] . '</p>';
                    echo '<p>' . $row['home_team_score'] . '</p>';
                    echo '</div>';
                    echo '<div class="details">';
                    echo '<p>' . $row['date'] . '</p>';
                    echo '<p>' . $row['period'] . '</p>';
                    echo '<p>' . $row['status'] . '</p>';
                    echo '</div>';
                    echo '<div class="team">';
                    echo '<p>' . $row['visitor_team_score'] . '</p>';
                    echo '<p>' . $visitor_team['name'] . '</p>';
                    echo '<img src="./assets/img/teams/' .$visitor_team['id'] . '.svg" alt="team-logo">';
                    echo '</div>';
                    echo '</div>';
                }
            ?>
        </div>
    </div>

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->

</body>
</html>
