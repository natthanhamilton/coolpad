<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Get the Gallery Object.
 * 
 * @since 1.7.0
 *
 * @access public
 * @param mixed $gallery_id
 * @return void
 */
function envira_get_gallery( $gallery_id ){
	
	// Attempt to return the transient first, otherwise generate the new query to retrieve the data.
	if ( false === ( $gallery = get_transient( '_eg_cache_' . $gallery_id ) ) ) {
		$gallery = _envira_get_gallery( $gallery_id );
		if ( $gallery ) {
			$expiration = envira_get_transient_expiration_time();
			set_transient( '_eg_cache_' . $gallery_id, $gallery, $expiration );
		}
	}
	
	// Return the gallery data.
	return $gallery;
	
}

/**
 * Internal method that returns a gallery based on ID.
 *
 * @since 1.7.0
 *
 * @param int $id	  The gallery ID used to retrieve a gallery.
 * @return array|bool Array of gallery data or false if none found.
 */
function _envira_get_gallery( $gallery_id ){
	
	$meta = get_post_meta( $gallery_id, '_eg_gallery_data', true );
	
	/**
	* Version 1.2.1+: Check if $meta has a value - if not, we may be using a Post ID but the gallery
	* has moved into the Envira CPT
	*/
	if ( empty( $meta ) ) {
		$gallery_id = get_post_meta( $gallery_id, '_eg_gallery_id', true );
		$meta = get_post_meta( $gallery_id, '_eg_gallery_data', true );
	}
	
	return $meta;
	
}

/**
 * envira_get_gallery_by_slug function.
 *
 * @since 1.7.0
 *
 * @access public
 * @param mixed $slug
 * @return void
 */
function envira_get_gallery_by_slug( $slug ){
	
	// Attempt to return the transient first, otherwise generate the new query to retrieve the data.
	if ( false === ( $gallery = get_transient( '_eg_cache_' . $slug ) ) ) {
		
		$gallery = _envira_get_gallery_by_slug( $slug );
		
		if ( $gallery ) {
			$expiration = envira_get_transient_expiration_time();
			set_transient( '_eg_cache_' . $slug, $gallery, $expiration );
		}
	}
	
	// Return the gallery data.
	return $gallery;

}

/**
 * _envira_get_gallery_by_slug function.
 *
 * @since 1.7.0
 *
 * @access private
 * @param mixed $slug
 * @return void
 */
function _envira_get_gallery_by_slug( $slug ){
	
	// Get Envira CPT by slug.
	$galleries = new WP_Query( array(
		'post_type' 	=> 'envira',
		'name' 			=> $slug,
		'fields'		  => 'ids',
		'posts_per_page' => 1,
	) );
	
	if ( $galleries->posts ) {
		return get_post_meta( $galleries->posts[0], '_eg_gallery_data', true );
	}
	
	// Get Envira CPT by meta-data field (yeah this is an edge case dealing with slugs in shortcode and modified slug in the misc tab of the gallery).
	$galleries = new WP_Query( array(
		'post_type' 	=> 'envira',
		'meta_key' 		=> 'envira_gallery_slug',
		'meta_value' 	=> $slug,
		'fields'		  => 'ids',
		'posts_per_page' => 1,
	) );
	
	if ( $galleries->posts ) {
		return get_post_meta( $galleries->posts[0], '_eg_gallery_data', true );
	}
	
	// If nothing found, get Envira CPT by _eg_gallery_old_slug.
	// This covers Galleries migrated from Pages/Posts --> Envira CPTs.
	$galleries = new WP_Query( array(
		'post_type'	  => 'envira',
		'no_found_rows' => true,
		'cache_results' => false,
		'fields'		  => 'ids',
		'meta_query'	  => array(
			array(
				'key'	=> '_eg_gallery_old_slug',
				'value'	=> $slug,
			),
		),
		'posts_per_page' => 1,
	) );
	
	if ( $galleries->posts ) {
		return get_post_meta( $galleries->posts[0], '_eg_gallery_data', true );
	}
	
	// No galleries found.
	return false;
	
}

/**
 * envira_get_galleries function.
 *
 * @since 1.7.0
 *
 * @access public
 * @param bool $skip_empty (default: true)
 * @param bool $ignore_cache (default: false)
 * @param string $search_terms (default: '')
 * @return void
 */
function envira_get_galleries( $skip_empty = true, $ignore_cache = false, $search_terms = '' ){
	// Attempt to return the transient first, otherwise generate the new query to retrieve the data.
	if ( $ignore_cache || ! empty( $search_terms ) || false === ( $galleries = get_transient( '_eg_cache_all' ) ) ) {
		$galleries = _envira_get_galleries( $skip_empty, $search_terms );
	
		// Cache the results if we're not performing a search and we have some results
		if ( $galleries && empty( $search_terms ) ) {
			$expiration = envira_get_transient_expiration_time();
			set_transient( '_eg_cache_all', $galleries, $expiration );
		}
	}
	
	// Return the gallery data.
	return $galleries;
}

/**
 * _envira_get_galleries function.
 *
 * @since 1.7.0
 *
 * @access private
 * @return void
 */
function _envira_get_galleries( $skip_empty = true, $search_terms = '' ){
	
	// Build WP_Query arguments.
	$args = array(
		'post_type'	  => 'envira',
		'post_status'	  => 'publish',
		'posts_per_page'=> 99,
		'no_found_rows' => true,
		'fields'		  => 'ids',
		'meta_query'	  => array(
			array(
				'key'	=> '_eg_gallery_data',
				'compare' => 'EXISTS',
			),
		),
	);
	
	// If search terms exist, add a search parameter to the arguments.
	if ( ! empty( $search_terms ) ) {
		$args['s'] = $search_terms;
	}
	
	// Run WP_Query.
	$galleries = new WP_Query( $args );
	
	if ( ! isset( $galleries->posts ) || empty( $galleries->posts ) ) {
		return false;
	}
	
	// Now loop through all the galleries found and only use galleries that have images in them.
	$ret = array();
	foreach ( $galleries->posts as $id ) {
		$data = get_post_meta( $id, '_eg_gallery_data', true );
	
		// Skip empty galleries.
		if ( $skip_empty && empty( $data['gallery'] ) ) {
			continue;
		}
	
		// Skip default/dynamic gallery types.
		$type = envira_get_config( 'type', $data );
		if ( 'defaults' === envira_get_config( 'type', $data ) || 'dynamic' === envira_get_config( 'type', $data )  ) {
			continue;
		}
	
		// Add gallery to array of galleries.
		$ret[] = $data;
	}
	
	// Return the gallery data.
	return $ret;
	
}
/**
 * envira_get_gallery_image_count function.
 *
 * @since 1.7.0
 *
 * @access public
 * @param mixed $gallery_id
 * @return void
 */
function envira_get_gallery_image_count( $gallery_id ){

	$gallery = get_post_meta( $gallery_id , '_eg_gallery_data', true );
	
	return ( isset( $gallery['gallery'] ) ? count( $gallery['gallery'] ) : 0 );
		
}

/**
 * Helper method for setting default config values.
 *
 * @since 1.7.0
 *
 * @global int $id		The current post ID.
 * @global object $post The current post object.
 * @param string $key	The default config key to retrieve.
 * @return string		Key value on success, false on failure.
 */
function envira_get_config_default( $key ) {

	global $id, $post;

	// Get the current post ID. If ajax, grab it from the $_POST variable.
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_POST['post_id'] ) ) {
		$post_id = absint( $_POST['post_id'] );
	} else {
		$post_id = isset( $post->ID ) ? $post->ID : (int) $id;
	}

	// Prepare default values.
	$defaults = envira_get_config_defaults( $post_id );

	// Return the key specified.
	return isset( $defaults[$key] ) ? $defaults[$key] : false;

}
/**
 * Helper method for retrieving config values.
 *
 * @since 1.0.0
 *
 * @global int $id		  The current post ID.
 * @global object $post	  The current post object.
 * @param string $key		The config key to retrieve.
 * @param string $default A default value to use.
 * @return string			 Key value on success, empty string on failure.
 */
function envira_get_config( $key, $data, $default = '' ) {

	if ( !is_array( $data ) ){
		
		return envira_get_config_default( $key );
		
	}

	$is_mobile_keys = array();

	// If we are on a mobile device, some config keys have mobile equivalents, which we need to check instead
	if ( envira_mobile_detect()->isMobile() ) {
		$is_mobile_keys = array(
			'lightbox_enabled'	 => 'mobile_lightbox',
			'arrows'			 => 'mobile_arrows',
			'toolbar'			 => 'mobile_toolbar',
			'thumbnails'		 => 'mobile_thumbnails',
			'thumbnails_width'	 => 'mobile_thumbnails_width',
			'thumbnails_height'	 => 'mobile_thumbnails_height',
		);

		$is_mobile_keys = apply_filters( 'envira_gallery_get_config_mobile_keys', $is_mobile_keys );

		// // When on mobile, use used to blindly look at the mobile_social option to determine social sharing button output
		// // However, what we need to do is look to see if the settings are active on the addon first
		// if ( array_key_exists( 'social', $is_mobile_keys ) || array_key_exists( 'social_lightbox', $is_mobile_keys ) ) {

		// 	if ( empty( $data['config']['social'] ) ) {
		// 		unset( $is_mobile_keys['social'] );
		// 	}
		// 	if ( empty( $data['config']['social_lightbox'] ) ) {
		// 		unset( $is_mobile_keys['social_lightbox'] );
		// 	}
		// }

		if ( array_key_exists( $key, $is_mobile_keys ) ) {
			// Use the mobile array key to get the config value
			$key = $is_mobile_keys[ $key ];
		}

	}

	// We need supersize for the base dark theme, so we are forcing it here
	if ( $key == 'supersize' && isset( $data['config'] ) && $data['config']['lightbox_theme'] == 'base_dark' ) {
		$data['config'][ $key ] = 1;
	}

	// The toolbar is not needed for base dark so lets disable it
	if ( $key == 'toolbar' && $data['config']['lightbox_theme'] == 'base_dark' ) {
		$data['config'][ $key ] = 0;
	}

	if ( isset( $data['config'] ) ) {
			$data['config'] = apply_filters( 'envira_gallery_get_config', $data['config'], $key );
	} else {
		$data['config'][$key] = false;
	}

	$value = isset( $data['config'][$key] ) ? $data['config'][$key] : envira_get_config_default( $key );
	
	return $value;

}


function envira_get_gallery_data( $gallery_id ){
	
	//If no ID is set create a new gallery
	if ( !isset( $gallery_id ) ){
	
		return false;
	}
			
	$data = get_metadata( 'eg_gallery_data', $gallery_id );
		
	return $data;
		
}

/**
 * envira_get_gallery_images function.
 *
 * @since 1.7.0
 *
 * @access public
 * @param mixed $gallery_id
 * @return void
 */
function envira_get_gallery_images( $gallery_id ){
	
	$data = envira_get_gallery_data( $gallery_id );
	
	return $data['galleries'];
	
}

/**
 * Helper function to prepare the metadata for an image in a gallery.
 *
 * @since 1.7.0
 *
 * @param array $gallery_data	Array of data for the gallery.
 * @param int	$id				The attachment ID to prepare data for.
 * @param array $image			Attachment image. Populated if inserting from the Media Library
 * @return array $gallery_data Amended gallery data with updated image metadata.
 */
function envira_prepare_gallery_data( $gallery_data, $id, $image = false ) {

	// Get attachment
	$attachment = get_post( $id );

	// Depending on whether we're inserting from the Media Library or not, prepare the image array
	if ( ! $image ) {
		$url		= wp_get_attachment_image_src( $id, 'full' );
		$alt_text	= get_post_meta( $id, '_wp_attachment_image_alt', true );
		$new_image = array(
			'status'	 => 'active',
			'src'		=> isset( $url[0] ) ? esc_url( $url[0] ) : '',
			'title'		 => get_the_title( $id ),
			'link'		 => ( isset( $url[0] ) ? esc_url( $url[0] ) : '' ),
			'alt'		=> ! empty( $alt_text ) ? $alt_text : '',
			'caption' => ! empty( $attachment->post_excerpt ) ? $attachment->post_excerpt : '',
			'thumb'		 => ''
		);
	} else {
		$new_image = array(
			'status'	 => 'active',
			'src'		=> ( isset( $image['src'] ) ? $image['src'] : $image['url'] ),
			'title'		 => $image['title'],
			'link'		 => $image['link'],
			'alt'		=> $image['alt'],
			'caption' => $image['caption'],
			'thumb'		 => '',
		);
	}

	// Allow Addons to possibly add metadata now
	$image = apply_filters( 'envira_gallery_ajax_prepare_gallery_data_item', $new_image, $image, $id, $gallery_data );

	// If gallery data is not an array (i.e. we have no images), just add the image to the array
	if ( ! isset( $gallery_data['gallery'] ) || ! is_array( $gallery_data['gallery'] ) ) {
		$gallery_data['gallery'] = array();
		$gallery_data['gallery'][ $id ] = $image;
	} else {
		// Add this image to the start or end of the gallery, depending on the setting
		$media_position = $this->get_setting( 'media_position' );

		switch ( $media_position ) {
			case 'before':
				// Add image to start of images array
				// Store copy of images, reset gallery array and rebuild
				$images = $gallery_data['gallery'];
				$gallery_data['gallery'] = array();
				$gallery_data['gallery'][ $id ] = $image;
				foreach ( $images as $old_image_id => $old_image ) {
					$gallery_data['gallery'][ $old_image_id ] = $old_image;
				}
				break;
			case 'after':
			default:
				// Add image, this will default to the end of the array
				$gallery_data['gallery'][ $id ] = $image;
				break;
		}
	}

	// Filter and return
	$gallery_data = apply_filters( 'envira_gallery_ajax_item_data', $gallery_data, $attachment, $id, $image );

	return $gallery_data;

}

add_filter('envira_gallery_pre_data', 'envira_insure_random_gallery', 10, 2 );
/**
 * Helper function to ensure random galleries bypass cache and are displayed randomly on the front end
 *
 * @since 1.7.0
 *
 * @param array $data			Array of data for the gallery.
 * @param int	$gallery_id		The attachment ID to prepare data for.
 * @return array $data 			Updated gallery data
 */
function envira_insure_random_gallery( $data, $gallery_id ) {
	if ( !$data || !isset( $data['config']['sort_order'] ) || $data['config']['sort_order'] != 1 ) {
		return $data;
	}
	$data = envira_sort_gallery( $data, '1', 'DESC' ); //'1' = random
	return $data;
}

add_filter('envira_gallery_get_transient_markup', 'envira_maybe_clear_cache_random', 10, 2 );
/**
 * Helper function to ensure random galleries bypass cache and are displayed randomly on the front end
 *
 * @since 1.7.0
 *
 * @param array $transient	Transient
 * @param int	$data		Array of data for the gallery.
 * @return boolean 			Allow cache or not
 */
function envira_maybe_clear_cache_random( $transient, $data ) {
	if ( !$data || !isset( $data['config']['sort_order'] ) || $data['config']['sort_order'] != 1 ) {
		return $transient;
	} else {
		return false;
	}
}



// Conditionally load the template tag.
if ( ! function_exists( 'envira_gallery' ) ) {
	
	/**
	 * Primary template tag for outputting Envira galleries in templates.
	 *
	 * @since 1.0.0
	 *
	 * @param int	  $id 		The ID of the gallery to load.
	 * @param string $type	   	The type of field to query.
	 * @param array  $args			Associative array of args to be passed.
	 * @param bool	  $return	 Flag to echo or return the gallery HTML.
	 */
	function envira_gallery( $id, $type = 'id', $args = array(), $return = false ) {
	
		// If we have args, build them into a shortcode format.
		$args_string = '';
		
		if ( ! empty( $args ) ) {
		
			foreach ( (array) $args as $key => $value ) {
		
				$args_string .= ' ' . $key . '="' . $value . '"';
		
			}
		
		}
	
		// Build the shortcode.
		$shortcode = ! empty( $args_string ) ? '[envira-gallery ' . $type . '="' . $id . '"' . $args_string . ']' : '[envira-gallery ' . $type . '="' . $id . '"]';
	
		// Return or echo the shortcode output.
		if ( $return ) {
			
			return do_shortcode( $shortcode );
		} else {
	
			echo do_shortcode( $shortcode );
	
		}
	
	}
}