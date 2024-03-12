<?php
    include "conection.php";

    $con = conection();

    $stmt = $con->prepare("select * from users");
    $stmt->execute();
    $result = $stmt->get_result(); 

    $contents = file_get_contents("users_list.html");
    $split_contents = explode("##fila##", $contents);

    $body = "";
    while ($row = $result->fetch_assoc()) {
        $aux = $split_contents[1];
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##username##", $row["id_users"], $aux);
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        $aux = str_replace("##id_users##", $row["id_users"], $aux);
        
    }
?>

##id_users##</th>
<th>##username##</th>
<th>##password##</th>
<th>##nombre##</th>
<th>##apellido1##</th>
<th>##apellido2##</th>
<th>##correo##</th>
<th>##jugadorfavorito##</th>
<th>##equipofavorito##</th>