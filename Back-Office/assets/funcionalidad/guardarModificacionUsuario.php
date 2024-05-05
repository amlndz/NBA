<?php
include "./connection.php";
$con = connect();

// Obtener los datos del formulario
$id_usuario = $_POST['iduser'];
$username = $_POST['username'];
$password = $_POST['password'];
$nombre = $_POST['name'];
$mail = $_POST['email'];
$favplayer = $_POST['favoritePlayer'];
$favteam = $_POST['favoriteTeam'];
$isAdmin = isset($_POST['isAdmin']) ? 1 : 0;
$page = $_POST['page'];


// Ejecutar la consulta SQL para actualizar los datos en la tabla
$stmt = $con->prepare("UPDATE final_users SET full_name=?, username=?, email=?, password=?, administrador=? WHERE id=?");
$stmt->bind_param("ssssii", $nombre, $username, $mail, $password, $isAdmin, $id_usuario);
$stmt->execute();

header("Location: ../../listadoUsuarios.php?page=$page");
$stmt->close();
$con->close();

exit();
?>