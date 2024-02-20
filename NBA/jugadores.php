<?php
    session_start();

    // Verificar si el usuario está autenticado
    $usuario_autenticado = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
    
    $servername = "http://webalumnos.tlm.unavarra.es:10800";
    $username = "grupo25";
    $password = "fex1roMi4j";
    $database = "db_grupo25";

    // Crear conexión
    $conn = new mysqli("dbserver", $username, $password, $database);

    // Verificar conexión
    if ($conn->connect_error) {
        die("La conexión falló: " . $conn->connect_error);
    }

    // Verificar si se ha enviado información del jugador a través de GET
    if (isset($_GET['playerInfo'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $playerInfo = $_GET['playerInfo'];

        // Preparar la consulta SQL
        $sql = "SELECT p.first_name, p.last_name, p.number, t.full_name as team_name 
                FROM PLAYERS p
                INNER JOIN TEAMS t ON p.team_id = t.id
                WHERE LOWER(p.first_name) LIKE ? OR LOWER(p.last_name) LIKE ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros y ejecutar la consulta
        $playerInfo = '%' . $playerInfo . '%';
        $stmt->bind_param("ss", $playerInfo, $playerInfo);
    }
    else{
        $sql = "SELECT p.id, p.first_name, p.last_name, p.number, t.full_name as team_name 
                FROM PLAYERS p
                INNER JOIN TEAMS t ON p.team_id = t.id";
                // Preparar la declaración
                $stmt = $conn->prepare($sql);
    }
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
    <link href="css/style.min.css" rel="stylesheet">
</head>

<body>
<!-- Navbar Start -->
<div class="container-fluid p-0 nav-bar">
        <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
            <a href="index.php" class="navbar-brand px-lg-1 m-0">
                <img src="./logoNBA.png" id="logo-menu-image" alt="nba" width=20% height=20%><!-- Logo -->
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto p-4">
                <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="jugadores.php" class="nav-item nav-link active">Players</a>
                    <a href="equipos.php" class="nav-item nav-link">Teams</a>
                    <!-- <a href="contact.php" class="nav-item nav-link">Contact</a>
                    <a href="about.php" class="nav-item nav-link">About</a> -->
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
                                <a href="logout.php" class="dropdown-item">cerrar</a> 
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
            <?php
            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
                // Iterar sobre los resultados en incrementos de dos
                for ($i = 0; $i < $result->num_rows; $i += 2) {
                    // Obtener el primer jugador
                    $row = $result->fetch_assoc();
                    $playerInfoUrl1 = $row['first_name'] . " " . $row['last_name'];
                    $playerId1 = $row['id'];
                    // Construir el enlace con nombre y apellido como parámetros GET
                    ?>
            <div class="row">
                <div class="col-lg-6 mb-5">
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./img/".$playerId1.".avif' alt='img'";?> onerror="this.onerror=null;this.src='./img/default.png'">
                        </div>
                        <div class="col-sm-7">
                            <?php $url = "playerInfo.php?playerInfo=".urlencode($playerInfoUrl1); ?>
                            <h4><i class="fa fa-truck service-icon"></i><?php echo" <a hrefa class='player-name' href=$url > ".$row['first_name']." ".$row['last_name']."</a>";?></h4>
                            <p class="m-0">
                                <?php
                                echo "<a class='player-name' href='./playerInfo.php'>" . $row['first_name'] . " " . $row['last_name'] . "</a><br/>Dorsal: " . $row['number'] . "<br/>Team: " . $row['team_name'];?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                // Obtener el segundo jugador si existe
                if ($row = $result->fetch_assoc()) {
                    $playerInfoUrl2 = $row['first_name'] . " " . $row['last_name'];
                    $playerId2 = $row['id'];
                    ?>
                <div class="col-lg-6 mb-5">
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                        <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./img/".$playerId2.".avif' alt='img'";?> onerror="this.onerror=null;this.src='./img/default.png'">
                        </div>
                        <div class="col-sm-7">
                        <?php $url = "playerInfo.php?playerInfo=".urlencode($playerInfoUrl2); ?>
                            <h4><i class="fa fa-truck service-icon"></i><?php echo" <a hrefa class='player-name' href=$url > ".$row['first_name']." ".$row['last_name']."</a>";?></h4>
                            <p class="m-0">
                            <?php echo "<a class='player-name' href='./playerInfo.php'>" . $row['first_name'] . " " . $row['last_name'] . "</a><br/>Dorsal: " . $row['number'] . "<br/>Team: " . $row['team_name'];?>
                        </div>
                    </div>
                </div>
            </div>
                <?php
                    }
                }
            } else {
                echo "Error";
            }
            ?>         
                <!-- <div class="col-lg-6 mb-5">
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                            <img class="img-fluid mb-3 mb-sm-0" src="img/service-3.jpg" alt="">
                        </div>
                        <div class="col-sm-7">
                            <h4><i class="fa fa-award service-icon"></i>Best Quality Coffee</h4>
                            <p class="m-0">Sit lorem ipsum et diam elitr est dolor sed duo. Guberg sea et et lorem dolor sed est sit
                                invidunt, dolore tempor diam ipsum takima erat tempor</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-5">
                    <div class="row align-items-center">
                        <div class="col-sm-5">
                            <img class="img-fluid mb-3 mb-sm-0" src="img/service-4.jpg" alt="">
                        </div>
                        <div class="col-sm-7">
                            <h4><i class="fa fa-table service-icon"></i>Online Table Booking</h4>
                            <p class="m-0">Sit lorem ipsum et diam elitr est dolor sed duo. Guberg sea et et lorem dolor sed est sit
                                invidunt, dolore tempor diam ipsum takima erat tempor</p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!-- Service End -->

    
    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->
