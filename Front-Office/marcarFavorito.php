<?php
session_start();

include 'connection.php';

$conn = connect();

if(isset($_SESSION['username']) && isset($_POST['jugador_id'])) {
    $jugador_id = $_POST['jugador_id'];
    $username = $_SESSION['username'];

    // Preparar la consulta SQL
    $stmt = $conn->prepare("UPDATE final_users SET fav_player = ? WHERE username = ?");
    $stmt->bind_param("is", $jugador_id, $username);
    $_SESSION['fav_player'] = $jugador_id; // Actualizar la variable de sesión $_SESSION['fav_player
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
    echo "Error: Usuario no autenticado o ID de jugador no recibido";
}
