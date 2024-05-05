<?php
include "./connection.php";

$con = connect();

$num_filas = 100;

// Bucle para insertar filas en la tabla
for ($i = 1; $i <= $num_filas; $i++) {
    // Generar valores aleatorios para cada fila (ejemplo)
    $nombre = "Bot " . $i;
    $username = "bot " . $i;
    $correo = "bot" . $i . "@example.com";
    $password = "bot" . $i . "1234";

    // Consulta de inserción SQL
    $sql = "INSERT INTO final_users (full_name, username, email, password) VALUES ('$nombre', '$username', '$correo', '$password')";

    // Ejecutar la consulta de inserción
    try {
        $con->query($sql);
    } catch (Exception $e) {
        continue;
    }
}

header("Location: ../../listadoUsuarios.php?page=1");
$con->close();
exit();
?>