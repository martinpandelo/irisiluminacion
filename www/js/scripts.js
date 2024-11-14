function root() {
    var scripts = document.getElementsByTagName('script'),
        script = scripts[scripts.length - 1],
        path = script.getAttribute('src').split('/'),
        pathname = location.pathname.split('/'),
        notSame = false,
        same = 0;

    for (var i in path) {
        if (!notSame) {
            if (path[i] == pathname[i]) {
                same++;
            } else {
                notSame = true;
            }
        }
    }
    return location.origin + pathname.slice(0, same).join('/');
}

var WEB_ROOT = root();

(function($) {

    $.extend($.easing, {
        easeInOutCubic: function(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        }
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > ($('.navigation').innerHeight() + $('.top-bar').innerHeight())) {
            $(".navigation").addClass('fixed');
            $(".page").css('margin-top', $('.navigation').innerHeight());
        } else if ($(this).scrollTop() < ($('.navigation').innerHeight() + $('.top-bar').innerHeight())) {
            $(".navigation").removeClass('fixed');
            $(".page").css('margin-top', 0);
        }

        //boton subir
        if ($(this).scrollTop() > 300) {
            $("#js_up").slideDown(300);
        } else {
            $("#js_up").slideUp(300);
        }
    });


    /***************** Nav Transformicon ******************/

    /* When user clicks the Icon */
    $('.nav-toggle').click(function(e) {
        $(this).toggleClass('active');
        $('.header-nav').toggleClass('open');
        e.preventDefault();
    });
    /* When user clicks a link */
    $('.header-nav li a').click(function() {
        $('.nav-toggle').toggleClass('active');
        $('.header-nav').toggleClass('open');

    });

    // Toggle Search
    $('.show-search').click(function() {
        $('.search-form').css('margin-top', '0');
    });
    $('.close-search').click(function() {
        $('.search-form').css('margin-top', '-60px');
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    /***************** carrousel novedades ******************/
    $('#car-novedades').owlCarousel({
        loop: true,
        margin: 0,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplayHoverPause: true,
        navSpeed: 1000,
        navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
        responsive: {
            0: {
                items: 2,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 4,
                nav: true
            }
        }
    });

})(jQuery);