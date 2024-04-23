<?php
include "./assets/funcionalidad/connection.php";

$con = connect();

$id_usuario = $_GET['iduser'];
$stmt = $con->prepare("select * from final_users where id_user=$id_usuario");
$stmt->execute();
$result = $stmt->get_result();

$contents = file_get_contents("./plantillaModificarUsuarios.php");
$split_contents = explode("##datos##", $contents);

$body = "";
while ($row = $result->fetch_assoc()) {

    $aux = $split_contents[1];
    $aux = str_replace("##id_user##", $row["id_user"], $aux);
    $aux = str_replace("##username##", $row["username"], $aux);
    $aux = str_replace("##password##", $row["password"], $aux);
    $aux = str_replace("##nombre##", $row["nombre"], $aux);
    $aux = str_replace("##apellido1##", $row["apellido1"], $aux);
    $aux = str_replace("##apellido2##", $row["apellido2"], $aux);
    $aux = str_replace("##mail##", $row["mail"], $aux);
    $aux = str_replace("##favplayer##", $row["FAVPLAYER"], $aux);
    $aux = str_replace("##favteam##", $row["FAVTEAM"], $aux);

    if ($row["Administrador"] == 1) {
        $aux .= "<script>document.getElementById('isAdmin').checked = true;</script>";
    } else {
        $aux .= "<script>document.getElementById('isAdmin').checked = false;</script>";
    }

    $body .= $aux;
}
echo $split_contents[0] . $body;
