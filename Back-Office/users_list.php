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