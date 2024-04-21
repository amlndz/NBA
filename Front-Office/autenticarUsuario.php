<?php
    session_start();
    // Verificar si el usuario está autenticado
    function autenticar(){
        $usuario_autenticado = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
        return $usuario_autenticado;
    }
    function mostrarMensaje($mensaje){
        $_SESSION['error_message'] = $mensaje;
    }    
    function registrar(){
        require "connection.php";

        // Inicializar las variables para mantener los datos del formulario
        $nombre_completo = "";
        $nombre_usuario = "";
        $email = "";
        
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            
            // Recoger los datos del formulario
            $nombre_completo = $_POST["full_name"];
            $nombre_usuario = $_POST["username"];
            $email = $_POST["email"];
            $contrasena = $_POST["password"];
            $confirmar_contrasena = $_POST["confirm_password"];
            $_SESSION['full_name'] = $nombre_completo;
            $_SESSION['username'] = $nombre_usuario;
            $_SESSION['email'] = $email;
            // Conectar a la base de datos
            $conn = connect();
            
            // Comprobar si el correo electrónico ya existe en la base de datos
            $sql_email = "SELECT id FROM final_users WHERE email = ?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->bind_param("s", $email);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            
            if ($result_email->num_rows > 0) {
                mostrarMensaje("[!] - El correo electrónico ya está registrado.");
                return;
            }
            
            // Comprobar si el nombre de usuario ya existe en la base de datos
            $sql_username = "SELECT id FROM final_users WHERE username = ?";
            $stmt_username = $conn->prepare($sql_username);
            $stmt_username->bind_param("s", $nombre_usuario);
            $stmt_username->execute();
            $result_username = $stmt_username->get_result();
            
            if ($result_username->num_rows > 0) {
                mostrarMensaje("[!] - El nombre de usuario ya está registrado.");
                return;
            }
            
            // Comprobar si las contraseñas coinciden
            if ($contrasena === $confirmar_contrasena) {
                // Encriptar la contraseña
                $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);
                
                // Preparar una declaración SQL para insertar los datos en la tabla final_users
                $sql_insert = "INSERT INTO final_users (full_name, username, email, password) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bind_param("sssss", $nombre_completo, $nombre_usuario, $email, $contrasena_encriptada);
                
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
                mostrarMensaje("[!] - Las contraseñas no coinciden.");
            }
            
            // Cerrar la conexión
            $stmt->close();
            $conn->close();
        }
    }

    function iniciarSesion(){
        require "connection.php";
    
        // Iniciar la sesión si no está iniciada
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recoger los datos del formulario
            $nombre_usuario = $_POST["username"];
            $contrasena = $_POST["password"];
            $_SESSION['username'] = $nombre_usuario;
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
                    
                    // Obtener información del usuario
                    getUserInfo();
    
                    // Redirigir al usuario a la página anterior
                    if (isset($_SESSION['prev_page'])) {
                        $prevPage = $_SESSION['prev_page'];
                        unset($_SESSION['prev_page']); // Limpiar la variable de sesión
                        $stmt->close();
                        $conn->close();
                        header("Location: $prevPage");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    } else {
                        $stmt->close();
                        $conn->close();
                        // Si no hay una página anterior guardada, redirigir a una página predeterminada
                        header("Location: index.php");
                        exit; // Asegúrate de que el script se detenga después de la redirección
                    }
                } else {
                    mostrarMensaje("[!] - Nombre o usuario incorrecto.");
                    return;
                }
            } else {
                mostrarMensaje("[!] - Nombre o usuario incorrecto.");
            }
            $stmt->close();
            $conn->close();
            // Cerrar la conexión   
        }
    }
    

    function checkSessionTimeout() {
        $inactive_timeout = 300; // 5minutos
    
        if (isset($_SESSION['last_interaction_time'])) {
            $elapsed_time = time() - $_SESSION['last_interaction_time'];
            
            if ($elapsed_time > $inactive_timeout) {
                // Destruir la sesión y cerrarla
                
                session_unset();
                session_destroy();
                session_start(); // Iniciar una nueva sesión
                header("Location: login.php");
                exit; // Asegurarse de que el script se detenga después de la redirección
            }
        }
    
        // Actualizar el tiempo de última interacción en la sesión
        $_SESSION['last_interaction_time'] = time();
    }

    function actualizarUsuario(){

        // Inicializar las variables para mantener los datos del formulario
        $nombre_completo = "";
        $nombre_usuario = "";
        $email = "";
        // Verificar si se ha enviado el formulario
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            
            // Recoger los datos del formulario
            $contrasena = $_POST["password"];
            $confirmar_contrasena = $_POST["confirm_password"];
            $nombre_completo = $_POST["full_name"];
            $nombre_usuario = $_POST["username"];
            $email =$_POST["email"];
            // Conectar a la base de datos
            $conn = connect();
            // Comprobar si el correo electrónico ya existe en la base de datos
            $sql_email = "SELECT id FROM final_users WHERE email = ? AND id != ?";
            $stmt_email = $conn->prepare($sql_email);
            $stmt_email->bind_param("si", $email, $_SESSION['id']);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            if ($result_email->num_rows > 0) {
                mostrarMensaje("[!] - El correo electrónico ya está registrado.");
                return;
            }
            // Comprobar si el nombre de usuario ya existe en la base de datos
            $sql_username = "SELECT id FROM final_users WHERE username = ? AND id != ?";
            $stmt_username = $conn->prepare($sql_username);
            $stmt_username->bind_param("si", $nombre_usuario, $_SESSION['id']);
            $stmt_username->execute();
            $result_username = $stmt_username->get_result();
            if ($result_username->num_rows > 0) {
                mostrarMensaje("[!] - El nombre de usuario ya está registrado.");
                return;
            }
            // Comprobar si las contraseñas coinciden
            if ($contrasena === $confirmar_contrasena) {
                // Encriptar la contraseña
                $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);
                
                // Preparar una declaración SQL para actualizar los datos en la tabla final_users
                $sql_update = "UPDATE final_users SET full_name = ?, username = ?, email = ?, password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql_update);
                $stmt->bind_param("ssssi", $nombre_completo,$nombre_usuario , $email, $contrasena_encriptada, $_SESSION['id']);
                $stmt->execute();
                $_SESSION['full_name'] = $nombre_completo;
                $_SESSION['username'] = $nombre_usuario;
                $_SESSION['email'] = $email;
            }
            else {
                mostrarMensaje("[!] - Las contraseñas no coinciden.");
            }
            // cerrar conexiones
            $stmt->close();
            $conn->close();
        }
    }

    

 
    function getUserInfo(){

        $conn = connect();
        $user_sql = "SELECT * FROM final_users WHERE username LIKE ?";
        $stmt = $conn->prepare($user_sql);

        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $_SESSION['username']);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result === false) {
            die("Error al ejecutar la consulta: " . $stmt->error);
        }

        $user = $user_result->fetch_assoc();

        if ($user === null) {
            die("No se encontró un usuario con ese nombre de usuario");
        }

        $_SESSION['id'] = $user['id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['fav_player'] = $user['fav_player'];
        $_SESSION['fav_team'] = $user['fav_team'];
        $conn->close();
    }

    