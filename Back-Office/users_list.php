<?php
    include "conection.php";

    $con = connect();

    $stmt = $con->prepare("select * from users");
    $stmt->execute();
    $result = $stmt->get_result(); 

    $contents = file_get_contents("users_list.html");
    $split_contents = explode("##fila##", $contents);

    $body = "";
    while ($row = $result->fetch_assoc()) {
        
        $aux = $split_contents[1];
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##username##", $row["username"], $aux);
        $aux = str_replace("##password##", $row["password"], $aux);
        $aux = str_replace("##nombre##", $row["nombre"], $aux);
        $aux = str_replace("##apellido1##", $row["apellido1"], $aux);
        $aux = str_replace("##apellido2##", $row["apellido2"], $aux);
        $aux = str_replace("##favplayer##", $row["favplayer"], $aux);
        $aux = str_replace("##favteam##", $row["favteam"], $aux);
        $aux = str_replace("##administrador##", $row["administrador"], $aux);
        
        $body .= $aux;
    }
    echo $split_contents[0] . $body . $split_contents[1];

