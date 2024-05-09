<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    checkSessionTimeout();
    if(!$usuario_autenticado){
        $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
        header("Location: login.php");
        exit;
    }

    include 'connection.php';
    getUserInfo();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        actualizarUsuario();
    }

    if ($_SESSION['fav_player'] != null){
        $conn = connect();
        $stmt = $conn->prepare("SELECT * FROM final_players WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['fav_player']);
        $stmt->execute();
        $result = $stmt->get_result();
        $player = $result->fetch_assoc();
        $stmt->close();
        $conn->close();    

    }
    if ($_SESSION['fav_team'] != null){
        $conn = connect();
        $stmt = $conn->prepare("SELECT * FROM final_teams WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['fav_team']);
        $stmt->execute();
        $result = $stmt->get_result();
        $team = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
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
            <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white"><?php echo $_SESSION['username']?></h1>
        </div>
    </div>
    <!-- Page Header End -->
    <?php if ($_SESSION['fav_player'] != null){?>
    <div class="container-fluid py-8 d-flex justify-content-center player-spaces-diff-2"> <!-- Añade flexbox para centrar -->
        <div class="testimonial-item">
            <? $url = "playerInfo.php?id=".urlencode($_SESSION['fav_player']); ?>
            <a href="<?php echo $url ?>">
                <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/players/".$_SESSION['fav_player'].".avif' alt='imagen jugador'";?> onerror="this.onerror=null;this.src='./assets/img/players/default.avif'" width=30%>
            </a>
            <div class="player-info">
                <a href="<?php echo $url ?>">
                    <h4><?php echo $player['first_name'] . ' ' . $player['last_name']; ?></h4>
                </a>
            </div>
        </div>           
    </div>
    <?php } if ($_SESSION['fav_team'] != null){?>
    <div class="container-fluid py-8 d-flex justify-content-center player-spaces-diff-2 " > <!-- Añade flexbox para centrar -->
        <div class="testimonial-item">

            <? $url_team = "teamInfo.php?id=".urlencode($_SESSION['fav_team']); ?>
            <a href="<?php echo $url_team ?>">
                <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/teams/".$_SESSION['fav_team'].".svg' alt='imagen equipo'";?> onerror="this.onerror=null;this.src='./assets/img/nba.avif'" width=90%>
            </a>
            <div class="player-info">
                <a href="<?php echo $url ?>">
                    <h4><?php echo $team['full_name'] . ' ' . $team['abbreviation']; ?></h4>
                </a>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="container-fluid py-8 d-flex justify-content-center "> <!-- Añade flexbox para centrar -->
            <h1 class="text-primary" style="letter-spacing: 5px;">¿Desea actualizar los datos del usuario?</h1>
    </div>
    <div class="container player-spaces-diff-2 col-lg-6">                        
        <div class="text-center p-5" style="background: rgba(51, 33, 29, .8);">
            <?php
            if (isset($_SESSION['error_message'])) {
                    echo "<h6 id='error-form-msg' class='text-uppercase teams-stats-graphs-2' style='letter-spacing: 5px;'>" . $_SESSION['error_message'] . "</h6>";
                    unset($_SESSION['error_message']);
            }?>
            <form class="mb-5" method="POST">
                <div class="form-group">
                    <input type="text" name="full_name" class="form-control border-primary p-4" placeholder="Full Name" required="required" value="<?php echo $_SESSION['full_name'] ?>" />
                </div> 
                <div class="form-group">
                    <input type="text" name="username" class="form-control border-primary p-4" placeholder="Username" required="required" value="<?php echo $_SESSION['username']?>" />
                </div> 
                
                <div class="form-group">
                    <input type="email" name="email" class="form-control border-primary p-4" placeholder="Email" required="required" value="<?php echo $_SESSION['email'] ?>" />
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control border-primary p-4" placeholder="Password" required="required" />
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" class="form-control border-primary p-4" placeholder="Confirm Password" required="required" />
                </div>                          
                <div>
                    <button class="btn btn-primary btn-block font-weight-bold py-3" type="submit">Actualizar Cuenta</button>
                </div>
            </form>
        </div>
    </div>

<!-- Footer Start -->
<?php include 'footer.php'; ?>
<!-- Footer End -->
