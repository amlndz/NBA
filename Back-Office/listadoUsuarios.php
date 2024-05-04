<?php
include "./assets/funcionalidad/connection.php";

$con = connect();

if (isset($_SESSION['username'])) {
    echo "Si esta seteada " + $_SESSION['username'];
} else {
    echo "NO ESTA SETEADA";
}

$pagina = $_GET['page'];
$tamano_pagina = 25;
$offset = ($pagina - 1) * $tamano_pagina;


if (isset($_POST['term'])) {
    $searchTerm = $_POST['term'];
    $stmt = $con->prepare("select * from final_users where full_name like '%$searchTerm%' order by id limit $tamano_pagina offset $offset");
} else {
    $stmt = $con->prepare("select * from final_users order by id limit $tamano_pagina offset $offset");
}

$stmt->execute();
$result = $stmt->get_result();

// Contenido HTML para la sección de fila (reemplazar valores dinámicos)
$contents = file_get_contents("./plantillaListado.php");
$split_contents = explode("##fila##", $contents);

$body_fila = "";
while ($row = $result->fetch_assoc()) {
    $aux = $split_contents[1];
    $aux = str_replace("##id_user##", $row["id"], $aux);
    $aux = str_replace("##username##", $row["username"], $aux);
    $aux = str_replace("##password##", $row["password"], $aux);
    $aux = str_replace("##nombre##", $row["full_name"], $aux);
    $aux = str_replace("##mail##", $row["email"], $aux);
    $aux = str_replace("##favplayer##", $row["fav_player"], $aux);
    $aux = str_replace("##favteam##", $row["fav_team"], $aux);
    $aux = str_replace("##Administrador##", $row["administrador"], $aux);
    $aux = str_replace('##page##', $pagina, $aux);

    $body_fila .= $aux;
}

if (isset($_POST['term'])) {
    $searchTerm = $_POST['term'];
    $stmt_count = $con->prepare("SELECT COUNT(id) AS total FROM final_users where full_name like '%$searchTerm%'");
} else {
    $stmt_count = $con->prepare("SELECT COUNT(id) AS total FROM final_users");
}

$stmt_count->execute();
$result_count = $stmt_count->get_result();

if ($result_count->num_rows > 0) {
    $row_count = $result_count->fetch_assoc();
    $total_filas = $row_count['total'];
} else {
    $total_filas = 0;
}

// Calcular el número de páginas
$numero_redondeado = ceil($total_filas / $tamano_pagina);

// Contenido HTML para la sección de paginación
$body_paginacion = '<ul class="ul_paginacion">';
for ($i = 1; $i <= $numero_redondeado; $i++) {
    if ($i == $pagina) {
        $body_paginacion .= '<li class="li_paginacion active"><a id="caja_pagina" href="?page=' . $i . '">' . $i . '</a></li>';
    } else {
        $body_paginacion .= '<li class="li_paginacion"><a id="caja_pagina" href="?page=' . $i . '">' . $i . '</a></li>';
    }
}
$body_paginacion .= '</ul>';

echo $split_contents[0] . $body_fila; // Mostrar sección ##fila##
echo $body_paginacion; // Mostrar sección de paginación

$stmt->close();
$con->close();
?>