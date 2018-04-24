// Add functionality to Bolder Compare Products settings form
jQuery(document).ready(function($) {

    // Hide popup window on cancel
    jQuery(document).on('click', '.be-popup-container .cancel', function() {
        jQuery( '.be-compare-popup' ).remove();
        return false;
    });

    // Register Purchase Code
    jQuery('#be-manage-plugins').on('click', 'a.button.register', function(e){

        //prevent default action (hyperlink)
        e.preventDefault();
        
        plugin_id = jQuery( this ).parent().parent().parent().parent().attr( 'plugin-id' );

        var create_form = 
        '<div id="be-register-purchase-code" class="be-compare-popup">' +
            '<div class="be-popup-container add_form" id="be-register-purchase-code-form">' +
                '<h1>' + be_config_data.text_activate_license + '</h1>' +
                '<p class="header_description">' + be_config_data.text_activate_desc + '</p>' +
                '<form method="post">' +
                '<input type="hidden" name="pid" id="pid" value="' + plugin_id + '" />' +
                '<p><label for="envato_username">' + be_config_data.text_envato_username + '</label></p>' +
                '<p><input type="text" name="envato_username" id="envato_username" /></p>' +
                '<p><label for="envato_purchase_code">' + be_config_data.text_purchase_code + '</label></p>' +
                '<p><input type="text" name="envato_purchase_code" id="envato_purchase_code" /></p>' +
                '<p><label for="envato_api_key">' + be_config_data.text_api_key + ' [<a href="http://themeforest.net/help/api" target="_blank">?</a>]</label></p>' +
                '<p><input type="text" name="envato_api_key" id="envato_api_key" /></p>' +
                '<p><input type="submit" name="submit" value="' + be_config_data.text_register + '" class="form_submit" /> <a href="#" class="cancel">' + be_config_data.text_cancel + '</a></p>' +
                '</form>' +
            '</div>' +  
        '</div>';
            
        //insert lightbox HTML into page
        jQuery('body').append(create_form);
        becpwc_doBoxSize();
        
    });

    // Process form fields from registering purchase code
    jQuery(document).on('submit', '#be-register-purchase-code-form form', function(e){
        e.preventDefault();

        var pluginID = jQuery( '#be-register-purchase-code #pid' ).val();
        var username = jQuery( '#be-register-purchase-code #envato_username' ).val();
        var purchase = jQuery( '#be-register-purchase-code #envato_purchase_code' ).val();
        var api_key = jQuery( '#be-register-purchase-code #envato_api_key' ).val();

        if ( pluginID && username && purchase && api_key ) {
            //$( this ).block({ message: null, overlayCSS: { background: '#fff url(' + be_config_data.ajax_loader_url + ') no-repeat center', backgroundSize: '32px 32px', opacity: 0.6 } });

            var data = { action: 'be_config_register_plugin', pid: pluginID, env_username: username, env_purchase_code: purchase, env_api_key: api_key };
            
            $.post( ajaxurl, data, function( response ) {

                // remove any existing error messages
                jQuery( '#be-register-purchase-code .error' ).remove();

                if( response.indexOf( '<div class="error">' ) >= 0 )
                    jQuery( response ).insertAfter( '#be-register-purchase-code .header_description' );

                else {
                    var screen_meta = jQuery( '#screen-meta' ).html();
                    jQuery( '#wpbody-content' ).html( response );
                    jQuery( '#wpbody-content' ).prepend( '<div id="screen-meta" class="metabox-prefs">' + screen_meta + '</div>' );
                    var message = '<h1 style="color: green;">' + be_config_data.text_success + '!</h1><p><a href="#" class="cancel">' + be_config_data.text_close + '</a></p>';
                    jQuery( '#be-register-purchase-code form' ).replaceWith( message );
                }

            });
        } else {
            jQuery( '<div class="error"><p>' + be_config_data.error_incomplete_form + '</p></div>' ).insertAfter( '#be-register-purchase-code .header_description' );
            if( !username ) jQuery("label[for='envato_username']").css( 'color', 'red' );
            if( !purchase ) jQuery("label[for='envato_purchase_code']").css( 'color', 'red' );
            if( !api_key ) jQuery("label[for='envato_api_key']").css( 'color', 'red' );
        }

        return false;
    });

    // Delete Plugin Settings (Confirmation Box)
    jQuery('#be-registered-plugins').on('click', 'a.button.remove', function(e){

        //prevent default action (hyperlink)
        e.preventDefault();
        
        plugin_id = jQuery( this ).parent().parent().parent().parent().attr( 'plugin-id' );

        var create_form = 
        '<div id="be-remove-purchase-code" class="be-compare-popup">' +
            '<div class="be-popup-container add_form" id="be-remove-purchase-code-form">' +
                '<p class="header_description" style="display:none;"></p>' +
                '<form method="post">' +
                '<input type="hidden" name="plugin_id" id="plugin_id" value="' + plugin_id + '" />' +
                '<p><label>' + be_config_data.text_remove_plugin + '?</label></p>' +
                '<p><span><strong>' + be_config_data.text_remove_plugin_desc + '!</strong></span></p>' +
                '<p><input type="submit" name="submit" value="' + be_config_data.text_remove + '" class="form_submit" /> <a href="#" class="cancel">' + be_config_data.text_cancel + '</a></p>' +
                '</form>' +
            '</div>' +  
        '</div>';
            
        //insert lightbox HTML into page
        jQuery('body').append(create_form);
        becpwc_doBoxSize();
        
    });

    // Delete Settings Information (Process Changes)
    jQuery(document).on('submit', '#be-remove-purchase-code-form form', function(e){
        e.preventDefault();

        var pluginID = jQuery( '#be-remove-purchase-code #plugin_id' ).val();

        if ( pluginID ) {

            //$( this ).block({ message: null, overlayCSS: { background: '#fff url(' + be_config_data.ajax_loader_url + ') no-repeat center', backgroundSize: '32px 32px', opacity: 0.6 } });

            var data = { action: 'be_config_remove_plugin', pid: pluginID };
            
            $.post( ajaxurl, data, function( response ) {

                // remove any existing error messages
                jQuery( '#be-register-purchase-code .error' ).remove();

                if( response.indexOf( '<div class="error">' ) >= 0 )
                    jQuery( response ).insertAfter( '#be-register-purchase-code .header_description' );

                else {
                    var screen_meta = jQuery( '#screen-meta' ).html();
                    jQuery( '#wpbody-content' ).html( response );
                    jQuery( '#wpbody-content' ).prepend( '<div id="screen-meta" class="metabox-prefs">' + screen_meta + '</div>' );
                    var message = '<h1 style="color: green;">' + be_config_data.text_success + '!</h1><p><a href="#" class="cancel">' + be_config_data.text_close + '</a></p>';
                    jQuery( '#be-remove-purchase-code form' ).replaceWith( message );
                }

            });
        } else {
            jQuery( '<div class="error"><p>' + be_config_data.error_incomplete_form + '</p></div>' ).insertAfter( '#be-remove-purchase-code .header_description' );
        }

        return false;
    });

    // Create confirmation form for bulk deleting features
    jQuery('#be-compare-feat-table').on('click', '.button.action', function(e){
        e.preventDefault();

        var action = jQuery( this ).parent().find( 'select' ).val();
        var checkboxes = [];

        if( action === 'delete' ) {

            // Get checkbox data
            jQuery( '#be-compare-feat-table table tbody' ).find( ':checkbox:checked' ).each( function( index ) {
                checkboxes.push( jQuery(this).attr('value') );
            });

            var create_form = 
            '<div id="be-compare-delete-features" class="be-compare-popup">' +
                '<div class="be-popup-container add_form" id="be-compare-delete-features-form">' +
                    '<form method="post">' +
                    '<input type="hidden" name="feat_ids" id="feat_ids" value="' + checkboxes.toString() + '" />' +
                    '<p><label for="cat_id">' + be_config_data.text_del_features + '?</label></p>' +
                    '<p><span><strong>' + be_config_data.text_del_category_desc + '!</strong></span></p>' +
                    '<p><input type="submit" name="submit" value="' + be_config_data.text_delete + '" class="form_submit" /> <a href="#" class="cancel">' + be_config_data.text_cancel + '</a></p>' +
                    '</form>' +
                '</div>' +  
            '</div>';
                
            //insert lightbox HTML into page
            jQuery('body').append(create_form);
            becpwc_doBoxSize();

        }
    });

    function becpwc_doBoxSize() {
        // set max height for popup box
        var window_height = jQuery( window ).height();
        var box_height = window_height - 180;
        jQuery( '.be-popup-container' ).css( 'max-height', box_height +'px' );
    } jQuery( window ).on( 'resize', function() { becpwc_doBoxSize(); });

});
