<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Panel de administracion - resumen de las tablas
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css"
    href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/resumenTablas.css" />
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/titulo.css" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="perfil.php">
        <img src="assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold text-white">Administrador</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white " href="perfil.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">Tu perfil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-primary" href="resumenTablas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Resumen de las tablas</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="listadoUsuarios.php?page=1">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">format_list_numbered</i>
            </div>
            <span class="nav-link-text ms-1">Listado de usuarios</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="recargarTablas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">file_download</i>
            </div>
            <span class="nav-link-text ms-1">Recargar tablas de la API</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="truncarTablas.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">priority_high</i>
            </div>
            <span class="nav-link-text ms-1">Truncar tablas</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <a class="btn bg-gradient-primary w-100" href="cerrar_sesion.php" type="button">
          <i class="material-icons opacity-10">logout</i> Cerrar sesion
        </a>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="true">
      <div class="d-flex justify-content-end align-items-center w-100" id="navbar">
        <a href="perfil.php">
          <h2 class="mb-0 me-3">
            <?php
            include_once './assets/funcionalidad/obtener_nombre_usuario.php';
            echo obtenerNombreUsuario();
            ?>
            <i class="fa fa-user me-sm-1"></i>
          </h2>
        </a>
      </div>
    </nav>

    <div class="contenedor-titulo">
      <div class="titulo">-=-=-=- Resumen de las tablas -=-=-=-</div>
    </div>

    <div id="panel_central_resumen">
      <div id="resumen_contenido_pagina">
        <p>
          Bienvenido a esta página de resumen de tablas de la base de datos. Aquí encontrarás un breve vistazo a cada
          tabla, mostrando las primeras tres filas de cada una para ofrecerte una visión general de su contenido.
          Además, se proporcionará la cantidad total de filas que contiene cada tabla, permitiéndote tener una idea
          clara de la extensión y el tamaño de cada conjunto de datos.
        </p>
        <p>
          Esta página es una herramienta útil para obtener una visión rápida y concisa de la estructura y el contenido
          de las tablas de la base de datos, facilitando así la comprensión y el análisis de la información almacenada
          en cada una de ellas.
        </p>


      </div>

      <div id="todas_tablas">

        <?php
        include "./assets/funcionalidad/connection.php";
        $con = connect();


        // Lista de nombres de tablas y sus estructuras
        $tablas = [
          'final_users' => ['id', 'full_name', 'username', 'email', 'password', 'fav_player', 'fav_team', 'administrador'],
          'final_teams' => ['id', 'abbreviation', 'city', 'conference', 'full_name', 'name'],
          'final_players' => ['id', 'first_name', 'last_name', 'position', 'height', 'weight', 'team_id', 'number', 'draft', 'draft_round', 'country', 'draft_number'],
          'final_games' => ['id', 'date', 'season', 'period', 'time', 'postseason', 'home_team_score', 'visitior_team_score', 'home_team_id', 'visitor_team_id'],
          'final_stats' => ['id', 'player_id', 'team_id', 'game_id', 'min', 'fgm', 'fga', 'fg_pct', 'fg3m', 'fg3a', 'fg3_pct', 'ft_pct', 'ftm', 'fta', 'oreb', 'dreb', 'reb', 'ast', 'stl', 'blk', 'pf', 'pts'],
          'final_averages' => ['player_id', 'season', 'pts', 'ast', 'turnover', 'pf', 'fga', 'fgm', 'fta', 'ftm', 'fg3a', 'fg3m', 'reb', 'oreb', 'dreb', 'stl', 'blk', 'fg_pct', 'fg3_pct', 'ft_pct', 'min', 'games_played'],
        ];

        foreach ($tablas as $nombreTabla => $columnas) {
          $stmt = $con->prepare("SELECT * FROM $nombreTabla LIMIT 3"); // Obtener las primeras 3 filas de cada tabla
          $stmt->execute();
          $result = $stmt->get_result();

          // Crear estructura HTML para cada tabla
          echo '<div class="contenido_tabla">';
          echo '<div class="titulo_contenido_tabla"><h2>Tabla ' . $nombreTabla . '</h2></div>';
          echo '<div class="tablas">';
          echo '<table border="1"><thead><tr>';

          // Agregar encabezados de columna dinámicamente
          foreach ($columnas as $columna) {
            echo '<th>' . $columna . '</th>';
          }
          echo '</tr></thead><tbody>';

          // Mostrar las primeras 3 filas de la tabla
          while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            foreach ($columnas as $columna) {
              echo '<td>' . $row[$columna] . '</td>';
            }
            echo '</tr>';
          }

          $stmt = $con->prepare("SELECT COUNT(*) as total_filas FROM $nombreTabla"); // Obtener el recuento total de filas
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_filas = $row['total_filas'];

            if ($total_filas > 0) {
              echo '<tr class="ultima_fila"><td colspan="' . count($columnas) . '">y ' . $total_filas . ' filas más...</td></tr>';
            } else {
              echo '<tr class="ultima_fila"><td colspan="' . count($columnas) . '">No tiene ninguna fila esta tabla</td></tr>';
            }
          } else {
            echo '<tr class="ultima_fila"><td colspan="' . count($columnas) . '">Error al obtener el recuento de filas</td></tr>';
          }

          echo '</tbody></table></div></div>';
        }
        ?>

      </div>
      <div>

  </main>
</body>