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
            margin: 30,
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
