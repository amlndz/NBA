<?php
    require "autenticarUsuario.php";
    $usuario_autenticado = autenticar();
    
    require "connection.php";
    $conn = connect();
    // Verificar si se ha enviado información del jugador a través de GET
    if (isset($_GET['id'])) {
        // Obtener el nombre del jugador enviado desde el formulario
        $id = $_GET['id'];
        if (!empty($id)) {
            $sql = "SELECT * FROM final_teams t WHERE t.id LIKE ?";
            $stmt = $conn->prepare($sql);

            // Verificar si la consulta se preparó correctamente
            if ($stmt === false) {
                die("Error al preparar la consulta: " . $conn->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();
            // Obtener el resultado de la consulta
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $abbreviation = $row['abbreviation'];
            $city = $row['city'];
            $conference = $row['conference'];
            $division = $row['division'];
            $full_name = $row['full_name'];
            $name = $row['name'];

            echo "EQUIPO: ".$full_name." (".$abbreviation.")"."<br>";
            echo "CIUDAD: ".$city."<br>";
            echo "CONFERENCIA: ".$conference."<br>";
            echo "DIVISION: ".$division."<br>";
        }

        

    }
    else {
            echo "error";
        }
