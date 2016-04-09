$(function() {
    $('a[href*=#]:not([href=#]):not(.carousel-control):not(.tab-toggle)').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });

    $(document).on('scroll', function(){
        if ($(window).scrollTop() > 100) {
            $('.scroll-top-wrapper').addClass('show');
        } else {
            $('.scroll-top-wrapper').removeClass('show');
        }
    });

    $('.scroll-top-wrapper').on('click', function () {
        $('html, body').animate({scrollTop: $('body').offset().top}, 500, 'linear');
    });

    $('.tooltip-toggle').tooltip();

    $('.carousel').carousel({'interval': 50000000})

    $(".flash").fadeOut(2000);

    $('a.delete').click(function () {
        return confirm('Вы точно хотите удаить это?');
    });

    $('.selectizable').selectize({
        placeholder: 'Выбрать ...'
    });

    Array.prototype.removeValue = function (value) {
        if (this.indexOf(value) !== -1) {
            this.splice(this.indexOf(value), 1);
        }
    };
});
