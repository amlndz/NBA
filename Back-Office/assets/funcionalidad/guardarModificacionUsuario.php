<?php
include "./connection.php";
$con = connect();

var_dump($_POST);
// Obtener los datos del formulario
$id_usuario = $_POST['iduser'];
$username = $_POST['username'];
$password = $_POST['password'];
$nombre = $_POST['name'];
$apellido1 = $_POST['lastName1'];
$apellido2 = $_POST['lastName2'];
$mail = $_POST['email'];
$favplayer = $_POST['favoritePlayer'];
$favteam = $_POST['favoriteTeam'];
$isAdmin = isset($_POST['isAdmin']) ? 1 : 0;

// Ejecutar la consulta SQL para actualizar los datos en la tabla
$stmt = $con->prepare("UPDATE final_users SET username=?, password=?, nombre=?, apellido1=?, apellido2=?, mail=?, FAVPLAYER=?, FAVTEAM=?, Administrador=? WHERE id_user=?");
$stmt->bind_param("ssssssssii", $username, $password, $nombre, $apellido1, $apellido2, $mail, $favplayer, $favteam, $isAdmin, $id_usuario);
$stmt->execute();

header("Location: ../../listadoUsuarios.php");
exit();
?>