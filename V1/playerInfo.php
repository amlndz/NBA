<?php
// Verificar si se ha enviado información del jugador a través de GET
if(isset($_GET['playerInfo'])) {
    // Obtener el nombre del jugador enviado desde el formulario
    $playerInfo = $_GET['playerInfo'];
    
    // Aquí puedes realizar cualquier procesamiento adicional necesario con la información del jugador
    // Por ejemplo, puedes dividir el nombre y el apellido si es necesario
    $playerName = '';
    $playerSurname = '';
    
    if(!empty($playerInfo)){
        $playerInfo = strtolower($playerInfo);
        $parts = explode(' ', $playerInfo, 2);
        $playerName = $parts[0];
        $playerSurname = $parts[1];
    }
    
    // Ahora puedes usar $playerName y $playerSurname como necesites en tu lógica de negocio
    
    // Por ejemplo, puedes imprimirlos para verificar que se hayan recibido correctamente
    echo "Nombre del jugador: " . $playerName . "<br>";
    echo "Apellido del jugador: " . $playerSurname . "<br>";
} else {
    // Si no se proporciona ningún valor en el campo jugador, puedes redirigir al usuario de vuelta a la página anterior o mostrar un mensaje de error
    echo "No se proporcionó información del jugador.";
}
