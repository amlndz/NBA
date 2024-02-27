<?php
    
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();

    require "connection.php";
    $conn = connect();
    
    $sql = "SELECT p.id, p.first_name, p.last_name, p.number, p.position, p.draft, p.country, t.full_name as team_name 
            FROM final_players p
            INNER JOIN final_teams t ON p.team_id = t.id";
            // Preparar la declaración
            $stmt = $conn->prepare($sql);
    
    $stmt->execute();

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    // Cerrar la declaración
    $stmt->close();

    // Cerrar la conexión
    $conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>NBA</title>
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
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link">Inicio</a>
                    <a href="players.php" class="nav-item nav-link active">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link">Equipos</a>
                    <div class="nav-item dropdown">
                        <?php if (!$usuario_autenticado): ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="user.png" alt=""></a>
                            <div class="dropdown-menu text-capitalize">
                                <a href="login.php" class="dropdown-item">Log in</a>
                                <a href="login.php" class="dropdown-item">Sign in</a>
                            </div>
                        <?php else: ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="user.png" alt=""></a>
                            <div class="dropdown-menu text-capitalize">
                                <a href="logout.php" class="dropdown-item">Cerrar Sesion</a> 
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
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">2023/24 Players</h1>
            <!-- <div class="d-inline-flex mb-lg-5">
                <p class="m-0 text-white"><a class="text-white" href="">Home</a></p>
                <p class="m-0 text-white px-2">/</p>
                <p class="m-0 text-white">Services</p>
            </div> -->
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Players Start -->
    <div class="container-fluid pt-5">
        <div class="container">
            
        </div>
    </div>

    
    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->
