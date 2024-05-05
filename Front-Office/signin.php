<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        registrar();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
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
    <!-- Navbar Start -->
    <div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-1 m-0">
                <img src="./assets/img/logoNBA.png" id="logo-menu-image" alt="nba" width=20% height=20%><!-- Logo -->
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-tarPOST="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link">Inicio</a>
                    <a href="players.php" class="nav-item nav-link">Jugadores</a>
                    <a href="teams.php" class="nav-item nav-link">Equipos</a>
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
            </div>
        </nav>
    </div>
    
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 position-relative overlay-bottom">
        <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 200px">
            <!-- <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">NBA LOG IN</h1> -->
            <!-- <div class="d-inline-flex mb-lg-5">
                <p class="m-0 text-white"><a class="text-white" href="">Home</a></p>
                <p class="m-0 text-white px-2">/</p>
                <p class="m-0 text-white">Services</p>
            </div> -->
        </div>
    </div>
    <!-- Page Header End -->


    <div class="container-fluid py-5">
        <div class="container">
            <div class="reservation position-relative overlay-top overlay-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6 my-5 my-lg-0">
                        <div class="p-5">
                            <div class="mb-4">
                                <h1 class="display-3 text-primary">UNETE</h1>
                                <h2 class="text-white">¿Listo para entrar a la cancha?</h2>
                            </div>
                            <p class="text-white">¡Dunk into the action! Tu acceso directo al mundo emocionante de la NBA</p>
                            <ul class="list-inline text-white m-0">
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Más entretenimiento</li>
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Más emoción</li>
                                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Más pasión</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="container-fluid py-5">
                            <div class="text-center p-5" style="background: rgba(51, 33, 29, .8);">
                                <h1 class="text-white mb-4 mt-5">Sign In</h1>
                                <?php
                                    if (isset($_SESSION['error_message'])) {
                                        echo "<h6 id='error-form-msg' class='text-uppercase teams-stats-graphs-2' style='letter-spacing: 5px;'>" . $_SESSION['error_message'] . "</h6>";
                                        unset($_SESSION['error_message']);
                                    }
                                ?>
                                <form class="mb-5" method="POST">

                                    <div class="form-group">
                                        <!-- Campo de nombre completo -->
                                        <input type="text" name="full_name" class="form-control border-primary p-4" placeholder="Full Name" required="required" value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>" />
                                    </div> 
                                    <div class="form-group">
                                        <!-- Campo de nombre de usuario -->
                                        <input type="text" name="username" class="form-control border-primary p-4" placeholder="Username" required="required" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" />
                                    </div> 
                                    <div class="form-group">
                                        <!-- Campo de correo electrónico -->
                                        <input type="email" name="email" class="form-control border-primary p-4" placeholder="Email" required="required" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
                                    </div>
                                    <div class="form-group">
                                        <!-- Campo de contraseña -->
                                        <input type="password" name="password" class="form-control border-primary p-4" placeholder="Password" required="required" />
                                    </div>
                                    <div class="form-group">
                                        <!-- Campo de confirmación de contraseña -->
                                        <input type="password" name="confirm_password" class="form-control border-primary p-4" placeholder="Confirm Password" required="required" />
                                    </div>                          
                                    <div>
                                        <!-- Botón de enviar formulario -->
                                        <button class="btn btn-primary btn-block font-weight-bold py-3" type="submit">Sign in</button>
                                    </div>
                                    <div>
                                        <!-- Enlace para iniciar sesión -->
                                        <p class="text-white mb-4 mt-5">Already have an account?</p>
                                        <a href="./login.php">Log In</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Reservation End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Reservation End -->

        <!-- Navbar End -->
        
        <?php include 'footer.php'; ?>
