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
    }
    else {
        header("Location: teams.php");
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
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase"><?php echo $full_name." (".$abbreviation.")"?></h1>
            <img src="./assets/img/teams/<?php echo $id; ?>.svg" alt="imagen logo equipo" width=170rem>        
        </div>
    </div>
    <!-- Page Header End -->
    
    <!-- Content Start -->
    <div class="container-fluid py-5 d-flex justify-content-center"> <!-- Añade flexbox para centrar -->
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
    <div class="container">
        <div class="section-title">
            <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Plantilla</h4>
        </div>
        <div class="owl-carousel testimonial-carousel">
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


