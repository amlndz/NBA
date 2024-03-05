<?php  
    include "conection.php";

    
    $con = conection();
    
    $result = $con->query("set foreign_key_checks = 0");
    $result = $con->query("truncate table games");
    $result = $con->query("truncate table players");
    $result = $con->query("truncate table stats");
    $result = $con->query("truncate table teams");
    $result = $con->query("set foreign_key_checks = 1");

    echo "<br>[+] Tablas truncadas<br>";
    
    $stmt = $con->prepare("select city from teams");
    $stmt->execute();
    $result =  $stmt->get_result();

    while ($data = $result->fetch_assoc()) {
        echo $data["city"] . "<br>";
    }
    
?>