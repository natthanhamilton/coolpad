jQuery( document ).ready( function( $ ) {

	/**
	* Open image in a new browser tab when download icon clicked
	* 
	* If Password Protection is enabled, request a password when the download button
	* is clicked.
	*/
	$( document ).on( 'click', '.envira-download-button a', function( e ) {

		e.preventDefault();

		// Is this a dynamic gallery?
		if ( $( this ).closest( '.envira-gallery-wrap' ).hasClass('envira-dynamic-gallery') ) {
			// Gallery + Dynamic
			var gallery_id = 'dynamic';
			var url = $( this ).attr( 'href' );
			var is_dynamic = 'true';
		} else if ( $( this ).closest( '.envirabox-wrap' ).hasClass('envira-dynamic-gallery') ) {
			// Lightbox + Dynamic
			var image 			= $( 'img.envirabox-image' ).attr( 'src' ),
				force_download 	= $( this ).parent().attr( 'data-envira-downloads-force-download' ),
				gallery_id 		= $( 'img.envirabox-image' ).data( 'envira-gallery-id' ),
				gallery_item_id = $( 'img.envirabox-image' ).data( 'envira-item-id' );

			var is_dynamic = 'true';

			// Build URL based on whether we're forcing a download or not
			if ( force_download == '1' ) {
				url = location.protocol + '//' + location.host + location.pathname + '?envira-downloads-gallery-id=' + gallery_id + '&envira-downloads-gallery-image=' + gallery_item_id + '&envira-dynamic=1';
			} else {
				url = image;
			}
		} else if ( $( '.envirabox-overlay' ).css( 'display' ) == 'block' ) {
			// Get image based on whether we're in the lightbox or not
			// Lightbox
			var image 			= $( 'img.envirabox-image' ).attr( 'src' ),
				force_download 	= $( this ).parent().attr( 'data-envira-downloads-force-download' ),
				gallery_id 		= $( 'img.envirabox-image' ).data( 'envira-gallery-id' ),
				gallery_item_id = $( 'img.envirabox-image' ).data( 'envira-item-id' );

			// Build URL based on whether we're forcing a download or not
			if ( force_download == '1' ) {
				url = location.protocol + '//' + location.host + location.pathname + '?envira-downloads-gallery-id=' + gallery_id + '&envira-downloads-gallery-image=' + gallery_item_id;
			} else {
				url = image;
			}
		} else {
			// Gallery
			var url = $( this ).attr( 'href' ),
				gallery_id = $( this ).closest( '.envira-gallery-public' ).attr( 'id' ).split( '-' )[2];
		}
		
		// If Password Protection is enabled, prompt for a password before sending it as part of the request
		if ( envira_downloads.password_protection ) {
			// Check if a cookie for this gallery ID already exists with a password
			if ( is_dynamic ) {
				var cookie_password = envira_downloads_get_cookie( 'envira_password_protection_download_dynamic' );
			} else {
				var cookie_password = envira_downloads_get_cookie( 'envira_password_protection_download_' + gallery_id );
			}
			if ( cookie_password == '' ) {
				// Fallback to user requested password
				var password = prompt( envira_downloads.password_required );
				if ( password == '' ) {
					return false;
				}
			} else {
				password = cookie_password;
			}

			if ( password == null ) {
				return false;
			}

			// Append password to URL
			url += '&envira_password_protection_download=' + password;

		}
		if ( force_download == '1' ) {
			window.open( url, '_self' );
		}else{
			
			// Open Window
			window.open( url, '_blank' );
			
		}
		
		return false;
	} );



	$( document ).on( 'click', 'a.envira-download-all', function( e ) {

		e.preventDefault();

		// Get the URL from the link
		var url = $( this ).attr( 'href' );
		
		// Is this a dynamic gallery?
		if ( $( this ).closest( '.envira-gallery-wrap' ).hasClass('envira-dynamic-gallery') ) {
			var gallery_id = 'dynamic';
			var url = $( this ).attr( 'href' );
			var is_dynamic = 'true';
		} else {
			// Extracting the gallery_id from the url ensures we are getting it correctly and not searching through the DOM	
			var gallery_id = envira_downloads_getParameterByName( 'envira-downloads-gallery-id', url );
			if ( gallery_id == null ) {
				return false;
			}
		}

		// If Password Protection is enabled, prompt for a password before sending it as part of the request
		if ( envira_downloads.password_protection ) {
			// Check if a cookie for this gallery ID already exists with a password
			var cookie_password = envira_downloads_get_cookie( 'envira_password_protection_download_' + gallery_id );
			if ( cookie_password == '' ) {
				// Fallback to user requested password
				var password = prompt( envira_downloads.password_required );
				if ( password == '' ) {
					return false;
				}
			} else {
				password = cookie_password;
			}

			if ( password == null ) {
				return false;
			}

			// Append password to URL
			url += '&envira_password_protection_download=' + password;
		}

		// Open Window
		window.open( url, '_self' );
		return false;
	} );

	if ( ! $('div.envira-gallery-wrap').hasClass('envira-lightbox-theme-base_light') && ! $('div.envira-gallery-wrap').hasClass('envira-lightbox-theme-base_dark') ) {

		$( document ).on( 'mouseenter', 'div.envira-gallery-item-inner', function() {
			$( 'div.envira-download-button', $( this ) ).fadeIn().css('display', 'inline-block');
		} );

		$( document ).on( 'mouseleave', 'div.envira-gallery-item-inner', function() {
			$( 'div.envira-download-button', $( this ) ).hide();
		} );

	} else {

		$( 'div.envira-download-button', $( this ) ).fadeIn().css('display', 'inline-block');
		
	}





} );

/**
 * Get a cookie by its name
 *
 * @since 1.0.0
 *
 * @param 	string 	cname 	Cookie Name
 * @return 	string 			Cookie Value
 */
function envira_downloads_get_cookie( cname ) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


function envira_downloads_getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}