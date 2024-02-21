<?php
    if($_POST){
        if(isset($_POST['playerInfo']) && !empty($_POST['playerInfo'])) {
            $playerInfo = trim($_POST['playerInfo']);
            if(strpos($playerInfo, ' ') !== false) {
                // Mas de un atributo
                // Hay más de una palabra, redirigir a playerInfo.php con los parámetros
                $url = "playerInfo.php?playerInfo=" . urlencode($playerInfo);
                header("Location: $url");
                exit();
            } else {
                // Solo un atributo
                $url = "playerSearcher.php?playerInfo=" . urlencode($playerInfo);
                header("Location: $url");
                exit();
            }
        }
        else if(isset($_POST['teamInfo']) && !empty($_POST['teamInfo'])){
            $teamInfo = trim($_POST['teamInfo']);
            $url ="teamInfo.php?teamInfo=".urlencode($teamInfo);
            header("Location: $url");
        }
        else{
            $url="gamesInfo.php";
            header("Location: $url");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searcher Players</title>
    <style>
    .input-estilizado {
    padding: 8px;
    border: 2px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    width: 200px;
    outline: none;
    transition: border-color 0.3s;
    }

    .input-estilizado:focus {
    border-color: dodgerblue;
    }
</style>
</head> 
<body>
    <h1>BUSCAR JUGADOR</h1>
    <form action="searcher.php" method="POST">
        <label for="playerInfo">Buscar jugador</label>
        <input type="text" class="input-estilizado" id="playerInfo" name="playerInfo" placeholder="Nombre o Apellido del Jugador">
        <input type="submit" value="🔎" ><br><br>
        <label for="playerInfo">Buscar Equipo</label>
        <input type="text" class="input-estilizado" id="teamInfo" name="teamInfo" placeholder="Nombre del equipo">
        <input type="submit" value="🔎"><br><br>
        <input type="submit" value="PARTIDOS">

    </form>    
</body>
</html>