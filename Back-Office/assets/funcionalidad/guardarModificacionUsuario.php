<?php
session_start();

if ($_SESSION['administrador'] != 1) {
    header("Location: ../Front-Office/index.php");
    exit();
}
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

if ($password != null) {
    $contrasena_encriptada = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("UPDATE final_users SET full_name=?, username=?, email=?, password=?, administrador=? WHERE id=?");
    $stmt->bind_param("ssssii", $nombre, $username, $mail, $contrasena_encriptada, $isAdmin, $id_usuario);
    $stmt->execute();
} else {

    $stmt = $con->prepare("UPDATE final_users SET full_name=?, username=?, email=?, administrador=? WHERE id=?");
    $stmt->bind_param("sssii", $nombre, $username, $mail, $isAdmin, $id_usuario);
    $stmt->execute();
}

header("Location: ../../listadoUsuarios.php?page=$page");
$stmt->close();
$con->close();

exit();
?>