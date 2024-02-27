<?php
    session_start();

    // Verificar si el usuario está autenticado
    function autenticar(){
        $usuario_autenticado = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
        return $usuario_autenticado;
    }
