$(document).ready(function () {
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

    // Accordion
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

    //Listen for an OVERLAY
    (function ($) {
        $.fn.inlineStyle = function (prop) {
            return this.prop("style")[$.camelCase(prop)];
        };
    }(jQuery));

    //Tile specific overlays
    $('.tile-title').each(function (i, obj) {
        if ($(obj).inlineStyle("background-image")) {

            var image = window.getComputedStyle(obj, null).getPropertyValue("background-image");
            var css = 'linear-gradient(rgba(0, 0, 0, 0) 39%, #000 100%), ' + image;

            $(this).removeAttr('style').css({"background": css});
        }
    });

    // Modal tiles moving image and title
    var allTags = document.getElementsByClassName("modal-image");
    for (var i = 0, len = allTags.length; i < len; i++) {
        var tile = allTags[i];
        var link = $(this).find('.ult-modal-input-wrapper img');
        var id = '.' + link.data('class-id');
        var image = link.attr('src');

        $(tile).find('.vc_column-inner').empty();

        var newElement = document.createElement("a");
        newElement.setAttribute('href', 'javascript:void(0)');
        newElement.setAttribute('data-class-id', link.data('class-id'));
        newElement.setAttribute('class', link.attr('class'));
        newElement.setAttribute('data-overlay-class', link.data('overlay-class'));

        newElement.appendChild(tile.cloneNode(true));
        tile.parentNode.replaceChild(newElement, tile);

        var title = $(id).find('h3').text();
        $(newElement).find('.vc_column-inner').html("<div class='tile-title' style=\"background: linear-gradient(rgba(0, 0, 0, 0) 39%, #000 100%), url('" + image + "') center center no-repeat\">" + title + "</div>");
        $(id).find('.ult_modal-header').remove();
    }
});

// Initializers
$(document).ready(function () {
    $(document).on('click touchstart', '.navbar .dropdown-menu', function (e) {
        e.stopPropagation();
    });

    $("img").addClass("img-responsive");
    $('#slider').carousel();
    $('[data-toggle="tooltip"]').tooltip();
    $("#in-page-nav").affix({ offset: 1 });
    $('#fullpage').fullpage({ scrollBar: true });
});

