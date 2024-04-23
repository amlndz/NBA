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
    <link rel="stylesheet" href="assets/css/perfilUsuarios.css">
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <script src="./assets/js/botonPerfil.js" crossorigin="anonymous"></script>
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
                    <a class="nav-link text-white" href="listadoUsuarios.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">format_list_numbered</i>
                        </div>
                        <span class="nav-link-text ms-1">Listado de usuarios</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white " href="buscarEliminarUsuario.php">
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">delete</i>
                        </div>
                        <span class="nav-link-text ms-1">Eliminar un usuario</span>
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
            <div class="titulo">-=-=-=- Listado de usuarios -=-=-=-</div>
        </div>

        ##datos##
        <div class="card my-4 mx-4">
            <div class="card-body px-0 pb-2">
                <div class="container">
                    <h1>Perfil de Usuario</h1>
                    <form id="profile-form" action="assets/funcionalidad/guardarModificacionUsuario.php" method="post">
                        <div class="form-group">
                            <input type="hidden" name="iduser" id="id_usuario" value="##id_user##">
                            <label for="userId">ID de Usuario: ##id_user##</label>
                        </div>
                        <div class="form-group">
                            <label for="username">Usuario:</label>
                            <input type="text" name="username" id="username" value="##username##">
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña:</label>
                            <input type="text" name="password" id="password" value="##password##">
                        </div>
                        <div class="form-group">
                            <label for="name">Nombre:</label>
                            <input type="text" name="name" id="name" value="##nombre##">
                        </div>
                        <div class="form-group">
                            <label for="lastName1">Primer apellido:</label>
                            <input type="text" name="lastName1" id="lastName1" value="##apellido1##">
                        </div>
                        <div class="form-group">
                            <label for="lastName2">Segundo apellido:</label>
                            <input type="text" name="lastName2" id="lastName2" value="##apellido2##">
                        </div>
                        <div class="form-group">
                            <label for="email">Correo Electrónico:</label>
                            <input type="text" name="email" id="email" value="##mail##">
                        </div>
                        <div class="form-group">
                            <label for="favoritePlayer">Jugador Favorito: ##favplayer##</label>
                            <input type="hidden" name="favoritePlayer" id="favoritePlayer" value="##favplayer##">
                        </div>
                        <div class="form-group">
                            <label for="favoriteTeam">Equipo Favorito: ##favteam##</label>
                            <input type="hidden" name="favoriteTeam" id="favoriteTeam" value="##favteam##">
                        </div>
                        <div class="form-group">
                            <label for="isAdmin">
                                <input type="checkbox" name="isAdmin" id="isAdmin">
                                - Administrador:
                            </label>
                        </div>

                        <div class="btn-container">
                            <!-- Botón para eliminar -->
                            <button type="submit" class="btn-delete" id="btn-delete">Eliminar Usuario</button>

                            <script>
                                // Agregar un escuchador de eventos al botón "Eliminar Usuario"
                                document.getElementById("btn-delete").addEventListener("click", function (event) {
                                    // Prevenir el comportamiento predeterminado del botón
                                    event.preventDefault();

                                    // Obtener el ID del usuario a eliminar
                                    var id_usuario = document.getElementById("id_usuario").value;

                                    // Crear un formulario dinámicamente
                                    var form = document.createElement("form");
                                    form.setAttribute("method", "post");
                                    form.setAttribute("action", "assets/funcionalidad/eliminarUsuario.php");

                                    // Crear un campo de entrada oculto para enviar el ID del usuario
                                    var input = document.createElement("input");
                                    input.setAttribute("type", "hidden");
                                    input.setAttribute("name", "iduser");
                                    input.setAttribute("value", id_usuario);

                                    // Agregar el campo de entrada al formulario
                                    form.appendChild(input);

                                    // Agregar el formulario al cuerpo del documento
                                    document.body.appendChild(form);

                                    // Enviar el formulario
                                    form.submit();
                                });
                            </script>

                            <!-- Botón para volver -->
                            <a href="listadoUsuarios.php" class="btn-menu">Volver a la lista</a>

                            <!-- Botón para guardar -->
                            <button type="submit" class="btn-save" id="btn-save">Guardar datos</button>

                            <script>
                                document.getElementById("btn-delete").addEventListener("click", function (event) {
                                    // Prevenir el comportamiento predeterminado del botón
                                    event.preventDefault();

                                    // Obtener el ID del usuario a eliminar
                                    var id_usuario = document.getElementById("id_usuario").value;

                                    // Crear un formulario dinámicamente
                                    var form = document.createElement("form");
                                    form.setAttribute("method", "post");
                                    form.setAttribute("action", "assets/funcionalidad/eliminarUsuario.php");

                                    // Crear un campo de entrada oculto para enviar el ID del usuario
                                    var input = document.createElement("input");
                                    input.setAttribute("type", "hidden");
                                    input.setAttribute("name", "iduser");
                                    input.setAttribute("value", id_usuario);

                                    // Agregar el campo de entrada al formulario
                                    form.appendChild(input);

                                    // Agregar el formulario al cuerpo del documento
                                    document.body.appendChild(form);

                                    // Enviar el formulario
                                    form.submit();
                                });
                            </script>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        ##datos##
    </main>
</body>