<?php
require_once "./assets/funcionalidad/conexion.php";

function obtenerNombreUsuario()
{
    $con = conexion();

    $username = $_SESSION['username'];
    $stmt = $con->prepare("select username from final_users where username = '$username' ");
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $con->close();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['username'];
    } else {
        return null;
    }

}
?>