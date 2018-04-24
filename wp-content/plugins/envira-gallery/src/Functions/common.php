<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

function envira_crop_position(){
	
	$postition = array(
		 array(
			 'value' => 'c',
			 'name'	 => __( 'Center', 'envira-gallery' )
		 ),
		 array(
			 'value' => 'tl',
			 'name'	 => __( 'Top Left', 'envira-gallery' )
		 ),
		 array(
			 'value' => 'tr',
			 'name'	 => __( 'Top Right', 'envira-gallery' )
		 ),
		 array(
			 'value' => 'bl',
			 'name'	 => __( 'Bottom Left', 'envira-gallery' )
		 ),
		 array(
			 'value' => 'br',
			 'name'	 => __( 'Bottom Right', 'envira-gallery' )
		 )
	);
	
	return apply_filters( 'envira_crop_positions', $postition );
}

/**
 * Helper method for retrieving columns.
 *
 * @since 1.0.0
 *
 * @return array Array of column data.
*/	
function envira_get_columns(){
	
	$columns = array(
		 array(
			 'value' => '0',
			 'name'	 => __( 'Automatic', 'envira-gallery' )
		 ),
		 array(
			 'value' => '1',
			 'name'	 => __( 'One Column (1)', 'envira-gallery' )
		 ),
		 array(
			 'value' => '2',
			 'name'	 => __( 'Two Columns (2)', 'envira-gallery' )
		 ),
		 array(
			 'value' => '3',
			 'name'	 => __( 'Three Columns (3)', 'envira-gallery' )
		 ),
		 array(
			 'value' => '4',
			 'name'	 => __( 'Four Columns (4)', 'envira-gallery' )
		 ),
		 array(
			 'value' => '5',
			 'name'	 => __( 'Five Columns (5)', 'envira-gallery' )
		 ),
		 array(
			 'value' => '6',
			 'name'	 => __( 'Six Columns (6)', 'envira-gallery' )
		 )
	);
	
	return apply_filters( 'envira_gallery_columns', $columns );
	
}

/**
 * Helper method for retrieving gallery themes.
 *
 * @since 1.0.0
 *
 * @return array Array of gallery theme data.
 */
function envira_get_gallery_themes(){
	
	$themes = array(
		 array(
			 'value' => 'base',
			 'name'	 => __( 'Base', 'envira-gallery' ),
			 'file'	 => ENVIRA_FILE
		 )
	);
	
	return apply_filters( 'envira_gallery_gallery_themes', $themes );
	
}

/**
 * Helper method for retrieving justified gallery themes.
 *
 * @since 1.1.1
 *
 * @return array Array of gallery theme data.
 */
function envira_get_justified_gallery_themes() {

	$themes = array(
		array(
			'value' => 'normal',
			'name'	=> __( 'Normal', 'envira-gallery' ),
			'file'	=> ENVIRA_FILE
		)
	);

	return apply_filters( 'envira_gallery_justified_gallery_themes', $themes );

}

/**
 * Helper method for retrieving justified gallery themes.
 *
 * @since 1.1.1
 *
 * @return array Array of gallery theme data.
 */
function envira_get_justified_gallery_themes_details() {

	$details = array(
		array(
			'value' => 'normal',
			'name'	=> __( 'Normal', 'envira-gallery' ),
			'file'	=> ENVIRA_FILE
		),
		array(
			'value' => 'hover',
			'name'	=> __( 'On Hover', 'envira-gallery' ),
			'file'	=> ENVIRA_FILE
		)
	);

	return apply_filters( 'envira_gallery_justified_gallery_themes_details', $details );

}

/**
 * Helper method for retrieving display description options.
 *
 * @since 1.3.7.3
 *
 * @return array Array of description placement options.
 */
function envira_get_display_description_options() {

	$descriptions = array(
		array(
			'name'	=> __( 'Do not display', 'envira-gallery' ),
			'value' => 0,
		),
		array(
			'name'	=> __( 'Display above galleries', 'envira-gallery' ),
			'value' => 'above',
		),
		array(
			'name'	=> __( 'Display below galleries', 'envira-gallery' ),
			'value' => 'below',
		),
	);

	return apply_filters( 'envira_gallery_display_description_options', $descriptions );

}

/**
 * Helper method for retrieving display sorting options.
 *
 * @since 1.3.8
 *
 * @return array Array of sorting options
 */
function envira_get_sorting_options() {

	$options = array(
		array(
			'name'	=> __( 'No Sorting', 'envira-gallery' ),
			'value' => 0,
		),
		array(
			'name'	=> __( 'Random', 'envira-gallery' ),
			'value' => 1, // Deliberate, as we map the 'random' config key which was a true/false
		),
		array(
			'name'	=> __( 'Published Date', 'envira-gallery' ),
			'value' => 'date',
		),
		array(
			'name'	=> __( 'Filename', 'envira-gallery' ),
			'value' => 'src',
		),
		array(
			'name'	=> __( 'Title', 'envira-gallery' ),
			'value' => 'title',
		),
		array(
			'name'	=> __( 'Caption', 'envira-gallery' ),
			'value' => 'caption',
		),
		array(
			'name'	=> __( 'Alt', 'envira-gallery' ),
			'value' => 'alt',
		),
		array(
			'name'	=> __( 'URL', 'envira-gallery' ),
			'value' => 'link',
		),
	);

	return apply_filters( 'envira_gallery_sorting_options', $options );

}

/**
 * Helper method for retrieving sorting directions
 *
 * @since 1.3.8
 *
 * @return array Array of sorting directions
 */
function envira_get_sorting_directions() {

	$directions = array(
		array(
			'name'	=> __( 'Ascending (A-Z)', 'envira-gallery' ),
			'value' => 'ASC',
		),
		array(
			'name'	=> __( 'Descending (Z-A)', 'envira-gallery' ),
			'value' => 'DESC',
		),
	);

	return apply_filters( 'envira_gallery_sorting_directions', $directions );

}

/**
 * Helper method for retrieving lightbox themes.
 *
 * @since 1.0.0
 *
 * @return array Array of lightbox theme data.
 */
function envira_get_lightbox_themes() {

	$themes = array(
		array(
			'value' => 'base_dark',
			'name'	=> __( 'Base (Dark)', 'envira-gallery' ),
			'file'	=> ENVIRA_FILE
		)
	);

	$themes = apply_filters( 'envira_gallery_lightbox_themes', $themes );

	$themes[] = array(
		'value' => 'base',
		'name'	=> __( 'Legacy', 'envira-gallery' ),
		'file'	=> ENVIRA_FILE
	);

	//$themes = apply_filters( 'envira_gallery_lightbox_themes', $themes );

	return $themes;

}

/**
 * Helper method for retrieving title displays.
 *
 * @since 1.0.0
 *
 * @return array Array of title display data.
 */
function envira_get_title_displays() {

	$displays = array(
		array(
			'value' => 'float',
			'name'	=> __( 'Float', 'envira-gallery' )
		),
		array(
			'value' => 'float_wrap',
			'name'	=> __( 'Float (Wrapped)', 'envira-gallery' )
		),
		array(
			'value' => 'inside',
			'name'	=> __( 'Inside', 'envira-gallery' )
		),
		array(
			'value' => 'outside',
			'name'	=> __( 'Outside', 'envira-gallery' )
		),
		array(
			'value' => 'over',
			'name'	=> __( 'Over', 'envira-gallery' )
		)
	);

	return apply_filters( 'envira_gallery_title_displays', $displays );

}

/**
 * Helper method for retrieving arrow positions.
 *
 * @since 1.3.3.7
 *
 * @return array Array of arrow position display data.
 */
function envira_get_arrows_positions() {

	$displays = array(
		array(
			'value' => 'inside',
			'name'	=> __( 'Inside', 'envira-gallery' )
		),
		array(
			'value' => 'outside',
			'name'	=> __( 'Outside', 'envira-gallery' )
		),
	);

	return apply_filters( 'envira_gallery_arrows_positions', $displays );

}

/**
 * Helper method for retrieving lightbox transition effects.
 *
 * @since 1.0.0
 *
 * @return array Array of transition effect data.
 */
function envira_get_transition_effects() {

	$effects = array(
		array(
			'value' => 'none',
			'name'	=> __( 'No Effect', 'envira-gallery' )
		),
		array(
			'value' => 'fade',
			'name'	=> __( 'Fade', 'envira-gallery' )
		),
		array(
			'value' => 'elastic',
			'name'	=> __( 'Elastic', 'envira-gallery' )
		),
	);

	return apply_filters( 'envira_gallery_transition_effects', $effects );

}

/**
 * Helper method for retrieving an array of lightbox transition effect values
 *
 * @since 1.4.1.2
 *
 * @return array Transition values
 */
function envira_get_transition_effects_values() {

	// Get effects
	$effects = envira_get_transition_effects();

	// Build array
	$effect_values = array();
	foreach ( $effects as $effect ) {
		$effect_values[] = $effect['value'];
	}

	// Return
	return apply_filters( 'envira_gallery_transition_effects_values', $effect_values, $effects );

}

/**
 * Helper method for retrieving lightbox easing transition effects.
 *
 * These are deliberately seperate from get_transition_effects() above, so that
 * we can determine whether an effect on a Gallery or Album is an easing one or not.
 *
 * In turn, that determines the setting keys used for Fancybox (e.g. openEffect vs openEasing)
 *
 * @since 1.4.1.2
 *
 * @return array Array of easing transition effects
 */
function envira_get_easing_transition_effects() {

	$effects = array(
		array(
			'value' => 'Swing',
			'name'	=> __( 'Swing', 'envira-gallery' )
		),
		// array(
		//		'value' => 'Quad',
		//		'name'	=> __( 'Quad', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Cubic',
		//		'name'	=> __( 'Cubic', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Quart',
		//		'name'	=> __( 'Quart', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Quint',
		//		'name'	=> __( 'Quint', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Sine',
		//		'name'	=> __( 'Sine', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Expo',
		//		'name'	=> __( 'Expo', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Circ',
		//		'name'	=> __( 'Circ', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Back',
		//		'name'	=> __( 'Back', 'envira-gallery' )
		// ),
		// array(
		//		'value' => 'Bounce',
		//		'name'	=> __( 'Bounce', 'envira-gallery' )
		// ),
	);

	return apply_filters( 'envira_gallery_easing_transition_effects', $effects );

}

/**
 * Helper method for retrieving toolbar positions.
 *
 * @since 1.0.0
 *
 * @return array Array of toolbar position data.
 */
function envira_get_toolbar_positions() {

	$positions = array(
		array(
			'value' => 'top',
			'name'	=> __( 'Top', 'envira-gallery' )
		),
		array(
			'value' => 'bottom',
			'name'	=> __( 'Bottom', 'envira-gallery' )
		)
	);

	return apply_filters( 'envira_gallery_toolbar_positions', $positions );

}

/**
 * Helper method for retrieving thumbnail positions.
 *
 * @since 1.0.0
 *
 * @return array Array of thumbnail position data.
 */
function envira_get_thumbnail_positions() {

	$positions = array(
		array(
			'value' => 'top',
			'name'	=> __( 'Top', 'envira-gallery' )
		),
		array(
			'value' => 'bottom',
			'name'	=> __( 'Bottom', 'envira-gallery' )
		)
	);

	return apply_filters( 'envira_gallery_thumbnail_positions', $positions );

}

/**
 * Helper method for retrieving Justified Last Row.
 *
 * @since 1.0.0
 *
 * @return array Array of last data.
 */
function envira_get_justified_last_row() {

	$layouts = array(
		array(
			'value' => 'nojustify',
			'name'	=> __( 'Left', 'envira-gallery' )
		),
		 array(
			'value' => 'center',
			'name'	=> __( 'Center', 'envira-gallery' )
		),
		 array(
			'value' => 'right',
			'name'	=> __( 'Right', 'envira-gallery' )
		),
		array(
			'value' => 'justify',
			'name'	=> __( 'Fill', 'envira-gallery' )
		),
		 array(
			'value' => 'hide',
			'name'	=> __( 'Hide', 'envira-gallery' )
		),
	);

	return apply_filters( 'envira_gallery_justified_last_row', $layouts );

}

/**
 * Helper method for retrieving additional copy options for legacy gallery views
 *
 * @since 1.0.0
 *
 * @return array Array of last data.
 */
function envira_get_additional_copy_options() {

	$options = array(
		'title'	 => __( 'Title', 'envira-gallery' ),
		'caption'	=> __( 'Caption', 'envira-gallery' )
	);

	return apply_filters( 'envira_gallery_get_additional_copy_options', $options );

}

/**
 * Retrieves the slider config defaults.
 *
 * @since 1.0.0
 *
 * @param int $post_id The current post ID.
 * @return array		Array of slider config defaults.
 */
function envira_get_config_defaults( $post_id ) {

	// Prepare default values.
	$defaults = array(
		// Images Tab
		'type'				  			=> 'default',

		// Config Tab
		'columns'			 			=> '0',
		'justified_row_height' 			=> 150, // automatic/justified layout
		'justified_gallery_theme' 		=> 'normal',
		'justified_margins' 			=> 1,
		'justified_last_row' 			=> 'nojustify',
		'justified_gallery_theme_detail' => '',
		'gallery_theme'		 			=> 'base',
		'display_description' 			=> 0,
		'description'			 		=> '',
		'gutter'			  			=> 10,
		'margin'			  			=> 10,
		'random'			  			=> 0,
		'sorting_direction'				=> 'ASC',
		'image_size'		  			=> 'default', // Default = uses the below crop_width and crop_height
		'image_sizes_random'			=> array(),
		'crop_width'		  			=> 640,
		'crop_height'		  			=> 480,
		'crop'				  			=> 0,
		'crop_position'				  	=> 'c',
		'dimensions'					=> 0,
		'isotope'						=> 1,
		'css_animations'	 			=> 1,
		'css_opacity'		  			=> 100,
		'lightbox_title_caption'		=> 'title',
		'additional_copy_title'			=> 0,
		'additional_copy_caption'		=> 0,
		'additional_copy_automatic_title'	=> 1,
		'additional_copy_automatic_caption' => 1,
		'lazy_loading'					=> 1, // lazy loading 'ON' for new galleries
		'lazy_loading_delay'			=> 500,
		'sort_order'					=> '0',

		// Lightbox
		'lightbox_enabled'	 			=> 1,
		'lightbox_theme'		 		=> 'base_dark',
		'lightbox_image_size' 			=> 'default',
		'title_display'			 		=> 'float',
		'arrows'			  			=> 1,
		'arrows_position'	  			=> 'inside',
		'keyboard'				 		=> 1,
		'mousewheel'		  			=> 1,
		'toolbar'						=> 1,
		'toolbar_title'			 		=> 0,
		'toolbar_position'	 			=> 'top',
		'aspect'			  			=> 1,
		'loop'				  			=> 1,
		'lightbox_open_close_effect' 	=> 'fade',
		'effect'			  			=> 'fade',
		'html5'				  			=> 0,
		'supersize'						=> 0,

		// Thumbnails
		'thumbnails'		  			=> 1,
		'thumbnails_width'	 			=> 75,
		'thumbnails_height'	 			=> 50,
		'thumbnails_position' 			=> 'bottom',

		// Mobile
	  //  'mobile_columns'		=> 1,
		'mobile'			  			=> 1,
		'mobile_width'					=> 320,
		'mobile_height'			 		=> 240,
		'mobile_lightbox'	  			=> 1,
		'mobile_touchwipe'	  			=> 1,
		'mobile_touchwipe_close' 		=> 0,
		'mobile_arrows'			 		=> 1,
		'mobile_toolbar'		 		=> 1,
		'mobile_thumbnails'				=> 1,
		'mobile_thumbnails_width'		=> 75,
		'mobile_thumbnails_height'		=> 50,
		'mobile_justified_row_height'	=> 80,

		// Misc
		'title'				 			=> '',
		'slug'							=> '',
		'classes'						=> array(),
		'rtl'							=> 0,
	);

	// Allow devs to filter the defaults.
	$defaults = apply_filters( 'envira_gallery_defaults', $defaults, $post_id );

	return $defaults;

}

/**
 * Returns an array of supported file type groups and file types
 *
 * @since 1.3.3.2
 *
 * @return array Supported File Types
 */
function envira_get_supported_filetypes() {

	$supported_file_types = array(
		array(
			'title'		=> __( 'Image Files', 'envira-gallery' ),
			'extensions'=> 'jpg,jpeg,jpe,gif,png,bmp,tif,tiff,JPG,JPEG,JPE,GIF,PNG,BMP,TIF,TIFF',
		),
	);

	// Allow Developers and Addons to filter the supported file types
	$supported_file_types = apply_filters( 'envira_gallery_supported_file_types', $supported_file_types );

	return $supported_file_types;
}

/**
 * Returns an array of support file types in full MIME format
 *
 * @since 1.4.0.2
 *
 * @return array Supported File Types
 */
function envira_get_supported_filetypes_mimes() {

	$supported_file_types = array(
		'image/jpg',
		'image/jpeg',
		'image/jpe',
		'image/gif',
		'image/png',
		'image/bmp',
		'image/tif',
		'image/tiff',
	);

	// Allow Developers and Addons to filter the supported file types
	$supported_file_types = apply_filters( 'envira_gallery_supported_file_types_mimes', $supported_file_types );

	return $supported_file_types;

}

/**
 * Returns an array of positions for new images to be added to in an existing Gallery
 *
 * @since 1.3.3.6
 *
 * @return array
 */
function envira_get_media_positions() {

	$positions = array(
		array(
			'value' => 'before',
			'name'	=> __( 'Before Existing Images', 'envira-gallery' )
		),
		array(
			'value' => 'after',
			'name'	=> __( 'After Existing Images', 'envira-gallery' )
		),
	);

	return apply_filters( 'envira_gallery_media_positions', $positions );

}

/**
 * Returns an array of media deletion options, when an Envira Gallery is deleted
 *
 * @since 1.3.5.1
 *
 * @return array
 */
function envira_get_media_delete_options() {

	$options = array(
		array(
			'value' => '',
			'name'	=> __( 'No', 'envira-gallery' )
		),
		array(
			'value' => '1',
			'name'	=> __( 'Yes', 'envira-gallery' )
		),
	);

	return apply_filters( 'envira_gallery_media_delete_options', $options );

}

/**
 * Sorts the gallery
 *
 * @since 1.5.9
 *
 * @return array
 */
function envira_sort_gallery( $data, $sort_type, $sort_direction ){
	//Return if we dont have a sort type
	if(  empty( $sort_type ) || empty( $sort_direction ) ){
		return $data;
	}
	//Dont go any further if datas not set.
	if ( empty( $data ) ){
		return;
	}

	// Update the sort type
	$data['config']['sort_order'] = $sort_type;

	switch( $sort_type ){
		 case '1':
			// Shuffle keys
			$keys = array_keys( $data['gallery'] );
			shuffle( $keys );

			// Rebuild array in new order
			$new = array();
			foreach( $keys as $key ) {
				$new[ $key ] = $data['gallery'][ $key ];
			}

			// Assign back to gallery
			$data['gallery'] = $new;
			break;
		case 'src':
		case 'title':
		case 'status':
		case 'caption': // new for backend sort
		case 'alt': // new for backend sort
		case 'link': // new for backend sort

			// Get metadata
			$keys = array();
			foreach ( $data['gallery'] as $id => $item ) {
				$keys[ $id ] = strip_tags( $item[ $sort_type ] );
			}

			// Sort titles / captions
			if ( $sort_direction == 'ASC' ) {

				natcasesort( $keys );

			} else {

				arsort( $keys );

			}

			// Iterate through sorted items, rebuilding slider
			$new_sort = array();
			foreach( $keys as $key => $title ) {
				$new_sort[ $key ] = $data['gallery'][ $key ];
			}

			// Assign back to gallery
			$data['gallery'] = $new_sort;

			break;
		/**
		* Published Date
		*/
		case 'date':
				// Get published date for each
				$keys = array();
				foreach ( $data['gallery'] as $id => $item ) {
					// If the attachment isn't in the Media Library, we can't get a post date - assume now
					if ( ! is_numeric( $id ) || ( false === ( $attachment = get_post( $id ) ) ) ) {
						$keys[ $id ] = date( 'Y-m-d H:i:s' );
					} else {
						$keys[ $id ] = $attachment->post_date;
					}

				}

				// Sort titles / captions
				if ( $sort_direction == 'ASC' ) {
					asort( $keys );
				} else {
					arsort( $keys );
				}

				// Iterate through sorted items, rebuilding gallery
				$new = array();
				foreach( $keys as $key => $title ) {
					$new[ $key ] = $data['gallery'][ $key ];
				}

				// Assign back to gallery
				$data['gallery'] = $new;
				break;
		 break;
		case 'date':
		break;
		case '0':
		break;

	}

	return $data;

}

/**
 * Helper method to return the max execution time for scripts.
 *
 * @since 1.0.0
 *
 * @param int $time The max execution time available for PHP scripts.
 */
function envira_get_max_execution_time() {

	$time = ini_get( 'max_execution_time' );
	return ! $time || empty( $time ) ? (int) 0 : $time;

}

/**
 * Helper method to return the transient expiration time
 *
 * @since 1.3.6.4
 *
 * @return int Expiration Time (in seconds)
 */
function envira_get_transient_expiration_time( $plugin = 'envira-gallery' ) {

	// Define the default
	$default = DAY_IN_SECONDS;

	// Allow devs to filter this depending on the plugin
	$default = apply_filters( 'envira_gallery_get_transient_expiration_time', $default, $plugin );

	// Return
	return $default;

}

/**
 * Iterates through all Post Types, returning an array of reserved slugs
 *
 * @since 1.5.7.3
 */
function envira_standalone_get_reserved_slugs() {

	$postTypes = get_post_types();
	if ( !is_array($postTypes) ) {
		return; // Something went wrong fetching Post Types
	}

	$slugs = array();
	foreach ( $postTypes as $postType ) {
		// Skip our own post type
		if ( $postType == 'envira' || $postType == 'envira_album' ) {
			continue;
		}

		$postTypeObj = get_post_type_object( $postType );

		if ( !isset($postTypeObj->rewrite['slug']) ) {
			continue;
		}

		// Add slug to array
		$slugs[] = $postTypeObj->rewrite['slug'];
	}

	return $slugs;

}

/**
 * Checks whether to see if Standlone is enabled.
 * 
 * Since 1.7.0
 *
 * @access public
 * @return bool
 */
function envira_is_standalone_enabled(){
	
	$standalone = envira_get_setting( 'standalone_enabled' );
	
	if ( !$standalone ){
	
		return false;
	
	}
	
	return true;
	
}

/**
 * Helper method to minify a string of data.
 *
 * @since 1.0.4
 *
 * @param string $string  String of data to minify.
 * @return string $string Minified string of data.
 */
function envira_minify( $string, $stripDoubleForwardslashes = true ) {

	// Added a switch for stripping double forwardslashes
	// This can be disabled when using URLs in JS, to ensure http:// doesn't get removed
	// All other comment removal and minification will take place
	$stripDoubleForwardslashes = apply_filters( 'envira_minify_strip_double_forward_slashes', $stripDoubleForwardslashes );

	if ( $stripDoubleForwardslashes ) {
		$clean = preg_replace( '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/', '', $string );
	} else {
		// Use less aggressive method
		$clean = preg_replace( '!/\*.*?\*/!s', '', $string );
		$clean = preg_replace( '/\n\s*\n/', "\n", $clean );
	}

	$clean = str_replace( array( "\r\n", "\r", "\t", "\n", '  ', '	  ', '		  ' ), '', $clean );

	return apply_filters( 'envira_gallery_minified_string', $clean, $string );

}

/**
 * Gets the slug from the options table. If blank or does not exist, defaults
 * to 'envira'
 *
 * @since 1.5.7.3
 *
 * @param string $type Type (gallery|albums)
 * @return string $slug Slug.
 */
function envira_standalone_get_slug( $type ) {

	// Get slug
	switch ($type) {
		case 'gallery':
			$slug = get_option( 'envira-gallery-slug');
			if ( !$slug OR empty( $slug ) ) {
				// Fallback to check for previous version option name.
				$slug = get_option( 'envira_standalone_slug' );
				if ( ! $slug || empty( $slug ) ) {
					$slug = 'envira';
				}
			}
			break;

		case 'albums':
			$slug = get_option( 'envira-albums-slug');
			if ( !$slug OR empty( $slug ) ) {
				$slug = 'envira_album';
			}
			break;

		default:
			$slug = 'envira'; // Fallback
			break;
	}

	return $slug;

}

/**
 * Helper method for getting a setting's value. Falls back to the default
 * setting value if none exists in the options table.
 *
 * @since 1.7.0
 *
 * @param string $key	The setting key to retrieve.
 * @return string		Key value on success, false on failure.
 */
function envira_get_setting( $key, $default = '' ){
	
	// Prefix the key
	$prefixed_key = 'envira_gallery_' . $key;
	// Get the option value
	$value = get_option( $prefixed_key );
	
	// If no value exists, fallback to the default
	if ( ! isset( $value ) ) {
			 $value = envira_get_setting_default( $key );
	}
	
	// Allow devs to filter
	$value = apply_filters( 'envira_gallery_get_setting', $value, $key, $prefixed_key );
	
	return $value;	 
			 	
}
/**
 * Helper method for getting a setting's default value
 *
 * @since 1.7.0
 *
 * @param string $key	The default setting key to retrieve.
 * @return string		Key value on success, false on failure.
 */
function envira_get_setting_default( $key ) {

	// Prepare default values.
	$defaults = envira_get_setting_defaults();

	// Return the key specified.
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : false;

}
/**
 * Retrieves the default settings
 *
 * @since 1.7.0
 *
 * @return array	   Array of default settings.
 */
function envira_get_setting_defaults() {

	// Prepare default values.
	$defaults = array(
		'media_position' => 'after',
		'standalone_enabled' => true
	);

	// Allow devs to filter the defaults.
	$defaults = apply_filters( 'envira_gallery_settings_defaults', $defaults );
	
	return $defaults;

}

/**
 * Helper method for updating a setting's value.
 *
 * @since 1.3.3.6
 *
 * @param string $key	The setting key
 * @param string $value The value to set for the key
 * @return null
 */
function envira_update_setting( $key, $value ) {

	// Prefix the key
	$key = 'envira_gallery_' . $key;

	// Allow devs to filter
	$value = apply_filters( 'envira_gallery_get_setting', $value, $key );

	// Update option
	update_option( $key, $value );

}