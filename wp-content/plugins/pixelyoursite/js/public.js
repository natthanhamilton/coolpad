jQuery(document).ready(function( $ ) {

    if (typeof pys_fb_pixel_options === 'undefined') {
        return;
    }

    var options = pys_fb_pixel_options; // variable shorthand

    // load FB pixel
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.agent='dvpixelyoursite';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
        document,'script','https://connect.facebook.net/en_US/fbevents.js');

    /**
     * Setup Events Handlers
     */
    !function setupEventHandlers() {

        /**
         * WooCommerce Events
         */
        if (options.hasOwnProperty('woo')) {

            // WooCommerce single product AddToCart handler on AJAX-ed themes
            if (options.woo.is_product && options.woo.add_to_cart_enabled ) {

                $(document).on('added_to_cart', function () {

                    var params = {};

                    if (options.woo.single_product.type === 'variable') {

                        var $form = $('form.variations_form.cart'),
                            variation_id = false,
                            qty;

                        if ($form.length === 1) {
                            variation_id = $form.find('input[name="variation_id"]').val();
                        }

                        if (false === variation_id || false === options.woo.single_product.add_to_cart_params.hasOwnProperty(variation_id)) {
                            console.error('PYS PRO: product variation ID not found in available product variants.');
                            return;
                        }

                        params = clone(options.woo.single_product.add_to_cart_params[variation_id], {});
                        qty = parseInt($form.find('input[name="quantity"]').val());
                        params.value = params.value * qty;

                    } else {
                        params = clone(options.woo.single_product.add_to_cart_params, {});
                    }

                    fbq('track', 'AddToCart', params);

                });

            }

        }

    }();

    regularEvents();
    customCodeEvents();

    // AddToCart button
    $(".ajax_add_to_cart").click(function(e){

        var attr = $(this).attr('data-pys-event-id');

        if( typeof attr == 'undefined' || typeof pys_woo_ajax_events == 'undefined' ) {
            return;
        }

        evaluateEventByID( attr.toString(), pys_woo_ajax_events );

    });

    // EDD AddToCart
    $('.edd-add-to-cart').click(function () {

        try {

            // extract pixel event ids from classes like 'pys-event-id-{UNIQUE ID}'
            var classes = $.grep(this.className.split(" "), function (element, index) {
                return element.indexOf('pys-event-id-') === 0;
            });

            // verify that we have at least one matching class
            if (typeof classes == 'undefined' || classes.length == 0) {
                return;
            }

            // extract event id from class name
            var regexp = /pys-event-id-(.*)/;
            var event_id = regexp.exec(classes[0]);

            if (event_id == null) {
                return;
            }

            evaluateEventByID(event_id[1], pys_edd_ajax_events);

        } catch (e) {
            console.log(e);
        }

    });

    /**
     * Process Init, General, Search, Standard (except custom code), WooCommerce (except AJAX AddToCart, Affiliate and
     * PayPal events. In case if delay param is present - event will be fired after desired timeout.
     */
    function regularEvents() {

        if( typeof pys_events == 'undefined' ) {
            return;
        }

        for( var i = 0; i < pys_events.length; i++ ) {

            var eventData = pys_events[i];

            if( eventData.hasOwnProperty('delay') == false || eventData.delay == 0 ) {

                fbq( eventData.type, eventData.name, eventData.params );

            } else {

                setTimeout( function( type, name, params ) {
                    fbq( type, name, params );
                }, eventData.delay * 1000, eventData.type, eventData.name, eventData.params );

            }

        }

    }

    /**
     * Process only custom code Standard events.
     */
    function customCodeEvents() {

        if( typeof pys_customEvents == 'undefined' ) {
            return;
        }

        $.each( pys_customEvents, function( index, code ) {
            eval( code );
        } );

    }

    /**
     * Fire event with {eventID} from =events= events list. In case of event data have =custom= property, code will be
     * evaluated without regular Facebook pixel call.
     */
    function evaluateEventByID( eventID, events ) {

        if( typeof events == 'undefined' || events.length == 0 ) {
            return;
        }

        // try to find required event
        if( events.hasOwnProperty( eventID ) == false ) {
            return;
        }

        var eventData = events[ eventID ];

        if( eventData.hasOwnProperty( 'custom' ) ) {

            eval( eventData.custom );

        } else {

            fbq( eventData.type, eventData.name, eventData.params );

        }

    }

    var clone = function (src, dest) {
        for (var key in src) {
            dest[key] = src[key];
        }
        return dest;
    };

});