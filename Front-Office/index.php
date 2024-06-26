<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    checkSessionTimeout();
    $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
    
    if ($usuario_autenticado){
        include 'connection.php';
        getUserInfo();
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
    <link href="assets/img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/css/style.min.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar End -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-1 m-0">
                <img src="assets/img/logoNBA.png" class="transparente" id="logo-menu-image" alt="nba" width=20% height=20%><!-- Logo -->
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link active">Inicio</a>
                    <a href="players.php" class="nav-item nav-link">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link">Equipos</a>
                    <a href="games.php" class="nav-item nav-link">Partidos</a>
                    <div class="nav-item dropdown">
                        <?php if (!$usuario_autenticado): ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" alt="user"></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="login.php" class="dropdown-item">Log in</a>
                                <a href="signin.php" class="dropdown-item">Sign in</a>
                            </div>
                        <?php else: ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="assets/img/user.png" alt="user"></a>
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




    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5">
        <div id="blog-carousel" class="carousel slide overlay-bottom" data-ride="carousel">
            <img class="w-100" src="assets/img/carousel-1.webp" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <h1 class="text-primary font-weight-medium m-0" justify="center">Conoce a tus héroes, sigue a tus equipos <br>y vive la pasión de la NBA</h1>
                <h2 class="text-white m-0"><br>Temporada 2023/24</h2>
            </div>
                
        </div>
    </div>
    <!-- Carousel End -->


    <?php if ($usuario_autenticado):?>
        <!-- Testimonial Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="section-title player-spaces-diff">
                <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;"><?php echo $_SESSION['username'] ?></h4>
                <h1 class="display-4">Tus Favoritos</h1>
            </div>
            <div class="row justify-content-center player-spaces-diff">
                <div class="col-md-6">
                    <div class="testimonial-item text-center">
                        <?php $player_url = "playerInfo.php?id=" . urlencode($_SESSION['fav_player']); ?>
                        <a href="<?php echo $player_url ?>">
                            <img class="img-fluid mb-3" src="<?php echo "assets/img/players/" . $_SESSION['fav_player'] . ".avif" ?>" alt="Imagen del jugador" width=60%>
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="testimonial-item text-center">
                        <?php $team_url = "teamInfo.php?id=" . urlencode($_SESSION['fav_team']); ?>
                        <a href="<?php echo $team_url ?>">
                            <img class="img-fluid mb-3" src="<?php echo "assets/img/teams/" . $_SESSION['fav_team'] . ".svg" ?>" alt="Imagen del equipo" width=43%>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

    <?php endif; ?>
<?php
    include 'footer.php';
?>
