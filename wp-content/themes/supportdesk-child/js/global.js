// Instantiate the Bootstrap carousel
$('.carousel').carousel({
    interval: false
});

(function () {
    $('.carousel .item').each(function () {
        var itemToClone = $(this);

        for (var i = 1; i < 4; i++) {
            itemToClone = itemToClone.next();

            // wrap around if at end of item collection
            if (!itemToClone.length) {
                itemToClone = $(this).siblings(':first');
            }

            // grab item, clone, add marker class, add to collection
            itemToClone.children(':first-child').clone()
                .addClass("cloneditem-" + (i))
                .appendTo($(this));
        }
    });
}());


$(document).ready(function () {
    $('<tooltip title="<img src=\'http://res.cloudinary.com/coolpad/image/upload/v1472860236/support/IMEI.jpg\' alt=\'How to find your IMEI number\' class=\'img-responsive\'>"><i class="fa fa-question" aria-hidden="true"></i></tooltip>').insertAfter("#wpas_imei_wrapper label");
    $('tooltip').tooltip({
        animated: 'fade',
        placement: 'bottom',
        html: true
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

var appended = false;
