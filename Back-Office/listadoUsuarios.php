<?php
include "./assets/funcionalidad/connection.php";

$con = connect();

$stmt = $con->prepare("select * from final_users");
$stmt->execute();
$result = $stmt->get_result();

$contents = file_get_contents("./plantillaListado.php");
$split_contents = explode("##fila##", $contents);

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
    $aux = str_replace("##Administrador##", $row["Administrador"], $aux);

    $body .= $aux;
}
echo $split_contents[0] . $body;

