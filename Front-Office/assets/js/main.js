$(document).ready(function() {
    $('#search-players-form input[name="name"]').on('input', function() {
        $.ajax({
            url: 'playersInfoBBDD.php', // La URL de tu archivo PHP
            type: 'GET',
            data: $('#search-players-form').serialize(), // Los datos del formulario
            success: function(data) {
                // Divide la respuesta en jugadores y paginación
                var result = $(data);
                var players = result.filter('#players-container').html();
                var pagination = result.filter('#pagination-container').html();

                // Actualiza los contenedores con los nuevos resultados
                $('#players-container').html(players);
                $('#pagination-container').html(pagination);;
            }
        });
    });
});

$(document).ready(function() {
    $('#search-teams-form input[name="team"]').on('input', function() {
        $.ajax({
            url: 'teamsInfoBBDD.php', // La URL de tu archivo PHP para los equipos
            type: 'GET',
            data: $('#search-teams-form').serialize(), // Los datos del formulario
            success: function(data) {
                // Actualiza el contenedor con los nuevos resultados
                var result = $(data);
                var teams = result.filter('#teams-container').html();
                $('#teams-container').html(teams);
            }
        });
    });
});
(function ($) {

   
    "use strict";
    
    // Dropdown on click
    $(document).ready(function () {
        $('.navbar .dropdown .dropdown-toggle').on('click', function () {
            if ($(window).width() > 992) {
                $(this).siblings('.dropdown-menu').toggleClass('show');
            }
        });

        // Close dropdown when clicking outside
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.navbar .dropdown').length) {
                $('.navbar .dropdown .dropdown-menu').removeClass('show');
            }
        });

        // Back to top button
        $(window).scroll(function () {
            if ($(this).scrollTop() > 100) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }
        });
        $('.back-to-top').click(function () {
            $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
            return false;
        });

        // Date and time picker
        $('.date').datetimepicker({
            format: 'L'
        });
        $('.time').datetimepicker({
            format: 'LT'
        });

        // Testimonials carousel
        $(".testimonial-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1500,
            margin: 20,
            dots: true,
            loop: true,
            center: true,
            responsive: {
                0:{
                    items:1
                },
                576:{
                    items:1
                },
                768:{
                    items:2
                },
                992:{
                    items:3
                }
            }
        });
    });
    
})(jQuery);

$(document).ready(function() {
    $('.fav-btn').click(function() {
        var btn = $(this); // Captura el botón actual
        var id = btn.data('id');
        var tipo = btn.data('tipo'); // Nuevo dato que indica si es jugador o equipo
        var isFavorito = btn.hasClass("favorito");

        // Definimos el valor de fav_id que será enviado al servidor
        var favIdValue = isFavorito ? null : id;

        $.ajax({
            url: 'marcarFavorito.php',
            type: 'post',
            data: {id: id, tipo: tipo, fav_id: favIdValue},
            success: function(response){
                if (response.trim() === "Éxito") {
                    // Cambiamos la clase y la imagen del botón
                    btn.toggleClass("favorito");
                    var imgSrc = isFavorito ? "./assets/img/nonfav.avif" : "./assets/img/fav.avif";
                    var imgAlt = isFavorito ? "icono corazon no favorito" : "icono corazon favorito";
                    btn.find("img").attr("src", imgSrc).attr("alt", imgAlt);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log(textStatus, errorThrown);
            }
        });
    });
});






