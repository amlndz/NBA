document.addEventListener('DOMContentLoaded', function() {
    var menuContainer = document.getElementById('menu-container');
    
    menuContainer.addEventListener('click', function(event) {
        // Comprueba si el clic ocurrió dentro del contenedor del menú
        if (event.target === menuContainer) {
            // Oculta el menú al hacer clic en el fondo semitransparente
            document.getElementById('btn-menu').checked = false;
        }
    });
});
