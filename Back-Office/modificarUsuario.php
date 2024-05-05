<?php
include "./assets/funcionalidad/connection.php";

$con = connect();

$id_usuario = $_GET['iduser'];
$pagina = $_GET['page'];
$stmt = $con->prepare("select * from final_users where id=$id_usuario");
$stmt->execute();
$result = $stmt->get_result();

$contents = file_get_contents("./plantillaModificarUsuarios.php");
$split_contents = explode("##datos##", $contents);

$body = "";
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

    if ($row["administrador"] == 1) {
        $aux .= "<script>document.getElementById('isAdmin').checked = true;</script>";
    } else {
        $aux .= "<script>document.getElementById('isAdmin').checked = false;</script>";
    }

    $body .= $aux;
}
echo $split_contents[0] . $body;
$stmt->close();
$con->close();
