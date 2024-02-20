document.addEventListener('DOMContentLoaded', function() {
    // Función para cerrar el menú
    function cerrarMenu() {
        document.getElementById('btn-menu').checked = false;
    }

    // Función para cerrar el contenedor de usuario
    function cerrarContenedorUsuario() {
        document.getElementById('btn-user').checked = false;
    }

    // Event listener para cerrar el menú al hacer clic fuera de él
    var menuContainer = document.getElementById('menu-container');
    menuContainer.addEventListener('click', function(event) {
        // Comprueba si el clic ocurrió dentro del contenedor del menú
        if (event.target === menuContainer) {
            // Oculta el menú al hacer clic en el fondo semitransparente
            cerrarMenu();
        }
    });

    // Event listener para cerrar el contenedor de usuario al hacer clic fuera de él
    var userContainer = document.getElementById('user-container');
    userContainer.addEventListener('click', function(event) {
        // Comprueba si el clic ocurrió dentro del contenedor de usuario
        if (event.target === userContainer) {
            // Oculta el contenedor de usuario al hacer clic en el fondo semitransparente
            cerrarContenedorUsuario();
        }
    });
});
