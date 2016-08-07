$(document).ready(function () {

    $(window).load(function () {
        $(document).on('click touchstart', '.navbar .dropdown-menu', function (e) {
            e.stopPropagation();
        })
    });

    $("img")
        .addClass("img-responsive");


    /*
     Functions
     */

    // Check for mobile
    var isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    if (isMobile.any()) {
        $(window).resize(function () {
                var width = $(window).width();
                if (width <= 680) {
                    $('#buy_now').addClass('modal-dialog');
                }
                else {
                    $('#buy_now').removeClass('modal-dialog');
                }
            })
            .resize();//trigger the resize event on page load.
    }


    /*
     Accordion
     */
    $('#accordion').on('shown.bs.collapse', function () {
        var $accordin = $(this).parent();
        $accordin.find(".fa-minus").removeClass("fa-minus").addClass("fa-plus");
        $accordin.find(".panel-heading").css({'font-weight': 'bold', 'color': '#696969'});
        $accordin.find("#icon").css("color", "#60c8d7");
    }).on('hidden.bs.collapse', function () {
        var $accordin = $(this).parent();
        $accordin.find(".fa-plus").removeClass("fa-plus").addClass("fa-minus");
        $accordin.find(".panel-heading").css({'font-weight': '', 'color': '#a0a0a0'});
        $accordin.find("#icon").css("color", "#a0a0a0");
    });

    /*
     Product page
     */
    // Initiate affix and append in-page nav for this page
    $("#in-page-nav").affix({
        offset: 1
    });

    $('#buy_now').appendTo("body");


    /* $("#product-navigation ul li").click(function () {
     $('.box').hide().eq($(this).index()).show();
     $('body').animate({"scrollTop": "0px"}, 100);
     });
     $("#action-section ul li").click(function () {
     $('.box').hide().eq($(this).index()).show();
     $('body').animate({"scrollTop": "0px"}, 100);
     });*/

    // Carousel
    $('#slider').carousel();

    //Handles the carousel thumbnails
    $('[id^=carousel-selector-]').click(function () {
        var id_selector = $(this).attr("id");
        try {
            var id = /-(\d+)$/.exec(id_selector)[1];
            console.log(id_selector, id);
            jQuery('#slider').carousel(parseInt(id));
        } catch (e) {
            console.log('Regex failed!', e);
        }
    });
    // When the carousel slides, auto update the text
    $('#slider').on('slid.bs.carousel', function (e) {
        var id = $('.item.active').data('slide-number');
        $('#carousel-text').html($('#slide-content-' + id).html());
    });
});

/*

 Listen for an OVERLAY

 */
(function ($) {
    $.fn.inlineStyle = function (prop) {
        return this.prop("style")[$.camelCase(prop)];
    };
}(jQuery));

// Tile specific
$(document).ready(function () {
    $('.tile-title').each(function (i, obj) {
        if ($(obj).inlineStyle("background-image")) {

            var image = window.getComputedStyle(obj, null).getPropertyValue("background-image");
            var css = 'linear-gradient(rgba(0, 0, 0, 0) 39%, #000 100%), ' + image;

            $(this).removeAttr('style').css({"background": css});
        }
    });
});

// Generic overlay

/*

 Carousel set all same height

 */
equalheight = function (container) {
    var currentRow = {
        cols: [],
        h: 0
    };
    var topPostion = -1;
    $(container).each(function () {
        var $el = $(this);
        $($el).height('auto');
        if (topPostion != $el.position().top) {
            for (var j = 0; j < currentRow.cols.length; j++) {
                currentRow.cols[j].height(currentRow.h);
            }
            topPostion = $el.position().top;
            currentRow = {
                cols: [],
                h: 0
            };
        }
        currentRow.cols.push($el);
        if ($el.height() > currentRow.h) {
            currentRow.h = $el.height();
        }


    });
    for (var j = 0; j < currentRow.cols.length; j++) {
        currentRow.cols[j].height(currentRow.h);
    }

};
equalheight('.item');
$(window).load(function () {
    equalheight('.item');
});

$(window).resize(function () {
    equalheight('.item');
});