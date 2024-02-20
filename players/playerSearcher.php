    <?php include 'menu.php' ?>
    <h2>JUGADORES NBA</h2>
    <div class="cont-player-list">
        <?php
        $servername = "http://webalumnos.tlm.unavarra.es:10800";
        $username = "grupo25";
        $password = "fex1roMi4j";
        $database = "db_grupo25";
        
        // Crear conexión
        $conn = new mysqli("dbserver", $username, $password, $database);
        
        // Verificar conexión
        if ($conn->connect_error) {
            die("La conexión falló: " . $conn->connect_error);
        }
        
        // Verificar si se ha enviado información del jugador a través de GET
        if (isset($_GET['playerInfo'])) {
            // Obtener el nombre del jugador enviado desde el formulario
            $playerInfo = $_GET['playerInfo'];
            
            // Preparar la consulta SQL
            $sql = "SELECT p.first_name, p.last_name, p.number, t.full_name as team_name 
                    FROM PLAYERS p
                    INNER JOIN TEAMS t ON p.team_id = t.id
                    WHERE LOWER(p.first_name) LIKE ? OR LOWER(p.last_name) LIKE ?";

        // Preparar la declaración
        $stmt = $conn->prepare($sql);

        // Vincular parámetros y ejecutar la consulta
        $playerInfo = '%' . $playerInfo . '%';
        $stmt->bind_param("ss", $playerInfo, $playerInfo);
        $stmt->execute();
        }
        else{
            $sql = "SELECT p.first_name, p.last_name, p.number, t.full_name as team_name FROM PLAYERS p INNER JOIN TEAMS t ON p.team_id = t.id";
            // Preparar la declaración
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
        // Obtener el resultado de la consulta
        $result = $stmt->get_result();

        // Verificar si se encontraron resultados
        if ($result->num_rows > 0) {
            echo "<h2>Lista de jugadores:</h2>";
            echo "<ul>";
            while ($row = $result->fetch_assoc()) {
                // Construir el enlace con nombre y apellido como parámetros GET
                $playerInfoUrl=$row['first_name']." ". $row['last_name'];
                $url = "playerInfo.php?playerInfo=". urlencode($playerInfoUrl);
                echo "<li>Nombre: <a class='player-name' href=$url>" . $row['first_name'] . " " . $row['last_name'] . "</a> - Dorsal: " . $row['number'] . " - Equipo: " . $row['team_name'] . "</li>";
            }
            echo "</ul>";
        }
        // Cerrar la declaración
        $stmt->close();


        // Cerrar la conexión
        $conn->close();
        ?>
</div>

</body>
</html>
