<?php
// Incluir archivo de conexión y cualquier otro archivo necesario
include "connection.php";
include "playersInfoBBDD.php";

// Obtener los parámetros de búsqueda
$name = $_GET['name'];

// Realizar la búsqueda de jugadores utilizando la función definida en playersInfoBBDD.php
$results = searchPlayers($name);

// Generar HTML para los resultados de la búsqueda
$html = '';
foreach ($results as $result) {
    // Construir el HTML para mostrar cada jugador
    $html .= '<div class="col-lg-4 mb-4">';
    $html .= '<h4>' . $result['first_name'] . ' ' . $result['last_name'] . '</h4>';
    // Agrega más información si es necesario
    $html .= '</div>';
}

// Devolver los resultados en formato HTML
echo $html;
