<?php
function conexion()
{
    include 'credentials.php';

    $conn = mysqli_connect($hostname, $username, $password, $database);
    //$conn = new mysqli($hostname, $username, $password, $database);

    if (!$conn) {
        die('<p>Error al conectar con servidor MySQL: ' . $conn->connect_error . '</p>');
    }
    return $conn;
}
