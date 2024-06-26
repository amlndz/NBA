<?php
    include("playersInfoBBDD.php");
    $usuario_autenticado = autenticar();
    checkSessionTimeout();
    $_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
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
                    <a href="players.php" class="nav-item nav-link active">Jugadores</a>
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




<!-- Page Header Start     <div class="d-inline-flex mb-lg-5">
 -->
<div class="container-fluid page-header mb-5 position-relative overlay-bottom">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
        <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">JUGADORES 2023/24</h1>
        <form id="search-players-form" class="px-4 py-3" method="GET" action="players.php">
            <div class="form-group d-flex">
                <input type="text" class="form-control-2 mr-2" name="name" placeholder="Buscar jugador" value="<?php echo isset($_GET['name']) ? $_GET['name'] : ''; ?>">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
        <!-- Menú desplegable para búsqueda -->
        <div class="d-inline-flex">
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
        <?php if ($usuario_autenticado) {?>
                <form method="post" action="playerPDF.php" class=" mb-lg-5">
                    <button type="submit" name="generate_pdf" class="pdf-button fav-btn btn btn-primary text-white">
                        <img src="./assets/img/pdf.avif" alt="icono pdf"> Descargar PDF
                    </button>
                </form>
            <?php } ?>
    </div>
</div>

    
    <!-- Players Start -->
     <!-- Jugadores -->
        <div id="players-container" class="container-fluid pt-5">
            <div class="container">
                <div class="row">
                    <?php
                    // Iterar sobre los resultados
                    while ($row = $result->fetch_assoc()) {
                        $playerId = $row['id'];
                        $url = "playerInfo.php?id=".urlencode($playerId); ?>
                        <div class="col-lg-4 mb-4">
                            <div class="row align-items-center">
                                <div class="col-sm-5">
                                    <a <?php echo "href=$url"?>><img class="img-fluid mb-3 mb-sm-0" <?php echo "src='./assets/img/players/".$playerId.".avif' alt='img'";?> onerror="this.onerror=null;this.src='./assets/img/players/default.avif'"></a>
                                    </div>
                                <div class="col-sm-7">
                                    <h4><?php echo" <a hrefa class='player-name' href=$url > ".$row['first_name']." ".$row['last_name']."</a>";?></h4>
                                    <p class="m-0">
                                        <?php
                                        echo "Dorsal: ".$row['number']."<br/>Equipo: ". $row['team_name']."<br/>Posición: ".$row['position']."<br/>Draft: ".($row['draft'] ? $row['draft'] : "N/A")."<br/>Nacionalidad: ".$row['country'];
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="pagination-container" id="pagination-container">
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
