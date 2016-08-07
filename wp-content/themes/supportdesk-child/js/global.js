// Instantiate the Bootstrap carousel
$('.carousel').carousel({
    interval: false
});

(function(){
    $('.carousel .item').each(function(){
        var itemToClone = $(this);

        for (var i=1;i<4;i++) {
            itemToClone = itemToClone.next();

            // wrap around if at end of item collection
            if (!itemToClone.length) {
                itemToClone = $(this).siblings(':first');
            }

            // grab item, clone, add marker class, add to collection
            itemToClone.children(':first-child').clone()
                .addClass("cloneditem-"+(i))
                .appendTo($(this));
        }
    });
}());


$(document).ready(function(){
    $('<tooltip title="<img src=\'http://www.imei.info/media/t/gsm-cache/O/s/DILXz4-d.jpg\' alt=\'How to find your IMEI number\'>"><i class="fa fa-question" aria-hidden="true"></i></tooltip>').insertAfter("#wpas_imei_wrapper label");
    $('tooltip').tooltip({
        animated: 'fade',
        placement: 'bottom',
        html: true
    });});

var appended = false;
