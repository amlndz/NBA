<?php
include "./connection.php";
$con = connect();

$id_usuario = $_POST['iduser'];

// Preparar y ejecutar la consulta SQL para eliminar el usuario
$stmt = $con->prepare("DELETE FROM final_users WHERE id_user = ?");
$stmt->bind_param("i", $id_usuario); // "i" indica que el valor es un entero
$stmt->execute();

// Verificar si se eliminó correctamente
if ($stmt->affected_rows > 0) {
    echo "El usuario se ha eliminado correctamente.";
} else {
    echo "No se pudo eliminar el usuario.";
}

header("Location: ../../listadoUsuarios.php");
exit();
?>