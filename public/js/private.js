$(document).ready(function() {
    if ($('.back-top').length) {
        var scrollTrigger = 300,
            backToTop = function () {
                var scrollTop = $(window).scrollTop();
                if (scrollTop > scrollTrigger) {
                    $('.back-top').addClass('show');

                } else {
                    $('.back-top').removeClass('show');
                }
            };
        backToTop();
        $(window).on('scroll', function () {
            backToTop();
        });

        $('.back-top').on('click', function (e) {
            e.preventDefault();
            $('html,body').animate({
                scrollTop: 0
            }, 700);
        });
    }

    $(window).scroll(function(){
        if ($(window).scrollTop() >= 1) {
            $('header').addClass('fixed-header');
        }
        else {
            $('header').removeClass('fixed-header');
        }
    });

    $('.banner-slider').slick({
        autoplay: false,
        arrow: false,
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        prevArrow: '<button class="prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button class="next"><i class="fas fa-chevron-right"></i></i></button>',
    });
    $('.news-slider').slick({
        autoplay: true,
        arrow: false,
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    vertical: false,
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    vertical: false,
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ],
        prevArrow: '<button class="prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button class="next"><i class="fas fa-chevron-right"></i></i></button>',
        dots: false,
    });
    $(function() {
        $('nav#menu').mmenu({
            extensions	: [ 'effect-slide-menu', 'pageshadow' ],
            searchfield	: false,
            counters	: true,
            navbar 		: {
                title		: 'Menu'
            },
            navbars		: [{
                position	: 'top',
                content		: [
                    'prev',
                    'title',
                    'close'
                ]
            }
            ]
        });
    });

    // This is a functions that scrolls to #{blah}link
    function goToByScroll(id) {
        id = id.replace("link", "");
        // Scroll
        $('html,body').animate({
            scrollTop: $("#" + id).offset().top - 60
        }, 'slow');
    }

    $(".link-scroll").click(function(e) {
        e.preventDefault();
        goToByScroll(this.id);
    });
})
