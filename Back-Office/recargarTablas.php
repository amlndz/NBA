<?php
session_start();

if ($_SESSION['administrador'] != 1) {
  header("Location: ../Front-Office/index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Panel de administracion - recargar tablas
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
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.1.0" rel="stylesheet" />
  <link rel="stylesheet" href="assets/css/titulo.css">
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
  <script src="assets/js/recarga_de_tablas.js" defer></script>
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
          <a class="nav-link text-white " href="resumenTablas.php">
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
          <a class="nav-link text-white active bg-gradient-primary" href="recargarTablas.php">
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
        <a class="btn bg-gradient-primary w-100" href="../Front-Office/logout.php" type="button">
          <i class="material-icons opacity-10">logout</i> Cerrar sesion
        </a>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="true">
      <div class="container-fluid">
        <div class="navbar-wrapper">
          <a class="navbar-brand" href="../Front-Office/index.php"> <!-- Enlace como título -->
            <h3>
              <img src="../Front-Office/assets/img/logoNBA.png" class="transparente" id="logo-menu-image" alt="nba"
                width="10%" height="10%">
            </h3>
          </a>
        </div>
        <div class="d-flex justify-content-end align-items-center">
          <a href="perfil.php" class="me-3"> <!-- Enlace a la derecha -->
            <h2>
              <?php
              include_once './assets/funcionalidad/obtener_nombre_usuario.php';
              echo obtenerNombreUsuario();
              ?>
              <i class="fa fa-user me-sm-1"></i>
            </h2>
          </a>
        </div>
      </div>
    </nav>

    <div class="contenedor-titulo">
      <div class="titulo">-=-=-=- Recargar tablas -=-=-=-</div>
    </div>

    <div class="col-lg-8 col-md-10 mx-auto">
      <div class="card mt-4">
        <div class="card-body p-3 pb-0">
          <div class="alert alert-secondary alert-dismissible text-white text-center" role="alert">
            <span class="text-lg">¡Advertencia! Al presionar este botón, se procederá a truncar y realizar una recarga
              en la base de datos utilizando los datos de una API externa. Esto puede resultar en cambios significativos
              en la base de datos y afectar a la integridad y consistencia de los datos almacenados. Antes de continuar,
              asegúrate de entender completamente el impacto de esta acción y de haber realizado una copia de seguridad
              de la base de datos actual. Solo debes proceder si estás seguro de que los datos de la API son correctos y
              deseas sobrescribir los datos existentes en la base de datos. Si tienes alguna duda, consulta con un
              administrador de base de datos o un experto en el tema.</span>
          </div>
        </div>
      </div>
      <div class="card mt-4">
        <div class="card-body p-3 pb-0">
          <div class="alert alert-primary alert-dismissible text-white text-center" role="alert">
            <span class="text-lg">¡Advertencia! Truncar una tabla eliminará todos los datos almacenados en ella de forma
              permanente. Esta acción no se puede deshacer y puede tener consecuencias graves en la integridad y
              funcionalidad de la base de datos. Antes de proceder, asegúrate de entender completamente el impacto de
              esta operación y de haber realizado una copia de seguridad de los datos relevantes. Solo debe truncar una
              tabla si estás absolutamente seguro de que es necesario y si tienes el permiso adecuado para hacerlo. Si
              tienes alguna duda, consulta con un administrador de bases de datos o un experto en el tema.</span>
          </div>
        </div>
      </div>
      <div class="card mt-4">
        <div class="card-body p-3 pb-0">
          <div class="alert alert-secondary alert-dismissible text-white text-center" role="alert">
            <span class="text-lg">Esta acción tardará bastante tiempo, no borrará la tabla de usuarios</span>
          </div>
        </div>
      </div>
      <div class="mt-4 p-3 text-center">
        <img src="assets/img/api.jpg" alt="img-blur-shadow" width="30%" class="img-fluid shadow border-radius-xl">
      </div>

      <div class="text-center d-flex py-4 justify-content-center align-items-center">
        <form id="truncateButton" action="assets/funcionalidad/reload_tables.php" method="post">
          <button class="btn bg-gradient-danger mb-0 toast-btn" type="submit">Recargar tablas</button>
        </form>

      </div>
    </div>
    <div id="confirmationMessage" class="mt-3" style="display: none;"></div>

  </main>
</body>