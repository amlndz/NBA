<?php
    include("playersInfoBBDD.php");
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
                            <a href="#" class="nav-link dropdown-toggle user-img" data-toggle="dropdown"><img src="./assets/img/user.png" alt=""></a>
                            <div class="dropdown-menu text-capitalize">
                                <a href="login.php" class="dropdown-item">Log in</a>
                                <a href="login.php" class="dropdown-item">Sign in</a>
                            </div>
                        <?php else: ?>
                            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><img src="./assets/img/user.png" alt=""></a>
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




<!-- Page Header Start     <div class="d-inline-flex mb-lg-5">
 -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">2023/24 Players</h1>
        <!-- Menú desplegable para búsqueda -->
        <div class="d-inline-flex mb-lg-5">
            <div class="row mb-4">
                <div class="col">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Búsqueda Avanzada
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <form class="px-4 py-3" method="GET" action="players.php">
                                <h5 class="dropdown-header">Filtros de Búsqueda</h5>
                                <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Nombre" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                <input type="text" class="form-control" name="team" placeholder="Equipo" value="<?php echo isset($_GET['team']) ? $_GET['team'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                <input type="text" class="form-control" name="position" placeholder="Posición" value="<?php echo isset($_GET['position']) ? $_GET['position'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                <input type="text" class="form-control" name="draft" placeholder="Draft" value="<?php echo isset($_GET['draft']) ? $_GET['draft'] : ''; ?>">
                                </div>
                                <div class="form-group">
                                <input type="text" class="form-control" name="country" placeholder="País" value="<?php echo isset($_GET['country']) ? $_GET['country'] : ''; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                                <a href="players.php" class="btn btn-primary">Borrar</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
    <!-- Players Start -->
     <!-- Jugadores -->
     <div class="container-fluid pt-5">
        <div class="container">
            <div class="row">
                <?php
                // Iterar sobre los resultados
                while ($row = $result->fetch_assoc()) {
                    $playerInfoUrl = $row['first_name'] . " " . $row['last_name'];
                    $playerId = $row['id'];
                    ?>
                    <div class="col-lg-4 mb-4">
                        <div class="row align-items-center">
                            <div class="col-sm-5">
                                <img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/players/".$playerId.".avif' alt='img'";?> onerror="this.onerror=null;this.src='./assets/img/players/default.png'">    
                            </div>
                            <div class="col-sm-7">
                                <?php $url = "playerInfo.php?playerInfo=".urlencode($playerInfoUrl); ?>
                                <h4><?php echo" <a hrefa class='player-name' href=$url > ".$row['first_name']." ".$row['last_name']."</a>";?></h4>
                                <p class="m-0">
                                    <?php
                                    echo "Dorsal: ".$row['number']."<br/>Team: ". $row['team_name']."<br/>Position: ".$row['position']."<br/>Draft: ".($row['draft'] ? $row['draft'] : "N/A")."<br/>Country: ".$row['country'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    
    <div class="pagination-container">
    <div class="pagination">
        <?php
        // Calcular el número total de páginas
        $totalPages = ceil($totalRecords / $pageSize);

        // Mostrar controles de navegación
        if ($page > 1) {
            echo "<a href='?page=".($page - 1).buildFilterQueryString()."'>&laquo; Anterior</a>";
        }
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='?page=".$i.buildFilterQueryString()."'".($page == $i ? " class='active'" : "").">$i</a>";
        }
        if ($page < $totalPages) {
            echo "<a href='?page=".($page + 1).buildFilterQueryString()."'>Siguiente &raquo;</a>";
        }
        ?>
    </div>
</div>


    
    <!-- Footer Start -->
    <?php include 'footer.php'; ?>
    <!-- Footer End -->
