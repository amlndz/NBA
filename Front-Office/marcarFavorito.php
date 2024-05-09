<?php
session_start();

include 'connection.php';

$conn = connect();

if(isset($_SESSION['username']) && isset($_POST['id']) && isset($_POST['tipo'])) {
    $id = $_POST['id'];
    $username = $_SESSION['username'];
    $tipo = $_POST['tipo'];
    $fav_id = $_POST['fav_id'];

    if ($fav_id === 'null') {
        $fav_id = null; // Convertimos 'null' a valor NULL
    }

    // Determinamos el campo a actualizar según el tipo
    if ($tipo === 'jugador') {
        $campo = 'fav_player';
    } elseif ($tipo === 'equipo') {
        $campo = 'fav_team';
    }

    // Preparar la consulta SQL
    $stmt = $conn->prepare("UPDATE final_users SET $campo = ? WHERE username = ?");
    $stmt->bind_param("is", $fav_id, $username);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Éxito"; // Envía una respuesta al cliente si la actualización es exitosa
    } else {
        echo "Error al marcar como favorito"; // Envía un mensaje de error si la actualización falla
    }

    // Cerrar la conexión
    $stmt->close();
    $conn->close();
} else {
    echo "Error: Usuario no autenticado o ID no recibido";
}
