<?php
     if($_POST){
        if(isset($_POST['playerInfo'])) {
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
            }
        }
     }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searcher Players</title>
</head>
<body>
    <h1>BUSCAR JUGADOR</h1>
    <form action="searcher.php" method="POST">
        <label for="playerInfo">Buscar jugador</label>
        <input type="text" id="playerInfo" name="playerInfo" placeholder="Nombre o Apellido del Jugador"><br>
        <input type="submit" value="Buscar">
    </form>    
</body>
</html>