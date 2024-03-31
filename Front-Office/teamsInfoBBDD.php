<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    
    include "connection.php";
    $conn = connect();

    
    $conference = isset($_GET['conference']) ? $_GET['conference'] : null;

    $sql = "SELECT * FROM final_teams";
    if ($conference) {
        $sql .= " WHERE conference = ?";
    }

    if (isset($_GET['team'])) {
        $team = $_GET['team'];
    
        // Consulta SQL para buscar equipos
        if (isset($_GET['team'])) {
            $team = $_GET['team'];
        
            // Consulta SQL para buscar equipos
           
            $sql = "SELECT * FROM final_teams WHERE full_name LIKE '%$team%' OR abbreviation LIKE '%$team%'";

            $result = $conn->query($sql);
        
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                echo "<div id='teams-container' class='container-fluid pt-5'><div class='container'><div class='row'>";
                for ($i = 0; $i < $result->num_rows; $i += 1) {
                    $row = $result->fetch_assoc();
                    $teamId = $row['id'];
                    $url = "teamInfo.php?id=".urlencode($teamId);
                    echo "
                    <div class='col-lg-4 mb-5'>
                        <div class='row align-items-center'>
                            <div class='col-sm-5'>
                                <a href='$url'><img class='img-fluid mb-3 mb-sm-0' src='./assets/img/teams/".$teamId.".svg' alt='img' onerror=\"this.onerror=null;this.src='./assets/img/logoNBA.png'\"></a>
                            </div>
                            <div class='col-sm-7'>
                                <h4><a hrefa class='team-name' href='$url'>".$row['full_name']." (".$row['abbreviation'].")</a></h4>
                                <p class='m-0'>
                                    abbreviation: ".$row['abbreviation']."<br/>city: ". $row['city']."<br/>Conference: ".$row['conference']."<br/>Division: ".$row['division']."
                                </p>
                            </div>
                        </div>
                    </div>";
                }
            
                exit;
            }
        }
        
        $result = $conn->query($sql);
    
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo "<div id='teams-container' class='container-fluid pt-5'><div class='container'><div class='row'>";
            for ($i = 0; $i < $result->num_rows; $i += 1) {
                $row = $result->fetch_assoc();
                $teamId = $row['id'];
                $url = "teamInfo.php?id=".urlencode($teamId);
                echo "
                <div class='col-lg-4 mb-5'>
                    <div class='row align-items-center'>
                        <div class='col-sm-5'>
                            <a href='$url'><img class='img-fluid mb-3 mb-sm-0' src='./assets/img/teams/".$teamId.".svg' alt='img' onerror=\"this.onerror=null;this.src='./assets/img/logoNBA.png'\"></a>
                        </div>
                        <div class='col-sm-7'>
                            <h4><a hrefa class='team-name' href='$url'>".$row['full_name']." (".$row['abbreviation'].")</a></h4>
                            <p class='m-0'>
                                Abreviatura: ".$row['abbreviation']."<br/>Ciudad: ". $row['city']."<br/>Conferencia: ".$row['conference']."<br/>División: ".$row['division']."
                            </p>
                        </div>
                    </div>
                </div>";
            }
        
            exit;
        }
    }
    

    $stmt = $conn->prepare($sql);

    if ($conference) {
        $stmt->bind_param("s", $conference);
    }

    $stmt->execute();

    // Obtener el resultado de la consulta
    $result = $stmt->get_result();

    // Cerrar la declaración
    $stmt->close();


    // Cerrar la conexión
    $conn->close();