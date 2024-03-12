function confirm_truncation() {
    var response = confirm("¿Estás seguro que quieres continuar?");

    if (response) {
        document.getElementById("truncateButton").submit();
    }
}