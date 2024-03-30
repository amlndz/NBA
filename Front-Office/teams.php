<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    
    include "connection.php";
    $conn = connect();

    
    $conference = isset($_GET['conference']) ? $_GET['conference'] : null;

    $sql = "SELECT * FROM final_teams";
    if ($conference) {
        $sql .= " WHERE conference = ?";
    }

    $stmt = $conn->prepare($sql);

    if ($conference) {
        $stmt->bind_param("s", $conference);
    }

    $stmt->execute();

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    // Cerrar la declaración
    $stmt->close();

    $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];

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
                <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="players.php" class="nav-item nav-link">Players</a>
                    <a href="teams.php" class="nav-item nav-link active">Teams</a>
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
            <h1 class="display-4 mb-5 mt-0 mt-lg-5 text-white text-uppercase">EQUIPOS NBA</h1>
            <div class="d-inline-flex mb-lg-5">
                <div class="row mb-5">
                    <div class="col">
                        <button class="btn <?php echo ($_GET['conference'] == 'west') ? 'btn-primary active' : 'btn-primary' ?>" onclick="window.location.href='<?php echo ($_GET['conference'] == 'west') ? 'teams.php' : '?conference=west' ?>'">West</button>
                    </div>
                    <div class="col">
                        <button class="btn <?php echo ($_GET['conference'] == 'east') ? 'btn-primary active' : 'btn-primary' ?>" onclick="window.location.href='<?php echo ($_GET['conference'] == 'east') ? 'teams.php' : '?conference=east' ?>'">East</button>
                    </div>
                </div>
            </div>  
        </div>  
    </div>
    <!-- Page Header End -->


    <!-- Menu Start -->
    <div class="container-fluid pt-5">
        <div class="container">
            <?php
            // Verificar si se encontraron resultados
            if ($result->num_rows > 0) {
            ?>  
            <div class="row">
                <?php
                // Iterar sobre los resultados en incrementos de dos
                for ($i = 0; $i < $result->num_rows; $i += 1) {
                    // Obtener el primer jugador
                    $row = $result->fetch_assoc();
                    $teamInfoUrl = $row['abbreviation'];
                    $teamId = $row['id'];
                    $url = "teamInfo.php?id=".urlencode($teamId); 
                    // Construir el enlace con nombre y apellido como parámetros GET
                    ?>
                    
                    <div class="col-lg-4 mb-5">
                        <div class="row align-items-center">
                            <div class="col-sm-5">
                            <a <?php echo "href=$url"?>><img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/teams/".$teamId.".svg' alt='img'";?> onerror="this.onerror=null;this.src='./assets/img/logoNBA.png'"></a>
                            </div>
                            <div class="col-sm-7">
                                <h4><?php echo" <a hrefa class='team-name' href=$url > ".$row['full_name']." (".$row['abbreviation'].")</a>";?></h4>
                                <!-- <i class="fa service-icon"></i> -->
                                <p class="m-0">
                                    <?php
                                    echo "abbreviation: ".$row['abbreviation']."<br/>city: ". $row['city']."<br/>Conference: ".$row['conference']."<br/>Division: ".$row['division'];?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php }
                }?>
            </div>
        </div>
    </div>
    

    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->
