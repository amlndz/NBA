<?php
    session_start();

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar las credenciales
        if ($_POST["usuario"] === "user" && $_POST["contrasena"] === "1234") {
            // Si las credenciales son válidas, establecer la variable de sesión
            $_SESSION['usuario_autenticado'] = true;

            // Redirigir al usuario a otra página después del inicio de sesión
            header("Location: index.php");
            exit; // Asegúrate de que el script se detenga después de la redirección
        } else {
            // Si las credenciales no son válidas, mostrar un mensaje de error
            $error = "Usuario o contraseña incorrectos";
        }
    }
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
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                        <a href="players.php" class="nav-item nav-link">Players</a>
                        <a href="teams.php" class="nav-item nav-link">Teams</a>
                        <!-- <a href="contact.php" class="nav-item nav-link">Contact</a>
                        <a href="about.php" class="nav-item nav-link">About</a> -->
                        <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="./assets/img/user.png" alt=""></a>
                                
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
                        <div class="text-center p-5" style="background: rgba(51, 33, 29, .8);">
                            <h1 class="text-white mb-4 mt-5">Log In</h1>
                            <form class="mb-5">
                                <div class="form-group">
                                    <input type="text" class="form-control border-primary p-4" placeholder="Full-Name"
                                        required="required" />
                                </div> 
                                <div class="form-group">
                                    <input type="text" class="form-control border-primary p-4" placeholder="Username"
                                        required="required" />
                                </div> 
                                <div class="form-group">
                                    <input type="email" class="form-control border-primary p-4" placeholder="Email"
                                        required="required" />
                                </div>
                                <div class="form-group">
                                    <div class="date" id="date" data-target-input="nearest">
                                        <input type="text" class="form-control border-primary p-4 datetimepicker-input" placeholder="Birthday -  mm/dd/yyyy" data-target="#date" data-toggle="datetimepicker"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control border-primary p-4" placeholder="Password"
                                        required="required" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control border-primary p-4" placeholder="Confirm the Password"
                                        required="required" />
                                </div>                          
                                <div>
                                    <button class="btn btn-primary btn-block font-weight-bold py-3" type="submit">Sign in</button>
                                </div>
                                <div>
                                    <p class="text-white mb-4 mt-5">¿Ya tienes una cuenta?</p>
                                    <a href="./login.php">Iniciar Sesión</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Reservation End -->

        <!-- Navbar End -->
        
        <?php include 'footer.php'; ?>
