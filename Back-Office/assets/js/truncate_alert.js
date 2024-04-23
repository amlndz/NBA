function confirm_truncation() {
    var response = confirm("¿Estás seguro que quieres truncar las tablas?");

    if (response) {
        // Hacer la petición al script PHP
        fetch('assets/funcionalidad/truncate_tables.php')
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