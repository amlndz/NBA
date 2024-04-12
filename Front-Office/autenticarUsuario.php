<?php
    session_start();

    // Verificar si el usuario está autenticado
    function autenticar(){
        $usuario_autenticado = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
        return $usuario_autenticado;
    }

    function registrar(){
        require "connection.php"; // Incluir el archivo de conexión
        
        // Inicializar las variables para mantener los datos del formulario
        $nombre_completo = "";
        $nombre_usuario = "";
        $email = "";
        $fecha_nacimiento = "";
        
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombre_completo = $_POST["full_name"];
            $nombre_usuario = $_POST["username"];
            $email = $_POST["email"];
            $fecha_nacimiento = $_POST["birthday"];
            $contrasena = $_POST["password"];
            $confirmar_contrasena = $_POST["confirm_password"];
            
            // Conectar a la base de datos
            $conn = connect();
            
            // Comprobar si el correo electrónico ya existe en la base de datos
            $sql_email = "SELECT id FROM final_users WHERE email = ?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->bind_param("s", $email);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            
            if ($result_email->num_rows > 0) {
                echo "<script type='text/javascript'>alert('El correo electrónico ya está registrado.');</script>";
                return;
            }
            
            // Comprobar si el nombre de usuario ya existe en la base de datos
            $sql_username = "SELECT id FROM final_users WHERE username = ?";
            $stmt_username = $conn->prepare($sql_username);
            $stmt_username->bind_param("s", $nombre_usuario);
            $stmt_username->execute();
            $result_username = $stmt_username->get_result();
            
            if ($result_username->num_rows > 0) {
                echo "<script type='text/javascript'>alert('El nombre de usuario ya está registrado.');</script>";
                return;
            }
            
            // Comprobar si las contraseñas coinciden
            if ($contrasena === $confirmar_contrasena) {
                // Encriptar la contraseña
                $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);
                
                // Preparar una declaración SQL para insertar los datos en la tabla final_users
                $sql_insert = "INSERT INTO final_users (full_name, username, email, birthday, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bind_param("sssss", $nombre_completo, $nombre_usuario, $email, $fecha_nacimiento, $contrasena_encriptada);
                
                // Ejecutar la consulta
                if (!$stmt->execute()) {
                    echo "Error al ejecutar la consulta: " . $stmt->error;
                } else {
                    // Si las credenciales son válidas, establecer la variable de sesión
                    $_SESSION['usuario_autenticado'] = true;
            
                    // Redirigir al usuario a la página anterior
                    if (isset($_SESSION['prev_page'])) {
                        $prevPage = $_SESSION['prev_page'];
                        unset($_SESSION['prev_page']); // Limpiar la variable de sesión
                        header("Location: $prevPage");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    } else {
                        // Si no hay una página anterior guardada, redirigir a una página predeterminada
                        header("Location: index.php");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    }
                }
            } else {
                echo "<script type='text/javascript'>alert('Las contraseñas no coinciden.');</script>";
            }
            
            // Cerrar la conexión
            $conn->close();
        }
    }

    function iniciarSesion(){
        require "connection.php"; // Incluir el archivo de conexión

        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombre_usuario = $_POST["username"];
            $contrasena = $_POST["password"];
            
            // Conectar a la base de datos
            $conn = connect();
            
            // Buscar al usuario en la base de datos por nombre de usuario
            $sql = "SELECT id, password FROM final_users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $nombre_usuario);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $hashed_password = $row["password"];
                
                // Verificar si la contraseña es correcta
                if (password_verify($contrasena, $hashed_password)) {
                    // Si las credenciales son válidas, establecer la variable de sesión
                    $_SESSION['usuario_autenticado'] = true;
                    
                    // Redirigir al usuario a la página anterior
                    if (isset($_SESSION['prev_page'])) {
                        $prevPage = $_SESSION['prev_page'];
                        unset($_SESSION['prev_page']); // Limpiar la variable de sesión
                        header("Location: $prevPage");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    } else {
                        // Si no hay una página anterior guardada, redirigir a una página predeterminada
                        header("Location: index.php");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    }
                } else {
                    echo "<script type='text/javascript'>alert('Contraseña incorrecta.');</script>";
                }
            } else {
                echo "<script type='text/javascript'>alert('Nombre de usuario no encontrado.');</script>";
            }
            
            // Cerrar la conexión
            $conn->close();
        }
    }