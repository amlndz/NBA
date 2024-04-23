function recarga_de_tablas() {
    var response = confirm("¿Estás seguro que quieres recargar las tablas?");

    if (response) {
        // Hacer la petición al script PHP
        fetch('assets/funcionalidad/reload_tables.php')
        .then(response => response.text())
        .then(data => {
            // Mostrar el mensaje de confirmación
            document.getElementById("confirmationMessage").innerText = data;
            document.getElementById("confirmationMessage").style.display = "block";
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}