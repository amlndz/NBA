<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    Panel de administracion - listado usuarios
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
          <a class="nav-link text-white active bg-gradient-primary" href="listadoUsuarios.php?page=1">
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
        <li class="nav-item">
          <a class="nav-link text-white " href="meterUsuarios.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">add</i>
            </div>
            <span class="nav-link-text ms-1">Meter usuarios</span>
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
      <div class="titulo">-=-=-=- Listado de usuarios -=-=-=-</div>
    </div>

    <script>
      $(document).ready(function () {
        // Captura el evento 'keyup' en el campo de entrada
        $('#searchInput').on('keyup', function () {
          // Obtén el valor actual del campo de entrada
          var searchTerm = $(this).val();

          // Realiza la solicitud AJAX solo si el término de búsqueda no está vacío
          // Realiza la llamada AJAX usando jQuery
          $.ajax({
            url: 'listadoUsuarios.php?page=1',  // URL del script PHP que manejará la solicitud
            method: 'POST',  // Método HTTP para la solicitud (puedes usar 'GET' o 'POST')
            data: { term: searchTerm },  // Datos a enviar al servidor (puedes incluir más parámetros aquí)
            dataType: 'html',  // Tipo de datos esperados en la respuesta (por ejemplo, 'html', 'json', etc.)
            success: function (response) {
              var $responseHtml = $(response);

              // Ahora puedes buscar un elemento específico dentro de la respuesta utilizando su id
              var elementoDeseado = $responseHtml.find('#panel_central');

              $("#panel_central").html("");
              $('#panel_central').html(elementoDeseado);
              // Busca la sección de filas en la respuesta
              console.log(response);
            },
            error: function (xhr, status, error) {
              // Maneja errores de la solicitud AJAX
              console.error('Error en la solicitud AJAX:', error);
            }
          });
        });
      });
    </script>


    <div id="busqueda_de_usuario">
      <input type="text" id="searchInput" placeholder="Buscar un usuario">
    </div>

    <div id="panel_central">
      <div class="card my-4 mx-4 m-10">
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary font-weight-bolder opacity-7">ID</th>
                  <th class="text-uppercase text-secondary font-weight-bolder opacity-7">Nombre</th>
                  <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Usuario y contraseña</th>
                  <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Equipo y jugador
                    favorito</th>
                  <th class="text-center text-uppercase text-secondary font-weight-bolder opacity-7">Administrador</th>
                </tr>
              </thead>

              ##fila##
              <tbody>
                <tr>
                  <td>
                    <div class="d-flex px-3 py-1">
                      ##id_user##
                    </div>
                  </td>
                  <td>
                    <div class="d-flex flex-column justify-content-center px-4">
                      <h6 class="mb-0 text-sm">##nombre## </h6>
                      <p class="text-xs text-secondary mb-0">##mail##</p>
                    </div>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">##username##</p>
                    <p class="text-xs font-weight-bold mb-0">##password##</p>
                  </td>
                  <td class="align-middle text-center text-sm">
                    <p class="text-xs text-secondary mb-0">##favplayer##</p>
                    <p class="text-xs text-secondary mb-0">##favteam##</p>
                  </td>
                  <td class="align-middle text-center">
                    <span class="text-secondary text-xs font-weight-bold">##Administrador##</span>
                  </td>
                  <td class="align-middle">
                    <a href="modificarUsuario.php?iduser=##id_user##&page=##page##"
                      class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                      data-original-title="Edit user">
                      Editar
                    </a>
                  </td>
                </tr>
              </tbody>
              ##fila##
            </table>
          </div>
        </div>
      </div>
      <ul class="ul_paginacion"></ul>

  </main>
</body>