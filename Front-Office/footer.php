<?php
    include_once "connection.php"; // Asegúrate de tener el archivo de conexión
    $conn = connect(); // Realiza la conexión a la base de datos

    require "credentials.php"; // Asegúrate de tener el archivo de credenciales

    // Consulta para obtener la última fecha de modificación de la tabla final_averages
    $sql = "SELECT MAX(update_time) AS last_update_time
            FROM information_schema.TABLES 
            WHERE TABLE_SCHEMA = '$database' 
            AND TABLE_NAME = 'final_averages'";

    $result = $conn->query($sql); // Ejecuta la consulta

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $update_time = $row['last_update_time'];
        } else {
            echo "No se encontró la tabla final_averages o no se pudo obtener la fecha de modificación.";
        }
    } else {
        echo "Error al ejecutar la consulta: " . $conn->error;
    }

    $conn->close(); // Cierra la conexión
?>


<!-- Footer Start -->
<div class="container-fluid footer text-white mt-6 pt-6 px-0 position-relative overlay-top">
        <div class="row mx-0 pt-5 px-sm-3 px-lg-5 mt-4">
            <div class="col-lg-4 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Get In Touch</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i>Universidad Publica de Navarra, UPNA</p>
                <p><i class="fa fa-phone-alt mr-2"></i>+34 000000000</p>
                <p class="m-0"><i class="fa fa-envelope mr-2"></i>info@example.com</p>
            </div>
            <div class="col-lg-4 col-md-6 mb-5">
                <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Follow Us</h4>
                <h6 class="text-white text-uppercase mb-4">Estudiantes de Ingeniería Informática</h6>
                <h6 class="text-white text-uppercase mb-4">Proyecto de Sistemas de Información Web</h6>
            </div>
            <div class="col-lg-2 col-md-6 mb-5">
                <p>Alejandro Meléndez Uriz</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="https://www.linkedin.com/in/alejandro-meléndez-526795291/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square"  href="https://www.instagram.com/a_melendez__/" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="col-lg-2 col-md-6 mb-5">
                <p>Ander Monreal Ayanz</p>
                <div class="d-flex justify-content-start">
                    <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" href="https://www.linkedin.com/in/ander-monreal-ayanz-485794291/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-lg btn-outline-light btn-lg-square" href="https://www.instagram.com/andeermonnreal/" target="_blank"><i class="fab fa-instagram" ></i></a>
                </div>
            </div>
        </div>
        <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
            <p class="m-0 text-white">Ultima actualizacion de datos: <?php echo $update_time ?></p>
        </div>
        <div class="container-fluid text-center text-white border-top py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
            <p class="mb-2 text-white">Copyright &copy; <a class="font-weight-bold" href="#">Domain</a>. All Rights Reserved.</a></p>
            <p class="m-0 text-white">Designed by <a class="font-weight-bold" href="https://htmlcodex.com">HTML Codex</a></p>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.js"></script>
    <script src="assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="assets/mail/jqBootstrapValidation.min.js"></script>
    <script src="assets/mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="assets/js/main.js"></script>
</body>
</html>
