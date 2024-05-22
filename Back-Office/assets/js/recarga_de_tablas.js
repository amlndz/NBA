function recarga_de_tablas() {
  var response = confirm("¿Estás seguro que quieres recargar las tablas?");

  if (response) {
    // Hacer la petición al script PHP
    console.log("Entro aqui");
    fetch("../funcionalidad/reload_tables.php")
      .then((response) => response.text())
      .then((data) => {
        // Mostrar el mensaje de confirmación
        console.log("He terminado de recargar las tablas");
        document.getElementById("confirmationMessage").innerText = data;
        document.getElementById("confirmationMessage").style.display = "block";
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  }
  console.log("no deberia de terminar yo creo");
}
