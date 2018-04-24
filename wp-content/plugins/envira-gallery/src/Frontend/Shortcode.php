<?php
/**
 * Shortcode class.
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author	Envira Gallery Team
 */
namespace Envira\Frontend;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}
 
class Shortcode {

	/**
	 * Holds the gallery data.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $data;

	/**
	 * Holds gallery IDs for init firing checks.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $done = array();

	/**
	 * Iterator for galleries on the page.
	 *
	 * @since 1.7.0
	 *
	 * @var int
	 */
	public $counter = 1;

	/**
	 * Array of gallery ids on the page.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $gallery_ids = array();

	/**
	 * Array of gallery item ids on the page.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $gallery_item_ids = array();

	/**
	 * Holds image URLs for indexing.
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $index = array();

	/**
	 * Holds the sort order of the gallery for addons like Pagination
	 *
	 * @since 1.5.6
	 *
	 * @var array
	 */
	public $gallery_sort = array();

	/**
	 * gallery_data
	 *
	 * (default value: array())
	 *
	 * @var array
	 * @access public
	 */
	public $gallery_data = array();


	/**
	 * is_mobile
	 *
	 * @var mixed
	 * @access public
	 */
	public $is_mobile;

	/**
	 * item
	 *
	 * @var mixed
	 * @access public
	 */
	public $item;

	/**
	 * gallery_markup
	 *
	 * @var mixed
	 * @access public
	 */
	public $gallery_markup;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.7.0
	 */
	public function __construct() {
		
		$this->base = envira();
		
		$this->is_mobile = envira_mobile_detect()->isMobile();

		// Load hooks and filters.
		add_action( 'init', array( &$this, 'register_scripts' ) );
		
		add_shortcode( 'envira-gallery', array( $this, 'shortcode' ) );
		
		add_filter( 'style_loader_tag', array( $this, 'add_stylesheet_property_attribute' ) );
		add_filter( 'envirabox_actions', array( $this, 'envirabox_actions' ), 101, 2 );
		add_filter( 'envirabox_inner_above', array( $this, 'envirabox_inner_above' ), 10, 2 );
		add_action( 'envira_gallery_output_caption', array( $this, 'gallery_image_caption_titles' ), 10, 5 );

		add_filter( 'envirabox_margin', array( $this, 'envirabox_margin' ), 10, 2 );
		add_filter( 'envirabox_padding', array( $this, 'envirabox_padding' ), 10, 2 );
		add_filter( 'envirabox_arrows', array( $this, 'envirabox_arrows' ), 10, 2 );
		add_filter( 'envirabox_gallery_thumbs_position', array( $this, 'envirabox_gallery_thumbs_position' ), 10, 2 );
		add_filter( 'envirabox_dynamic_margin', array( $this, 'envirabox_dynamic_margin' ), 10, 2 );
		add_filter( 'envirabox_dynamic_margin_amount', array( $this, 'envirabox_dynamic_margin_amount' ), 10, 2 );
		add_filter( 'envira_gallery_title_type', array( $this, 'envira_gallery_title_type' ), 10, 2 );
		add_filter( 'envira_always_show_title', array( $this, 'envira_always_show_title' ), 10, 2 );

		add_filter( 'envirabox_wrap_css_classes', array( $this, 'envira_supersize_wrap_css_class' ), 10, 2 );

	}
	
	/**
	 * register_scripts function.
	 * 
	 * @since 1.7.0
	 *
	 * @access public
	 * @return void
	 */
	public function register_scripts(){
		
		// Register main gallery style.
		wp_register_style( ENVIRA_SLUG . '-style', plugins_url( 'assets/css/envira.css', ENVIRA_FILE ), array(), ENVIRA_VERSION );
		wp_register_style( ENVIRA_SLUG . '-lazyload', plugins_url( 'assets/css/responsivelyLazy.css', ENVIRA_FILE ), array(), ENVIRA_VERSION );

		// Register Justified Gallery style.
		wp_register_style( ENVIRA_SLUG . '-jgallery', plugins_url( 'assets/css/justifiedGallery.css', ENVIRA_FILE ), array(), ENVIRA_VERSION );

		// Register main gallery script.
		wp_register_script( ENVIRA_SLUG . '-script', plugins_url( 'assets/js/min/envira-min.js', ENVIRA_FILE ), array( 'jquery' ), ENVIRA_VERSION, true );

	}
	
	/**
	 * Creates the shortcode for the plugin.
	 *
	 * @since 1.7.0
	 *
	 * @global object $post The current post object.
	 *
	 * @param array $atts Array of shortcode attributes.
	 * @return string		 The gallery output.
	 */
	public function shortcode( $atts ) {

		global $post;
		// If no attributes have been passed, the gallery should be pulled from the current post.
		$gallery_id = false;

		if ( empty( $atts ) ) {
			$gallery_id = $post->ID;
			$data		= is_preview() ? _envira_get_gallery( $gallery_id ) : envira_get_gallery( $gallery_id );
		} else if ( isset( $atts['id'] ) && !isset( $atts['dynamic'] ) ) {
			$gallery_id = (int) $atts['id'];
			$data		= is_preview() ? _envira_get_gallery( $gallery_id ) : envira_get_gallery( $gallery_id );
		} else if ( isset( $atts['slug'] ) ) {
			$gallery_id = $atts['slug'];
			$data		= is_preview() ? _envira_get_gallery_by_slug( $gallery_id ) : envira_get_gallery_by_slug( $gallery_id );
		} else {
			// A custom attribute must have been passed. Allow it to be filtered to grab data from a custom source.
			$data = apply_filters( 'envira_gallery_custom_gallery_data', false, $atts, $post );
			$gallery_id = $data['config']['id'];
		}

		// Lets check if this gallery has already been output on the page
		$data['gallery_id'] = ( isset( $data['config']['type'] ) && $data['config']['type'] == 'dynamic' ) ? $data['config']['id'] : $data['id'];

		if ( ! empty( $atts['counter'] ) ) {
			// we are forcing a counter so lets force the object in the gallery_ids
			$this->counter = $atts['counter'];
			$this->gallery_ids[] = $data['id'];
		}

		if ( ! empty( $data['id'] ) && ! in_array( $data['id'], $this->gallery_ids ) ) {
			$this->gallery_ids[] = $data['id'];
		} elseif( $this->counter > 1 && !empty( $data['id'] ) ) {
			$data['id'] = $data['id'] . '_' . $this->counter;
		}

		if ( ! empty( $data['id'] ) && empty( $atts['presorted'] ) ) {
			$this->gallery_sort[ $data['id'] ] = false; // reset this to false, otherwise multiple galleries on the same page might get other ids, or other wackinesses
		}

		// Allow the data to be filtered before it is stored and used to create the gallery output.
		$data = apply_filters( 'envira_gallery_pre_data', $data, $gallery_id );
		
		// Limit the number of images returned, if specified
		// [envira-gallery id="123" limit="10"] would only display 10 images
		if ( isset( $atts['limit'] ) && is_numeric( $atts['limit'] ) ) {
			// check for existence of gallery, if there's nothing it could be an instagram or blank gallery
			if ( ! empty( $data['gallery'] ) ) {
				$images = array_slice( $data['gallery'], 0, absint( $atts['limit'] ), true );
				$data['gallery'] = $images;
			}
		}
		
		$password_form = apply_filters( 'envira_gallery_pwd_form', false, $data, $gallery_id );
		
		// If there is no data to output or the gallery is inactive, do nothing.
		if ( ! $data || empty( $data['gallery'] ) || isset( $data['status'] ) && 'inactive' == $data['status'] && ! is_preview() ) {
			return;
		}

		// This filter detects if something needs to be displayed BEFORE a gallery is displayed, such as a password form
		$pre_gallery_html = apply_filters( 'envira_abort_gallery_output', false, $data, $gallery_id, $atts );

		if ( $pre_gallery_html !== false ) {
			// If there is HTML, then we stop trying to display the gallery and return THAT HTML
			return apply_filters( 'envira_gallery_output', $pre_gallery_html, $data );
		}

		$this->gallery_data = $data;

		// Get rid of any external plugins trying to jack up our stuff where a gallery is present.
		$this->plugin_humility();

		// Prepare variables.
		$this->index[ $this->gallery_data['id'] ] = array();
		$this->gallery_markup		= '';
		$i							= 1;

		// If this is a feed view, customize the output and return early.
		if ( is_feed() ) {
			
			return $this->do_feed_output( $this->gallery_data );
			
		}

		$lazy_loading_delay = isset( $this->gallery_data['config']['lazy_loading_delay'] ) ? intval($this->gallery_data['config']['lazy_loading_delay']) : 500;

		// Load scripts and styles.
		wp_enqueue_style( ENVIRA_SLUG . '-style' );
		wp_enqueue_style( ENVIRA_SLUG . '-lazyload' );
		wp_enqueue_style( ENVIRA_SLUG . '-jgallery' );
		wp_enqueue_script( ENVIRA_SLUG . '-script' );
		
		// If supersize is enabled, lets go ahead and enqueue the CSS for it
		if ( envira_get_config( 'supersize', $this->gallery_data ) ) {
			wp_register_style( ENVIRA_SLUG . '-supersize-style', plugins_url( 'assets/css/envira-supersize.css', plugin_basename( ENVIRA_FILE ) ), array(), ENVIRA_VERSION );
			wp_enqueue_style( ENVIRA_SLUG . '-supersize-style' );
		}
			
		// If lazy load is active, load the lazy load script
		if ( envira_get_config( 'lazy_loading', $data ) == 1 ) {
			wp_localize_script( ENVIRA_SLUG . '-script', 'envira_lazy_load', 'true');
			wp_localize_script( ENVIRA_SLUG . '-script', 'envira_lazy_load_initial', 'false');
			wp_localize_script( ENVIRA_SLUG . '-script', 'envira_lazy_load_delay', (string) $lazy_loading_delay);
		}
		wp_localize_script( ENVIRA_SLUG . '-script', 'envira_gallery', array(
			'debug'				=> ( defined( 'ENVIRA_DEBUG' ) && ENVIRA_DEBUG ? true : false ),
		) );

		// Load custom gallery themes if necessary.
		if ( 'base' !== envira_get_config( 'gallery_theme', $this->gallery_data ) && envira_get_config( 'columns', $this->gallery_data ) > 0 ) {
			
			// if columns is zero, then it's automattic which means we do not load gallery themes because it will mess up the new javascript layout
			$this->load_gallery_theme( envira_get_config( 'gallery_theme', $this->gallery_data ) );
		
		}

		// Load custom lightbox themes if necessary, don't load if user hasn't enabled lightbox
		if ( envira_get_config( 'lightbox_enabled', $this->gallery_data ) ) {
		
			if ( 'base' !== envira_get_config( 'lightbox_theme', $this->gallery_data ) ) {
				$this->load_lightbox_theme( envira_get_config( 'lightbox_theme', $this->gallery_data ) );
			}
		}

		// Load gallery init code in the footer.
		add_action( 'wp_footer', array( $this, 'gallery_init' ), 1000 );

		// Run a hook before the gallery output begins but after scripts and inits have been set.
		do_action( 'envira_gallery_before_output', $this->gallery_data );
		
		$markup = apply_filters( 'envira_gallery_get_transient_markup', get_transient( '_eg_fragment_' . $data['gallery_id'] ), $this->gallery_data );		
	
		if ( $markup && ( !defined('ENVIRA_DEBUG') || !ENVIRA_DEBUG ) ) {
		
			$this->gallery_markup = $markup;
			
		} else {

			// echo "------"; print_r ($this->gallery_sort); exit;
		
			// Apply a filter before starting the gallery HTML.
			$this->gallery_markup = apply_filters( 'envira_gallery_output_start', $this->gallery_markup, $this->gallery_data );
	
			 // Schema.org microdata ( Itemscope, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding
			 $schema_microdata = apply_filters( 'envira_gallery_output_shortcode_schema_microdata', 'itemscope itemtype="http://schema.org/ImageGallery"', $this->gallery_data );
		
			// Build out the gallery HTML.
			$this->gallery_markup .= '<div id="envira-gallery-wrap-' . sanitize_html_class( $this->gallery_data['id'] ) . '" class="' . $this->get_gallery_classes( $this->gallery_data ) . '" ' . $schema_microdata . '>';
				$this->gallery_markup  = apply_filters( 'envira_gallery_output_before_container', $this->gallery_markup, $this->gallery_data );
					 
				$temp_gallery_markup = apply_filters( 'envira_gallery_temp_output_before_container', '', $this->gallery_data );
	
				// Description
				if ( isset( $this->gallery_data['config']['description_position'] ) && $this->gallery_data['config']['description_position'] == 'above' ) {
					$temp_gallery_markup = $this->description( $temp_gallery_markup, $this->gallery_data );
				}
	
				$opacity_insert = false;
				if ( envira_get_config( 'columns', $this->gallery_data ) == 0 ) {
					$opacity_insert = ' style="opacity: 0.0" ';
				}
	
				// add justified CSS?
				$extra_css 					= 'envira-gallery-justified-public';
				$row_height 				= false;
				$justified_gallery_theme 	= false;
				$justified_margins 			= false;
	
				if ( envira_get_config( 'columns', $this->gallery_data ) > 0 ) {
	
					$extra_css = false;
					// add isotope if the user has it enabled
					$isotope = envira_get_config( 'isotope', $this->gallery_data ) ? ' enviratope' : false;
	
				} else {
			
					$row_height = !$this->is_mobile  ?	 envira_get_config( 'justified_row_height', $this->gallery_data ) : envira_get_config( 'mobile_justified_row_height', $this->gallery_data );
					$justified_gallery_theme = envira_get_config( 'justified_gallery_theme', $this->gallery_data );
					$justified_margins = envira_get_config( 'justified_margins', $this->gallery_data );
	
					// this is a justified layout, no isotope even if it's selected in the DB
					$isotope = false;
					
				}
	
				$extra_css = apply_filters( 'envira_gallery_output_extra_css', $extra_css, $this->gallery_data );
	
				$temp_gallery_markup .= '<div' .  $opacity_insert . ' data-row-height="'.$row_height.'" data-justified-margins="'.$justified_margins.'" data-gallery-theme="'.$justified_gallery_theme.'" id="envira-gallery-' . sanitize_html_class( $this->gallery_data['id'] ) . '" class="envira-gallery-public '.$extra_css.' envira-gallery-' . sanitize_html_class( envira_get_config( 'columns', $this->gallery_data ) ) . '-columns envira-clear' . $isotope . ( envira_get_config( 'css_animations', $this->gallery_data ) ? ' envira-gallery-css-animations' : '' ) . '" data-envira-columns="' . envira_get_config( 'columns', $this->gallery_data ) . '">';
	
					// Start image loop
					foreach ( $this->gallery_data['gallery'] as $id => $item ) {
						
						// Skip over images that are pending (ignore if in Preview mode).
						if ( isset( $item['status'] ) && 'pending' == $item['status'] && ! is_preview() ) {
							continue;
						}
	
						// Lets check if this gallery has already been output on the page
						if ( ! in_array( $id, $this->gallery_item_ids ) ) {
							$this->gallery_item_ids[] = $id;
						}
	
						// Add the gallery item to the markup
						$temp_gallery_markup = $this->generate_gallery_item_markup( $temp_gallery_markup, $this->gallery_data, $item, $id, $i );
	
						// Check the counter - if we are an instagram gallery AND there's a limit, then stop here
						if ( isset( $atts['limit'] ) && is_numeric( $atts['limit'] ) && $this->gallery_data['config']['type'] == 'instagram' && $i >= $atts['limit'] ) {
							break;
						}
	
						// Increment the iterator.
						$i++;
	
					}
					// End image loop
	
			 // Filter output before starting this gallery item.
			 $temp_gallery_markup  = apply_filters( 'envira_gallery_output_before_item', $temp_gallery_markup, $id, $item, $data, $i );
	
				$temp_gallery_markup .= '</div>';
				// Description
				if ( isset( $this->gallery_data['config']['description_position'] ) && $this->gallery_data['config']['description_position'] == 'below' ) {
					$temp_gallery_markup = $this->description( $temp_gallery_markup, $this->gallery_data );
				}
	
				$temp_gallery_markup 	= apply_filters( 'envira_gallery_temp_output_after_container', $temp_gallery_markup, $this->gallery_data );
	
				$this->gallery_markup 	= apply_filters( 'envira_gallery_output_after_container', $this->gallery_markup .= $temp_gallery_markup, $this->gallery_data );
	
	
			$this->gallery_markup .= '</div>';
			$this->gallery_markup  = apply_filters( 'envira_gallery_output_end', $this->gallery_markup, $this->gallery_data );
	
			// Increment the counter.
			$this->counter++;
	
			// Remove any contextual filters so they don't affect other galleries on the page.
			if ( envira_get_config( 'mobile', $this->gallery_data ) ) {
				remove_filter( 'envira_gallery_output_image_attr', array( $this, 'mobile_image' ), 999, 4 );
			}
	
			// Add no JS fallback support.
			$no_js		= '<noscript>';
			$no_js	 .= $this->get_indexable_images( $data['id'] );
			$no_js	 .= '</noscript>';
			
			$this->gallery_markup .= apply_filters( 'envira_gallery_output_noscript', $no_js, $this->gallery_data );
				
			$transient = set_transient( '_eg_fragment_' . $data['gallery_id'] , $this->gallery_markup, DAY_IN_SECONDS );
			
		}
		
		$this->data[ $data['id'] ]	= $this->gallery_data;

		// Return the gallery HTML.
		return apply_filters( 'envira_gallery_output', $this->gallery_markup, $this->gallery_data );

	}

	/**
	* Outputs an individual gallery item in the grid
	*
	* @since 1.4.2.1
	*
	* @param	string	$gallery	Gallery HTML
	* @param	array	$data		Gallery Config
	* @param	array	$item		Gallery Item (Image)
	* @param	int		$id			Gallery Image ID
	* @param	int		$i			Index
	* @return	string				Gallery HTML
	*/
	public function generate_gallery_item_markup( $gallery, $data, $item, $id, $i ) {

		// Get some config values that we'll reuse for each image
		$padding = absint( round( envira_get_config( 'gutter', $data ) / 2 ) );
		$html5_attribute = ( ( envira_get_config( 'html5', $data ) == '1' ) ? 'data-envirabox-group' : 'rel' );

		// Skip over images that are pending (ignore if in Preview mode).
		if ( isset( $item['status'] ) && 'pending' == $item['status'] && ! is_preview() ) {
			return $gallery;
		}

		$item = apply_filters( 'envira_gallery_output_item_data', $item, $id, $data, $i );

		// Get image and image retina URLs
		// These calls will generate thumbnails as necessary for us.
		$imagesrc = $this->get_image_src( $id, $item, $data );
		$image_src_retina = $this->get_image_src( $id, $item, $data, false, true );
		$placeholder = wp_get_attachment_image_src( $id, 'medium' ); // $placeholder is null because $id is 0 for instagram?

		// If we don't get an imagesrc, it's likely because of an error w/ dynamic
		// So to prevent JS errors or not rendering the gallery at all, return the gallery HTML because we can't render without it
		if ( !$imagesrc ) { return $gallery; }

		// Filter output before starting this gallery item.
		$gallery	= apply_filters( 'envira_gallery_output_before_item', $gallery, $id, $item, $data, $i );

		// Maybe change the item's link if it is an image and we have an image size defined for the Lightbox
		$item = $this->maybe_change_link( $id, $item, $data );

		// Schema.org microdata ( Itemscope, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding
		$schema_microdata = apply_filters( 'envira_gallery_output_schema_microdata_imageobject', 'itemscope itemtype="http://schema.org/ImageObject"', $data );

		$output		= '<div id="envira-gallery-item-' . sanitize_html_class( $id ) . '" class="' . $this->get_gallery_item_classes( $item, $i, $data ) . '" style="padding-left: ' . $padding . 'px; padding-bottom: ' . envira_get_config( 'margin', $data ) . 'px; padding-right: ' . $padding . 'px;" ' . apply_filters( 'envira_gallery_output_item_attr', '', $id, $item, $data, $i ) . ' ' . $schema_microdata . '>';

		$output .= '<div class="envira-gallery-item-inner">';
		$output	 = apply_filters( 'envira_gallery_output_before_link', $output, $id, $item, $data, $i );

		$css_class = false; // no css classes yet

		// Top Left box
		$output .= '<div class="envira-gallery-position-overlay ' . $css_class . ' envira-gallery-top-left">';
		$output	 = apply_filters( 'envira_gallery_output_dynamic_position', $output, $id, $item, $data, $i, 'top-left' );
		$output .= '</div>';

		// Top Right box
		$output .= '<div class="envira-gallery-position-overlay ' . $css_class . ' envira-gallery-top-right">';
		$output	 = apply_filters( 'envira_gallery_output_dynamic_position', $output, $id, $item, $data, $i, 'top-right' );
		$output .= '</div>';

		// Bottom Left box
		$output .= '<div class="envira-gallery-position-overlay ' . $css_class . ' envira-gallery-bottom-left">';
		$output	 = apply_filters( 'envira_gallery_output_dynamic_position', $output, $id, $item, $data, $i, 'bottom-left' );
		$output .= '</div>';

		// Bottom Right box
		$output .= '<div class="envira-gallery-position-overlay ' . $css_class . ' envira-gallery-bottom-right">';
		$output	 = apply_filters( 'envira_gallery_output_dynamic_position', $output, $id, $item, $data, $i, 'bottom-right' );
		$output .= '</div>';

		// Determine Caption For Gallery Thumbnail
		if ( envira_get_config( 'columns', $data ) == 0 ) {
			$caption_array = array();
			if ( envira_get_config( 'additional_copy_automatic_title', $data ) && isset( $item['title'] ) ) {
				$caption_array[] = strip_tags( htmlspecialchars( $item['title'] ) );
			}
			if ( envira_get_config( 'additional_copy_automatic_caption', $data ) && isset( $item['caption'] ) ) {
				$caption_array[] = str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', strip_tags( esc_attr( $item['caption'] ) ) );
			}
			$caption = implode(' - ', $caption_array);
		} else if ( envira_get_config( 'columns', $data ) > 0 ) {
			// check and see if we are using a certain gallery theme, so we can determine if caption is sent
			$gallery_theme = envira_get_config( 'gallery_theme', $data );
			if ( ( $gallery_theme == 'captioned' || $gallery_theme == 'polaroid' ) && ( envira_get_config( 'additional_copy_caption', $data ) ) ) {
				// don't display the caption, because the gallery theme will take care of this
				$caption = false;
			} else {
				//$caption = isset( $item['caption'] ) ? do_shortcode( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', strip_tags( esc_attr( $item['caption'] ) ) ) ) : false;
				$caption_array = array();
 				if ( $this->get_config( 'additional_copy_automatic_title', $data ) && isset( $item['title'] ) ) {
 					$caption_array[] = strip_tags( htmlspecialchars( $item['title'] ) );
 				}
 				if ( $this->get_config( 'additional_copy_automatic_caption', $data ) && isset( $item['caption'] ) ) {
 					$caption_array[] = str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', strip_tags( esc_attr( $item['caption'] ) ) );
 				}
 				$caption = implode(' - ', $caption_array);
			}
		} else {
			$caption = false;
		}

		// Show caption BUT if the user has overrided with the title, show that instead
		if ( !envira_get_config( 'lightbox_title_caption', $data ) || envira_get_config( 'lightbox_title_caption', $data ) != 'title' ) {
			$lightbox_caption = isset( $item['caption'] ) ? do_shortcode( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', strip_tags( htmlspecialchars( $item['caption'] ) ) ) ) : false;
		} else {
			$lightbox_caption = isset( $item['title'] ) ? do_shortcode( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', strip_tags( htmlspecialchars( $item['title'] ) ) ) ) : false;
		}
		// Allow changes to lightbox caption
		$lightbox_caption = apply_filters( 'envira_gallery_output_lightbox_caption', $lightbox_caption, $data, $id, $item, $i );
		// Cap the length of the lightbox caption for light/dark themes
		if ( envira_get_config( 'lightbox_theme', $data ) == 'base_dark' || envira_get_config( 'lightbox_theme', $data ) == 'base_light' ) {
			$lightbox_caption_limit = apply_filters( 'envira_gallery_output_lightbox_caption_limit', 100, $data, $id, $item, $i );
			if ( strlen($lightbox_caption) > $lightbox_caption_limit ) {
				$lightbox_caption = substr($lightbox_caption, 0, strrpos(substr($lightbox_caption, 0, $lightbox_caption_limit), ' ')) . '...';
			}
		}
		
		$lightbox_caption 		= htmlentities($lightbox_caption);
		$envira_gallery_class 	= 'envira-gallery-' . sanitize_html_class( $data['id'] );
		
		// Link Target
		$external_link_array 	= false;

		// If this is a dynamic gallery and the user has passed in a comma-delimited list of URLS via "external" we should use these, otherwise go with normal
		if ( isset( $data['config']['external'] ) && !empty( $data['config']['external'] ) ) {
			$external_link_array = explode("," , $data['config']['external'] );
			if ( $external_link_array ) {
			// determine where we are in the queue, override link if there's one
				if ( isset( $external_link_array[$i - 1] ) ) {
					$item['link'] = $external_link_array[$i - 1]; // esc_url is filtered below
					$envira_gallery_class = false;
				}
			}
		}

		// dynamic gallery isn't happening and there's an instagram_link, override
		if ( !$external_link_array && ! empty( $item['instagram_link'] ) ) {
			$item['link'] = $item['instagram_link'];
		}

		$target = ! empty( $item['target'] ) ? 'target="' . $item['target'] . '"' : false;

		// Determine if we create a link.
		// If this is a mobile device and the user has disabled lightbox, there should not be a link
		// "Turn off the lightbox then the image shouldn't be clicked"
		if ( $this->is_mobile && !envira_get_config( 'lightbox_enabled', $data ) ) {
			$create_link = false;
		} else if ( isset( $data['config']['type']) && $data['config']['type'] == 'instagram' && ! $data['config']['instagram_link'] ) {
			$create_link = false;
		} else if ( ! empty( $item['link'] ) ) {
			$create_link = true;
		} else {
			$create_link = false;
		}

		// Filter the ability to create a link
		$create_link = apply_filters( 'envira_gallery_create_link', $create_link, $data, $id, $item, $i, $this->is_mobile );

		// Schema.org microdata ( itemprop, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding
		$schema_microdata = apply_filters( 'envira_gallery_output_schema_microdata_itemprop_contenturl', 'itemprop="contentUrl"', $data, $id, $item, $i );
		
		if ( $create_link ) {
			if ( ! empty( $item['mobile_thumb'] ) ) {
				$this->is_mobile_thumb = esc_url( $item['mobile_thumb'] );
			} else if ( ! empty( $item['src'] ) && $this->is_mobile == 'mobile' ) {
				$this->is_mobile_thumb = esc_url( $item['src'] );
			} else {
				$this->is_mobile_thumb = false;
			}		
			//fallback to src if the item thumb isnt set.
			$thumb = $item['thumb'] ? $item['thumb'] : $item['src'];
			
			$title = str_replace('<', '&lt;', $item['title']); // not sure why this was not encoded
			// allow addons to change the link
			$link_href = apply_filters( 'envira_gallery_link_href', $item['link'], $data, $id, $item, $i, $this->is_mobile );
			$output .= '<a ' . $target . ' href="' . esc_url( $item['link'] ) . '" class="envira-gallery-' . sanitize_html_class( $data['id'] ) . ' envira-gallery-link " ' . $html5_attribute . '="enviragallery' . sanitize_html_class( $data['id'] ) . '" title="' . strip_tags( htmlspecialchars( $title ) ) . '" data-envira-caption="' . $caption . '" data-envira-lightbox-caption="' . $lightbox_caption . '" data-envira-retina="' . ( isset( $item['lightbox_retina_image'] ) ? $item['lightbox_retina_image'] : '' ) . '" data-thumbnail="' . esc_attr( $thumb ). '" data-mobile-thumbnail="' .  esc_attr( $this->is_mobile_thumb ) . '"' . ( ( isset($item['link_new_window']) && $item['link_new_window'] == 1 ) ? ' target="_blank"' : '' ) . ' ' . apply_filters( 'envira_gallery_output_link_attr', '', $id, $item, $data, $i ) . ' itemprop="contentUrl">';
		}

		$output	 = apply_filters( 'envira_gallery_output_before_image', $output, $id, $item, $data, $i );	

		$gallery_theme = envira_get_config( 'columns', $data ) == 0 ? ' envira-' . envira_get_config( 'justified_gallery_theme', $data ) : '';
		$gallery_theme_suffix = ( envira_get_config( 'justified_gallery_theme_detail', $data ) ) === 'hover' ? '-hover' : false;

		// Schema.org microdata ( itemprop, etc. ) interferes with Google+ Sharing... so we are adding this via filter rather than hardcoding
		$schema_microdata = apply_filters( 'envira_gallery_output_schema_microdata_itemprop_thumbnailurl', 'itemprop="thumbnailUrl"', $data, $id, $item, $i );

		// Build the image and allow filtering
		// Update: how we build the html depends on the lazy load script

		// Check if user has lazy loading on - if so, we add the css class

		$envira_lazy_load = envira_get_config( 'lazy_loading', $data ) == 1 ? 'envira-lazy' : '';

		// Determine/confirm the width/height of the image
		// $placeholder should hold it but not for instagram

		if ( $this->is_mobile == 'mobile' && !envira_get_config( 'mobile', $data ) ) { // if the user is viewing on mobile AND user unchecked 'Create Mobile Gallery Images?' in mobile tab
			$output_src = $item['src'];
		} else if ( envira_get_config( 'crop', $data ) ) { // the user has selected the image to be cropped
			$output_src = $imagesrc;
		} else if ( envira_get_config( 'image_size', $data ) && $imagesrc ) { // use the image being provided thanks to the user selecting a unique image size
			$output_src = $imagesrc;
		} else if ( !empty( $item['src'] ) ) {
			$output_src = $item['src'];
		} else if ( !empty( $placeholder[0] ) ) {
			$output_src = $placeholder[0];
		} else {
			$output_src = false;
		}

		if ( envira_get_config( 'crop', $data ) && envira_get_config( 'crop_width', $data ) && envira_get_config( 'image_size', $data ) != 'full' ) {
			$output_width = envira_get_config( 'crop_width', $data );
		} else if ( envira_get_config( 'columns', $data ) != 0 && envira_get_config( 'image_size', $data ) && envira_get_config( 'image_size', $data ) != 'full' && envira_get_config( 'crop_width', $data ) && envira_get_config( 'crop_height', $data ) ) {
			$output_width = envira_get_config( 'crop_width', $data );
		} else if ( isset( $data['config']['type'] ) && $data['config']['type'] == 'instagram' && strpos($imagesrc, 'cdninstagram' ) !== false ) {
			// if this is an instagram image, @getimagesize might not work
			// therefore we should try to extract the size from the url itself
			if ( strpos( $imagesrc , '150x150' ) ) {
				$output_width = '150';
			} else if ( strpos( $imagesrc , '640x640' ) ) {
				$output_width = '640';
			} else {
				$output_width = '150';
			}
		} else if ( !empty( $item['width'] ) ) {
			$output_width = $item['width'];
		} else if ( !empty( $placeholder[1] ) ) {
			$output_width = $placeholder[1];
		} else {
			$output_width = false;
		}

		if ( envira_get_config( 'crop', $data ) && envira_get_config( 'crop_width', $data ) && envira_get_config( 'image_size', $data ) != 'full' ) {
			$output_height = envira_get_config( 'crop_height', $data );
		} else if ( envira_get_config( 'columns', $data ) != 0 && envira_get_config( 'image_size', $data ) && envira_get_config( 'image_size', $data ) != 'full' && envira_get_config( 'crop_width', $data ) && envira_get_config( 'crop_height', $data ) ) {
			$output_height = envira_get_config( 'crop_height', $data );
		} else if ( isset( $data['config']['type'] ) && $data['config']['type'] == 'instagram' && strpos($imagesrc, 'cdninstagram' ) !== false ) {
			// if this is an instagram image, @getimagesize might not work
			// therefore we should try to extract the size from the url itself
			if ( strpos( $imagesrc , '150x150' ) ) {
				$output_height = '150';
			} else if ( strpos( $imagesrc , '640x640' ) ) {
				$output_height = '640';
			} else {
				$output_height = '150';
			}
		} else if ( !empty( $item['height'] ) ) {
			$output_height = $item['height'];
		} else if ( !empty( $placeholder[2] ) ) {
			$output_height = $placeholder[2];
		} else {
			$output_height = false;
		}

		/* add filters for width and height, primarily so dynamic can add width and height */

		$output_width = apply_filters( 'envira_gallery_output_width', $output_width, $id, $item, $data, $i, $output_src );
		$output_height = apply_filters( 'envira_gallery_output_height', $output_height, $id, $item, $data, $i, $output_src );

		if ( envira_get_config( 'columns', $data ) == 0 ) {

			// Automatic

			$output_item = '<img id="envira-gallery-image-' . sanitize_html_class( $id ) . '" class="envira-gallery-image envira-gallery-image-' . $i . $gallery_theme . $gallery_theme_suffix . ' '.$envira_lazy_load.'" data-envira-index="' . $i . '" src="' . esc_url( $output_src ) . '"' . ( envira_get_config( 'dimensions', $data ) ? ' width="' . envira_get_config( 'crop_width', $data ) . '" height="' . envira_get_config( 'crop_height', $data ) . '"' : '' ) . ' data-envira-src="' . esc_url( $output_src ) . '" data-envira-gallery-id="' . $data['id'] . '" data-envira-item-id="' . $id . '" data-envira-caption="' . strip_tags( htmlspecialchars( $caption ) ) . '" data-envira-lightbox-caption="' . $lightbox_caption . '"  alt="' . esc_attr( $item['alt'] ) . '" title="' . strip_tags( esc_attr( $item['title'] ) ) . '" ' . apply_filters( 'envira_gallery_output_image_attr', '', $id, $item, $data, $i ) . ' itemprop="thumbnailUrl" data-envira-srcset="' . esc_url( $output_src ) . ' 400w,' . esc_url( $output_src ) . ' 2x" data-envira-width="'.$output_width.'" data-envira-height="'.$output_height.'" srcset="' . ( ( $envira_lazy_load ) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' : esc_url( $image_src_retina ) . ' 2x' ) . '" data-safe-src="'. ( ( $envira_lazy_load ) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' : esc_url( $output_src ) ) . '" />';

		} else {

			// Legacy

			$output_item = false;

			if ( $envira_lazy_load ) {

				if ( $output_height > 0 && $output_width > 0 ) {
					$padding_bottom = ( $output_height / $output_width ) * 100;
				} else {
					// this shouldn't be happening, but this avoids a debug message
					$padding_bottom = 100;
				}
				$output_item .= '<div class="envira-lazy" style="padding-bottom:'.$padding_bottom.'%;">';

			}

			// If the user has asked to set width and height dimensions to images, let's determine what those values should be
			if ( envira_get_config( 'image_size', $data ) && envira_get_config( 'image_size', $data ) == 'default' ) {
				$width_attr_value 	= envira_get_config( 'crop_width', $data );
				$height_attr_value	= envira_get_config( 'crop_height', $data );
			} else if ( envira_get_config( 'image_size', $data ) && envira_get_config( 'image_size', $data ) == 'full' ) {
				// is there a way to get the oringial width/height outside of this?
				$src = apply_filters( 'envira_gallery_retina_image_src', wp_get_attachment_image_src( $id, 'full' ), $id, $item, $data );
				$width_attr_value 	= $src[1];
				$height_attr_value	= $src[2];				
			} else if ( $output_width && $output_height ) {
				$width_attr_value 	= $output_width;
				$height_attr_value	= $output_height;
			} else {
				$width_attr_value = $height_attr_value = false;
			}

			$output_item .= '<img id="envira-gallery-image-' . sanitize_html_class( $id ) . '" class="envira-gallery-image envira-gallery-image-' . $i . $gallery_theme . $gallery_theme_suffix . '" data-envira-index="' . $i . '" src="' . esc_url( $output_src ) . '"' . ( envira_get_config( 'dimensions', $data ) && $width_attr_value && $height_attr_value ? ' width="' . $width_attr_value . '" height="' . $height_attr_value . '"' : '' ) . ' data-envira-src="' . esc_url( $output_src ) . '" data-envira-gallery-id="' . $data['id'] . '" data-envira-item-id="' . $id . '" data-envira-caption="' . $caption . '" data-envira-lightbox-caption="' . $lightbox_caption . '"	alt="' . esc_attr( $item['alt'] ) . '" title="' . strip_tags( esc_attr( $item['title'] ) ) . '" ' . apply_filters( 'envira_gallery_output_image_attr', '', $id, $item, $data, $i ) . ' itemprop="thumbnailUrl" data-envira-srcset="' . esc_url( $output_src ) . ' 400w,' . esc_url( $output_src ) . ' 2x" srcset="' . ( ( $envira_lazy_load ) ? 'data:image/gif;base64,R0lGODlhAQABAIAAAP///////yH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==' : esc_url( $image_src_retina ) . ' 2x' ) . '" />';

			if ( $envira_lazy_load ) {

				$output_item .= '</div>';

			}

		}

		$output_item = apply_filters( 'envira_gallery_output_image', $output_item, $id, $item, $data, $i );

		// Add image to output
		$output .= $output_item;
		$output	 = apply_filters( 'envira_gallery_output_after_image', $output, $id, $item, $data, $i );

		$output	.= apply_filters( 'envira_gallery_output_caption', $output, $id, $item, $data, $i );

		if ( $create_link ) {
			$output .= '</a>';

		}

		$output	= apply_filters( 'envira_gallery_output_after_link', $output, $id, $item, $data, $i );

		$output .= '</div>';

		$output .= '</div>';
		$output	 = apply_filters( 'envira_gallery_output_single_item', $output, $id, $item, $data, $i );

		// Append the image to the gallery output
		$gallery .= $output;

		// Filter the output before returning
		$gallery	= apply_filters( 'envira_gallery_output_after_item', $gallery, $id, $item, $data, $i );

		return $gallery;

	}

	/**
	* Add the 'property' tag to stylesheets enqueued in the body
	*
	* @since 1.4.1.1
	*/
	public function add_stylesheet_property_attribute( $tag ) {

		// If the <link> stylesheet is any Envira-based stylesheet, add the property attribute
		if ( strpos( $tag, "id='envira-" ) !== false ) {
			$tag = str_replace( '/>', 'property="stylesheet" />', $tag );
		}

		return $tag;

	}

	/**
	* Builds HTML for the Gallery Description
	*
	* @since 1.3.0.2
	*
	* @param string $gallery Gallery HTML
	* @param array $data Data
	* @return HTML
	*/
	public function description( $gallery, $data ) {

		$gallery .= '<div class="envira-gallery-description envira-gallery-description-above" style="padding-bottom: ' . envira_get_config( 'margin', $data ) . 'px;">';
			$gallery	 = apply_filters( 'envira_gallery_output_before_description', $gallery, $data );

			// Get description.
			$description = $data['config']['description'];

			// If the WP_Embed class is available, use that to parse the content using registered oEmbed providers.
			if ( isset( $GLOBALS['wp_embed'] ) ) {
				$description = $GLOBALS['wp_embed']->autoembed( $description );
			}

			// Get the description and apply most of the filters that apply_filters( 'the_content' ) would use
			// We don't use apply_filters( 'the_content' ) as this would result in a nested loop and a failure.
			$description = wptexturize( $description );
			$description = convert_smilies( $description );
			$description = wpautop( $description );
			$description = prepend_attachment( $description );

			// Requires WordPress 4.4+
			if ( function_exists( 'wp_make_content_images_responsive' ) ) {
				$description = wp_make_content_images_responsive( $description );
			}

			// Append the description to the gallery output.
			$gallery .= $description;

			// Filter the gallery HTML.
			$gallery	 = apply_filters( 'envira_gallery_output_after_description', $gallery, $data );
		$gallery .= '</div>';

		return $gallery;
	}

   /**
	 * Outputs the gallery init script in the footer.
	 *
	 * @since 1.0.0
	 */
	public function gallery_init() {

		// envira_galleries stores all Fancybox instances
		// envira_isotopes stores all Isotope instances
		// envira_isotopes_config stores Isotope configs for each Gallery
		$envira_gallery_sort = json_encode( $this->gallery_sort );

		if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
			$envira_gallery_sort = json_encode( $this->gallery_sort, JSON_FORCE_OBJECT );
		}

		?>

		<script type="text/javascript">
			<?php ob_start(); ?>

			<?php

			$envira_javascript_var_init = 'var envira_gallery = [],
				envira_galleries = [],
				envira_gallery_images = {},
				envira_isotopes = [],
				envira_isotopes_config = [],
				envira_playing = false;';

			$envira_javascript_var_init = apply_filters('envira_gallery_javascript_var_init', $envira_javascript_var_init );

			echo $envira_javascript_var_init;

			?>

			var envira_gallery_sort = <?php echo $envira_gallery_sort; ?>,
				envira_gallery_options = {};

			jQuery(document).ready(function($){

				<?php
				do_action( 'envira_gallery_api_start_global' );

				foreach ( $this->data as $data ) {

					// Prevent multiple init scripts for the same gallery ID.
					if ( in_array( $data['id'], $this->done ) ) {
						continue;
					}

					// Assign the $envira_gallery_data_id from $data['id']
					// ...we do this to intercept any "odd" ids like * (used in dynamic/tags ) that JS wouldn't like
					$envira_gallery_data_id = false;
					if ( isset( $data['id'] ) ) {
						if ( $data['id'] == '*' || $data['id'] == 'tags_*' ) {
							$envira_gallery_data_id = 'tags_';
						} else {
							$envira_gallery_data_id = $data['id'];
						}
					}

					$this->done[] = (string) $envira_gallery_data_id;

					// if the user has selected a custom theme, only output the needed JS
					$gallery_theme = envira_get_config( 'justified_gallery_theme', $data );

					// in some cases, previous gallery using the old automattic layout aren't showing a row height, so just in case...	
					$justified_row_height = !$this->is_mobile ? envira_get_config( 'justified_row_height', $data ) : envira_get_config( 'mobile_justified_row_height', $data );

					// By default, we'll use the images in the Gallery DOM to populate the lightbox.
					// However, Addons (e.g. Pagination) may require us to give access to all images
					// in a Gallery, not just the paginated subset on screen.
					// Those Addons can populate this array now which will tell envirabox which images to use.
					$lightbox_images = apply_filters( 'envira_gallery_lightbox_images', false, $data );

					if ( is_array( $lightbox_images ) && ! empty( $this->gallery_sort[ $envira_gallery_data_id ] ) ) {

						$lightbox_images = array_replace( array_flip( $this->gallery_sort[ $envira_gallery_data_id ] ), $lightbox_images );
					}

					/* Get open and transition effects */
					$lightbox_open_close_effect = envira_get_config( 'lightbox_open_close_effect', $data );
					$lightbox_transition_effect = envira_get_config( 'effect', $data );

					/* Get standard effects */
					$lightbox_standard_effects = envira_get_transition_effects_values();

					$theme = $this->get_config( 'lightbox_theme', $data );

					do_action( 'envira_gallery_api_start', $data ); ?>

					<?php if ( $this->get_config( 'columns', $data ) > 0 && ! $this->get_config( 'isotope', $data ) ) :

						do_action( 'envira_gallery_api_no_enviratope', $data );

					endif; ?>

					envira_gallery['<?php echo $envira_gallery_data_id; ?>'] = {

						init: function(){

							<?php if ( $this->get_config( 'columns', $data ) == 0 ) : ?>

								this.justified_gallery();

								/* define when the trigger for loading images (lazy load) */

								$('#envira-gallery-<?php echo $data["id"]; ?>').justifiedGallery().on('jg.complete', function (e) {

									envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
									$(window).scroll(function(event){
										envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
									});

								});

								$( document ).on( "envira_pagination_ajax_load_completed", function() {
									 $('#envira-gallery-<?php echo $data["id"]; ?>').justifiedGallery().on('jg.complete', function (e) {

									 	envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
										$(window).scroll(function(event){
											envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
										});

									 });
								});

								<?php do_action( 'envira_gallery_api_justified', $data ); ?>

								<?php
								
								$gallery_theme = $this->get_config( 'justified_gallery_theme', $data );
								if ( $gallery_theme == 'js-desaturate' || $gallery_theme == 'js-threshold' || $gallery_theme == 'js-blur' || $gallery_theme == 'js-vintage' ) : ?>

								this.gallery_themes();

								<?php endif; ?>

								$('#envira-gallery-<?php echo $data["id"]; ?>').css('opacity', '1');

							<?php endif;

							if ( $this->get_config( 'columns', $data ) > 0 && $this->get_config( 'isotope', $data ) ) { ?>

								this.isotopes();

								/* define when the trigger for loading images (lazy load) */

								$('#envira-gallery-<?php echo $data["id"]; ?>').on( 'layoutComplete',
								  function( event, laidOutItems ) {
								  	envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
									$(window).scroll(function(event){
										envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
									});
								  }
								);

								$( document ).on( "envira_pagination_ajax_load_completed", function() {
									$('#envira-gallery-<?php echo $data["id"]; ?>').on( 'layoutComplete',
									  function( event, laidOutItems ) {
									  	envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
										$(window).scroll(function(event){
											envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
										});
									  }
									);
								});

							<?php } else if ( $this->get_config( 'columns', $data ) > 0 ) { ?>

								 	envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
								$(window).scroll(function(event){
									envira_gallery['<?php echo $envira_gallery_data_id; ?>'].load_images();
								});

							<?php }

							if ( $this->get_config( 'css_animations', $data ) ) :

								$opacity =	$this->get_config( 'css_opacity', $data ) == 0 ? 100 : ( $this->get_config( 'css_opacity', $data ) / 100 ); ?>

								envira_container_<?php echo $envira_gallery_data_id; ?> = $('#envira-gallery-<?php echo $envira_gallery_data_id; ?>').enviraImagesLoaded( function() {

									$('.envira-gallery-item img').fadeTo( 'slow', <?php echo $opacity; ?> );

								});

							<?php endif;

							if ( $this->get_config( 'lightbox_enabled', $data ) ) : ?>

							this.lightbox();

							<?php endif;

							do_action( 'envira_gallery_api_init', $data ); ?>

						},
						load_images: function(){

							<?php if ( $this->get_config( 'lazy_loading', $data ) ) { ?>

								if ( envira_gallery.debug !== undefined && envira_gallery.debug ) {
									console.log ('running: ' + '#envira-gallery-<?php echo $envira_gallery_data_id; ?>');
								}

								responsivelyLazy.run('#envira-gallery-<?php echo $envira_gallery_data_id; ?>');

							<?php } else { ?>
								if ( envira_gallery.debug !== undefined && envira_gallery.debug ) {
									console.log ('load_images was pinged, but lazy load turned off');
								}
							<?php } ?>

						},
						<?php if ( $this->get_config( 'columns', $data ) == 0 ) : ?>


						justified_gallery: function(){

							 $('#envira-gallery-<?php echo $data["id"]; ?>').enviraJustifiedGallery({
								 rowHeight : <?php echo $justified_row_height; ?>,
								 maxRowHeight: -1,
								 waitThumbnailsLoad: true,
								 selector: '> div > div',
								 lastRow: '<?php echo $this->get_config( 'justified_last_row', $data ) ? $this->get_config( 'justified_last_row', $data ) : 'nojustify'; ?>',
								 border: 0,
								 margins: <?php echo null !== $this->get_config( 'justified_margins', $data )  ? $this->get_config( 'justified_margins', $data ) : '1'; ?>,

								<?php do_action( 'envira_gallery_api_start_justified' ); ?>

							 });

						},
						justified_gallery_norewind: function(){

							 $('#envira-gallery-<?php echo $data["id"]; ?>').enviraJustifiedGallery('norewind');

						},
						<?php if ( $gallery_theme == 'js-desaturate' || $gallery_theme == 'js-threshold' || $gallery_theme == 'js-blur' || $gallery_theme == 'js-vintage' ) : ?>

						<?php $gallery_theme_suffix = ( $this->get_config( 'justified_gallery_theme_detail', $data ) ) == 'hover' ? '-hover' : false; ?>

						gallery_themes: function(){
							// Get IE or Edge browser version
							var ie_version = detectIE();
							$('#envira-gallery-<?php echo $data["id"]; ?>').on('jg.complete', function (e) {
								 if ( ie_version ) {
									 $('#envira-gallery-<?php echo $data["id"]; ?> img').each(function() {
										 var keep_id = $(this).attr('id');
										 $(this).attr('id', keep_id + '-effects' );
										 $(this).wrap('<div class="effect-wrapper" style="display:inline-block;width:' + this.width + 'px;height:' + this.height + 'px;">').clone().addClass('gotcolors').css({'position': 'absolute', 'opacity' : 0, 'z-index' : 1 }).attr('id', keep_id).insertBefore(this);
										 <?php

											 switch ($gallery_theme) {
												 case 'js-desaturate':
													 echo 'this.src = jg_effect_desaturate($(this).attr("src"));';
													 break;
												 case 'js-threshold':
													 echo 'this.src = jg_effect_threshold(this.src);';
													 break;
												 case 'js-blur':
													 echo 'this.src = jg_effect_blur(this.src);';
													 break;
												 case 'js-vintage':
													 echo 'jg_effect_vintage( this );';
													 break;
											 }

										 ?>
									 });
									 // $('#envira-gallery-<?php echo $data["id"]; ?> img').hover(
										//  function() {
										// 	 $(this).stop().animate({opacity: 1}, 200);
										//  },
										//  function() {
										// 	 $(this).stop().animate({opacity: 0}, 200);
										//  }
									 // );
									 // $('#envira-gallery-<?php echo $data["id"]; ?> .caption').hover(
										//  function() {
										// 	 $(this).parent().find('img').stop().animate({opacity: 1}, 200);
										//  },
										//  function() {
										// 	 $(this).parent().find('img').stop().animate({opacity: 0}, 200);
										//  }
									 // );
								 } /* end IE if */
								 else {

									 $('#envira-gallery-<?php echo $data["id"]; ?> img').hover(
										 function() {
										 	<?php if ( $gallery_theme_suffix ) { ?>
											 	$(this).addClass('envira-<?php echo $gallery_theme . $gallery_theme_suffix; ?>');
											 <?php } else { ?>
												$(this).removeClass('envira-<?php echo $gallery_theme . $gallery_theme_suffix; ?>');
											 <?php } ?>
										 },
										 function() {
											 $(this).addClass('envira-<?php echo $gallery_theme . $gallery_theme_suffix; ?>');
										 }
									 );
									 $('#envira-gallery-<?php echo $data["id"]; ?> .caption').hover(
										 function() {
										 	 <?php if ( $gallery_theme_suffix ) { ?>
											 	$(this).parent().find('img').addClass('envira-<?php echo $gallery_theme. '-on'; ?>');
											 <?php } else { ?>
												$(this).parent().find('img').removeClass('envira-<?php echo $gallery_theme. $gallery_theme_suffix; ?>');
											 <?php } ?>
										 },
										 function() {
										 	 $(this).parent().find('img').removeClass('envira-<?php echo $gallery_theme. '-on'; ?>');
											 $(this).parent().find('img').addClass('envira-<?php echo $gallery_theme. $gallery_theme_suffix; ?>');
										 }
									 );
								 }


							 });

						},

						<?php endif;

						endif;

						if ( $this->get_config( 'columns', $data ) > 0 && $this->get_config( 'isotope', $data ) ) : ?>

						isotopes: function(){
							envira_isotopes_config['<?php echo $envira_gallery_data_id; ?>'] = {
									  <?php do_action( 'envira_gallery_api_enviratope_config', $data ); ?>
									  itemSelector: '.envira-gallery-item',
									  masonry: {
												 columnWidth: '.envira-gallery-item'
											}

								  };
								  <?php
								  // Initialize Isotope
								  ?>
								  envira_isotopes['<?php echo $envira_gallery_data_id; ?>'] = envira_container_<?php echo $envira_gallery_data_id; ?> = $('#envira-gallery-<?php echo $envira_gallery_data_id; ?>').enviratope(envira_isotopes_config['<?php echo $envira_gallery_data_id; ?>']);
								  <?php
								  // Re-layout Isotope when each image loads
								  ?>
								  envira_isotopes['<?php echo $envira_gallery_data_id; ?>'].enviraImagesLoaded()
									  .done(function() {
											envira_isotopes['<?php echo $envira_gallery_data_id; ?>'].enviratope('layout');
									  })
									  .progress(function() {
											envira_isotopes['<?php echo $envira_gallery_data_id; ?>'].enviratope('layout');
									  });

							<?php do_action( 'envira_gallery_api_enviratope', $data ); ?>

						},

						<?php endif;

						if ( $this->get_config( 'lightbox_enabled', $data ) ) : ?>

						lightbox: function(){ <?php

	 					// By default, we'll use the images in the Gallery DOM to populate the lightbox.
						// However, Addons (e.g. Pagination) may require us to give access to all images
						// in a Gallery, not just the paginated subset on screen.
						// Those Addons can populate this array now which will tell envirabox which images to use.
						$lightbox_images = apply_filters( 'envira_gallery_lightbox_images', false, $data );

						if ( is_array( $lightbox_images ) && ! empty( $this->gallery_sort[ $envira_gallery_data_id ] ) ) {
							$lightbox_images = array_replace( array_flip( $this->gallery_sort[ $envira_gallery_data_id ] ), $lightbox_images );
						}

						$theme = $this->get_config( 'lightbox_theme', $data );

						?>
						envira_gallery_options['<?php echo $envira_gallery_data_id; ?>'] = {
							lightboxTheme: '<?php echo empty( $theme ) ? "base" : $theme; ?>',
							<?php do_action( 'envira_gallery_api_config', $data ); // Depreciated ?>
							<?php do_action( 'envira_gallery_api_envirabox_config', $data ); ?>
							<?php if ( ! $this->get_config( 'keyboard', $data ) ) : ?>
							keys: 0,
							<?php endif; ?>
							margin: <?php echo apply_filters( 'envirabox_margin', 30, $data ); ?>,
							padding: <?php echo apply_filters( 'envirabox_padding', 15, $data ); ?>,
							<?php if ( $this->get_config( 'supersize', $data ) ): ?>
							autoCenter: true,
							<?php endif; ?>
							arrows: <?php echo apply_filters( 'envirabox_arrows', $this->get_config( 'arrows', $data ), $data ); ?>,
							aspectRatio: <?php echo $this->get_config( 'aspect', $data ); ?>,
							loop: <?php echo $this->get_config( 'loop', $data ); ?>,
							mouseWheel: <?php echo $this->get_config( 'mousewheel', $data ); ?>,
							preload: 1,
							<?php if ( $this->get_config( 'videos_enlarge', $data ) ) { ?>
							enviraVideoFitToView: true,
							<?php } ?>

							<?php
							/* Get open and transition effects */
							$lightbox_open_close_effect = envira_get_config( 'lightbox_open_close_effect', $data );
							$lightbox_transition_effect = envira_get_config( 'effect', $data );

							/* Get standard effects */
							$lightbox_standard_effects =	 envira_get_transition_effects_values();

							/* If open/close is standard, use openEffect, closeEffect */
							if ( in_array( $lightbox_open_close_effect, $lightbox_standard_effects ) ) {
								?>
								openEffect: '<?php echo $lightbox_open_close_effect; ?>',
								closeEffect: '<?php echo $lightbox_open_close_effect; ?>',
								<?php
							} else {

								// easing effects have been depreciated, and will default back to 'swing'

								?>
								openEffect: 'elastic', /* FancyBox default is fade, should be elastic for the openEffect/closeEffect functions */
								closeEffect: 'elastic',
								openEasing: 'swing', /* <?php echo ( $lightbox_open_close_effect == "Swing" ? "swing" : "easeIn" . $lightbox_open_close_effect ); ?>', */
								closeEasing: 'swing', /* <?php echo ( $lightbox_open_close_effect == "Swing" ? "swing" : "easeOut" . $lightbox_open_close_effect ); ?>', */
								openSpeed: <?php echo apply_filters( 'envirabox_open_speed', 500, $data ); ?>,
								closeSpeed: <?php echo apply_filters( 'envirabox_close_speed', 500, $data ); ?>,
								<?php
							}

							/* If transition effect is standard, use nextEffect, prevEffect */
							if ( in_array( $lightbox_transition_effect, $lightbox_standard_effects ) ) {
								?>
								nextEffect: '<?php echo $lightbox_transition_effect; ?>',
								prevEffect: '<?php echo $lightbox_transition_effect; ?>',
								<?php
							} else {

								// easing effects have been depreciated, and will default back to 'swing'

								?>
								nextEasing: 'swing', /* '<?php echo ( $lightbox_transition_effect == "Swing" ? "swing" : "easeIn" . $lightbox_transition_effect ); ?>', */
								prevEasing: 'swing', /* '<?php echo ( $lightbox_transition_effect == "Swing" ? "swing" : "easeOut" . $lightbox_transition_effect ); ?>', */
								nextSpeed: <?php echo apply_filters( 'envirabox_next_speed', 600, $data ); ?>,
								prevSpeed: <?php echo apply_filters( 'envirabox_prev_speed', 600, $data ); ?>,
								<?php
							}
							?>
							tpl: {
								wrap	 : '<?php echo $this->get_lightbox_template( $data ); ?>',
								image	 : '<img class="envirabox-image" src="{href}" alt="" data-envira-title="" data-envira-caption="" data-envira-index="" data-envira-data="" />',
								iframe	 : '<iframe id="envirabox-frame{rnd}" name="envirabox-frame{rnd}" class="envirabox-iframe" frameborder="0" vspace="0" hspace="0" allowtransparency="true" wekitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
								error	 : '<p class="envirabox-error"><?php echo __( 'The requested content cannot be loaded.<br/>Please try again later.</p>', 'envira-gallery' ); ?>',
								closeBtn : '<a title="<?php echo __( 'Close', 'envira-gallery' ); ?>" class="envirabox-item envirabox-close" href="#"></a>',
								next	 : '<a title="<?php echo __( 'Next', 'envira-gallery' ); ?>" class="envirabox-nav envirabox-next envirabox-arrows-<?php echo ( $this->get_config( 'lightbox_theme', $data ) != 'base_dark' && $this->get_config( 'lightbox_theme', $data ) != 'base_light' ? $this->get_config( 'arrows_position', $data ) : 'inside' ); ?> envirabox-nav-<?php echo $this->get_config( 'lightbox_theme', $data ); ?> <?php echo isset( $data['config']['supersize'] ) && $data['config']['supersize'] == 1 ? 'supersize' : ''; ?>" href="#"><span></span></a>',
								prev	 : '<a title="<?php echo __( 'Previous', 'envira-gallery' ); ?>" class="envirabox-nav envirabox-prev envirabox-arrows-<?php echo ( $this->get_config( 'lightbox_theme', $data ) != 'base_dark' && $this->get_config( 'lightbox_theme', $data ) != 'base_light' ? $this->get_config( 'arrows_position', $data ) : 'inside' ); ?> envirabox-nav-<?php echo $this->get_config( 'lightbox_theme', $data ); ?> <?php echo isset( $data['config']['supersize'] ) && $data['config']['supersize'] == 1 ? 'supersize' : ''; ?>" href="#"><span></span></a>'
								<?php do_action( 'envira_gallery_api_templates', $data ); ?>
							},

							helpers: {
								<?php
								do_action( 'envira_gallery_api_helper_config', $data );
								// Grab title display
								$title_display = $this->get_config( 'title_display', $data );
								if ( $title_display == 'float_wrap' ) {
									$title_display = 'float';
								}
								?>
								title: {
									<?php do_action( 'envira_gallery_api_title_config', $data ); ?>
									type: '<?php echo apply_filters( 'envira_gallery_title_type', $title_display, $data ); ?>',
									alwaysShow: '<?php echo apply_filters( 'envira_always_show_title', false, $data ); ?>',
								},
								<?php if ( $this->get_config( 'thumbnails', $data ) ) : ?>
								<?php
								$mobile_thumbnails_width = $this->get_config( 'mobile_thumbnails_width', $data ) ? $this->get_config( 'mobile_thumbnails_width', $data ) : 75;
								$mobile_thumbnails_height = $this->get_config( 'mobile_thumbnails_height', $data ) ? $this->get_config( 'mobile_thumbnails_height', $data ) : 50;
								?>
								thumbs: {
									width: <?php echo apply_filters( 'envira_gallery_lightbox_thumbnail_width', $this->get_config( 'thumbnails_width', $data ), $data ); ?>,
									height: <?php echo apply_filters( 'envira_gallery_lightbox_thumbnail_height', $this->get_config( 'thumbnails_height', $data ), $data ); ?>,
									mobile_thumbs: <?php echo apply_filters( 'envira_gallery_mobile_lightbox_thumbnails', $this->get_config( 'mobile_thumbnails', $data ), $data ); ?>,
									mobile_width: <?php echo apply_filters( 'envira_gallery_mobile_lightbox_thumbnail_width', $mobile_thumbnails_width, $data ); ?>,
									mobile_height: <?php echo apply_filters( 'envira_gallery_mobile_lightbox_thumbnail_height', $mobile_thumbnails_height, $data ); ?>,
									source: function(current) {
										if ( typeof current.element == 'undefined' ) {
											return current.thumbnail;
										} else {
											return $(current.element).data('thumbnail');
										}
									},
									mobileSource: function(current) {
										if ( typeof current.element == 'undefined' ) {
											return current.mobile_thumbnail;
										} else {
											return $(current.element).data('mobile-thumbnail');
										}
									},
									dynamicMargin: <?php echo apply_filters( 'envirabox_dynamic_margin', 'false', $data ); ?>,
									dynamicMarginAmount: <?php echo apply_filters( 'envirabox_dynamic_margin_amount', 0, $data ); ?>,
									position: '<?php echo apply_filters( 'envirabox_gallery_thumbs_position', $this->get_config( 'thumbnails_position', $data ), $data ); ?>',
								},
								<?php endif; ?>
								<?php if ( $this->get_config( 'toolbar', $data ) ) : ?>
								buttons: {
									tpl: '<?php echo $this->get_toolbar_template( $data ); ?>',
									position: '<?php echo $this->get_config( 'toolbar_position', $data ); ?>',
									padding: '<?php echo ( ( $this->get_config( 'toolbar_position', $data ) == 'bottom' && $this->get_config( 'thumbnails', $data ) && $this->get_config( 'thumbnails_position', $data ) == 'bottom' ) ? true : false ); ?>'
								},
								slideshow: {
									skipSingle: true
								},
								<?php else: ?>
								slideshow: {
									skipSingle: true
								},
								<?php endif; ?>
								navDivsRoot: <?php echo apply_filters( 'envirabox_nav_divs_root', 'false', $data ); ?>,
								actionDivRoot: <?php echo apply_filters( 'envirabox_action_divs_root', 'false', $data ); ?>,
							},
							<?php do_action( 'envira_gallery_api_config_callback', $data ); ?>
							beforeLoad: function(){


								<?php

								if ( ! $lightbox_images ) {

									// Show caption BUT if the user has overrided with the title, show that instead
									if ( !$this->get_config( 'lightbox_title_caption', $data ) || $this->get_config( 'lightbox_title_caption', $data ) != 'title' ) { ?>
										var lightbox_caption = $(this.element).data('envira-lightbox-caption').toString();
										if ( lightbox_caption !== undefined && lightbox_caption.length > 0 ) {
											// this.title = $(this.element).data('envira-lightbox-caption');
											lightbox_caption = lightbox_caption.replace(/<br\s*[\/]?>/gi, "\n");
											lightbox_caption = $('<div/>').append( lightbox_caption ).text();
											this.title = lightbox_caption.replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ '<br/>' +'$2');											
											
										} else {
											this.title = '';
										}
		
									<?php } else { ?>
										
										this.title = $('<div/>').append( $(this.element).attr('title') ).text(); 
										
									<?php } ?>

								<?php

								} else {

									// Show caption BUT if the user has overrided with the title, show that instead
									if ( !$this->get_config( 'lightbox_title_caption', $data ) || $this->get_config( 'lightbox_title_caption', $data ) != 'title' ) { ?>
										this.title = this.group[ this.index ].caption;
									<?php } else { ?>
										this.title = this.group[ this.index ].title;
									<?php }

								} ?>

								<?php do_action( 'envira_gallery_api_before_load', $data ); ?>
							},
							afterLoad: function(){
								$('.envirabox-overlay-fixed').on({
									'touchmove' : function(e){
										e.preventDefault();
									}
								});

								<?php do_action( 'envira_gallery_api_after_load', $data ); ?>

								/* if this is a video, enable overfolow:hidden on envira-inner */
								$.extend(this, {
									scrolling		: 'no'
								});


								<?php if ( $this->get_config( 'supersize', $data ) ): ?>
								/*$.extend(this, {
									width		: '100%',
									height		: '100%'
								});*/
								<?php endif; ?>
							},
							beforeShow: function(){
								$(window).on({
									'resize.envirabox' : function(){
										$.envirabox.update();
									}
								});



								<?php
								// Set data attributes on the lightbox image, based on either
								// the image in the DOM or (if $lightbox_images defined) the image
								// from $lightbox_images

								// Another issue: index will show wrong image if there is a random sort

								?>
								if ( typeof this.element === 'undefined' ) {
									<?php
									// Using $lightbox_images
									?>
									var gallery_id = this.group[ this.index ].gallery_id,
										gallery_item_id = this.group[ this.index ].id,
										alt = this.group[ this.index ].alt,
										title = this.group[ this.index ].title,
										caption = this.group[ this.index ].caption,
										index = this.index,
										full_sized_image = this.group[ this.index ].href;

								} else {
									<?php
									// Using image from DOM
									// Get a bunch of data attributes from clicked image link
									// is there lazy load? if so we have to jump two parents() instead of just one parent()
  									if ( $this->get_config( 'lazy_loading', $data ) && $this->get_config( 'columns', $data ) > 0 ) { ?>
 										var parent_element = this.element.find('img').parent().parent();
 									<?php } else { ?>
 										var parent_element = this.element.find('img').parent();
 									<?php } ?>
									var gallery_id = this.element.find('img').data('envira-gallery-id');
									var gallery_item_id = this.element.find('img').data('envira-item-id');
									var alt = this.element.find('img').attr('alt');
									var title = parent_element.attr('title');
									var caption = parent_element.data('envira-caption');
									var retina_image = parent_element.data('envira-retina');
									var index = this.element.find('img').data('envira-index');
									var src = this.element.find('img').attr('src');
									var full_sized_image = this.element.find('img').attr('data-envira-fullsize-src');

								}

								<?php
								// Set alt, data-envira-title, data-envira-caption and data-envira-index attributes on Lightbox image
								?>
								this.inner.find('img').attr('alt', alt)
													  .attr('data-envira-gallery-id', gallery_id)
													  .attr('data-envira-fullsize-src', full_sized_image)
													  .attr('data-envira-item-id', gallery_item_id)
													  .attr('data-envira-title', title)
													  .attr('data-envira-caption', caption)
													  .attr('data-envira-index', index);

								<?php
								// Problem w/ above: there might not BE an image if we are working with embedded videos, so
								// proposing appending this information to envirabox-overlay
								?>

								$('.envirabox-wrap').attr('alt', alt)
													.attr('data-envira-gallery-id', gallery_id)
													.attr('data-envira-fullsize-src', full_sized_image)
													.attr('data-envira-item-id', gallery_item_id)
													.attr('data-envira-title', title)
													.attr('data-envira-caption', caption)
													.attr('data-envira-index', index)
													.attr('data-envira-src', src);

								<?php
								// Set retina image srcset if specified
								?>
								if ( typeof retina_image !== 'undefined' && retina_image !== '' ) {
									this.inner.find('img').attr('srcset', retina_image + ' 2x');
								}

								<?php
								// Custom lightbox themes
								// -- Insert theme slug into overlay <div> css

								?>

								$('.envirabox-overlay').addClass( 'overlay-<?php echo $this->get_config( 'lightbox_theme', $data ); ?>' );

								<?php
								// Using Video Addon?
								// -- Insert slug into overlay <div> css

								?>

								$('.envirabox-overlay').addClass( 'overlay-video' );

								<?php
								// Proofing addon - if there's a current order, we need to add the class here
								?>

								<?php if ( $this->get_config( 'proofing', $data ) ) { ?>

									 if ( $('#envira-gallery-' + gallery_id).hasClass('envira-proofing-no-order') ) {
									 	$('.envirabox-overlay').addClass( 'envira-proofing-no-order' );
									 } else if ( $('#envira-gallery-' + gallery_id).hasClass('envira-proofing-yes-order') ) {
										$('.envirabox-overlay').addClass( 'envira-proofing-yes-order' );
									 }

								<?php } ?>

								<?php if ( $this->get_config( 'thumbnails', $data ) ) : ?>
								$('.envirabox-overlay').addClass('envirabox-thumbs');
								<?php endif; ?>

								<?php if ( $this->get_config( 'dynamic', $data ) ) : ?>
								$('.envirabox-wrap').addClass('envira-dynamic-gallery');
								<?php endif; ?>

								<?php do_action( 'envira_gallery_api_before_show', $data ); ?>

								var overlay_supersize = <?php echo $this->get_config( 'supersize', $data ) ? 'true' : 'false'; ?>;
								if(overlay_supersize) {
									$('.envirabox-overlay').addClass( 'overlay-supersize' );
									$('#envirabox-thumbs').addClass( 'thumbs-supersize' );
								}
								$('.envira-close').click(function(event) {
									event.preventDefault();
									$.envirabox.close();
								});
							},
							afterShow: function(){


								<?php
								if ( $this->get_config( 'mobile_touchwipe', $data ) ) {
									?>

									if ( $('#envirabox-thumbs ul li').length > 0 ) {

										$('#envirabox-thumbs').swipe( {
											excludedElements:".noSwipe",
											swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
												if (direction === 'left' && fingerCount <= 1 ) {
													$.envirabox.next( direction );
												} else if (direction === 'left' && fingerCount > 1 ) {
													$.envirabox.jumpto( 0 );
												} else if (direction === 'right' && fingerCount <= 1 ) {
													$.envirabox.prev( direction );
												} else if (direction === 'right' && fingerCount > 1 ) {
													$.envirabox.jumpto( sizeof( $('#envirabox-thumbs ul li').length ) );
												}
											}
										} );

									}

									$('.envirabox-wrap, .envirabox-wrap a.envirabox-nav').swipe( {
										excludedElements:"label, button, input, select, textarea, .noSwipe",
										swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
											if (direction === 'left') {
												$.envirabox.next(direction);
											} else if (direction === 'right') {
												$.envirabox.prev(direction);
											} else if (direction === 'up') {
												<?php
												if ( $this->get_config( 'mobile_touchwipe_close', $data ) ) {
													?>
													$.envirabox.close();
													<?php
												}
												?>
											}
										}
									} );
									<?php
								}

								?>


								<?php

								// If title helper = float_wrap, add a CSS class so we can disable word-wrap
								if ( $this->get_config( 'title_display', $data ) == 'float_wrap' ) {
									?>
									if ( typeof this.helpers.title !== 'undefined' ) {
										if ( ! $( 'div.envirabox-title' ).hasClass( 'envirabox-title-text-wrap' ) ) {
											$( 'div.envirabox-title' ).addClass( 'envirabox-title-text-wrap' );
										}
									}
									<?php
								}

								do_action( 'envira_gallery_api_after_show', $data ); ?>

								var overlay_supersize = <?php echo $this->get_config( 'supersize', $data ) ? 'true' : 'false'; ?>;
								if(overlay_supersize) {
									$('#envirabox-thumbs').addClass( 'thumbs-supersize' );
								}

							},
							beforeClose: function(){
								<?php do_action( 'envira_gallery_api_before_close', $data ); ?>
							},
							afterClose: function(){
								$(window).off('resize.envirabox');
								<?php do_action( 'envira_gallery_api_after_close', $data ); ?>
							},
							onUpdate: function(){

								<?php
								if ( $this->get_config( 'toolbar', $data ) ) : ?>
								var envira_buttons_<?php echo $envira_gallery_data_id; ?> = $('#envirabox-buttons li').map(function(){
									return $(this).width();
								}).get(),
									envira_buttons_total_<?php echo $envira_gallery_data_id; ?> = 0;
								$.each(envira_buttons_<?php echo $envira_gallery_data_id; ?>, function(i, val){
									envira_buttons_total_<?php echo $envira_gallery_data_id; ?> += parseInt(val, 10);
								});

								envira_buttons_total_<?php echo $envira_gallery_data_id; ?> += 1;

								$('#envirabox-buttons ul').width(envira_buttons_total_<?php echo $envira_gallery_data_id; ?>);

								<?php

								// Position based on lightbox theme

								$lightbox_theme = $this->get_config( 'lightbox_theme', $data );
								if ( $lightbox_theme == 'modern-dark' || $lightbox_theme == 'modern-light' ) {

								?>

								$('#envirabox-buttons').width(envira_buttons_total_<?php echo $envira_gallery_data_id; ?>).css('left', ($(window).width() - envira_buttons_total_<?php echo $envira_gallery_data_id; ?>) - 100);

								<?php } else { ?>

								$('#envirabox-buttons').width(envira_buttons_total_<?php echo $envira_gallery_data_id; ?>).css('left', ($(window).width() - envira_buttons_total_<?php echo $envira_gallery_data_id; ?>)/2);

								<?php } ?>

								<?php endif; ?>

								<?php do_action( 'envira_gallery_api_on_update', $data ); ?>

							},
							onCancel: function(){
								<?php do_action( 'envira_gallery_api_on_cancel', $data ); ?>
							},
							onPlayStart: function(){
								<?php do_action( 'envira_gallery_api_on_play_start', $data ); ?>
							},
							onPlayEnd: function(){
								<?php do_action( 'envira_gallery_api_on_play_end', $data ); ?>
							}
						};
						<?php

						if ( ! $lightbox_images ) {
							// No lightbox images specified, so use images from DOM
							?>
							envira_galleries['<?php echo $envira_gallery_data_id; ?>'] = $('.envira-gallery-<?php echo $envira_gallery_data_id; ?>').envirabox( envira_gallery_options['<?php echo $envira_gallery_data_id; ?>'] );
							<?php

						} else {
							// Use images from $lightbox_images
							add_filter( 'envira_minify_strip_double_forward_slashes', '__return_false' );
							?>
							envira_gallery_images['<?php echo $envira_gallery_data_id; ?>'] = [];
							<?php
							// Build a JS array of all images
							$count = 0;
							foreach ( $lightbox_images as $image_id => $image ) {
								// If no image ID exists, skip
								if ( empty( $image_id ) ) {
									continue;
								}
								$thumb =  $image['thumb'] != '' ? $image['thumb'] : $image['src']; 
								?>
								envira_gallery_images['<?php echo $envira_gallery_data_id; ?>'].push({
									href: '<?php echo $image['src']; ?>',
									gallery_id: '<?php echo $envira_gallery_data_id; ?>',
									id: <?php echo $image_id; ?>,
									alt: '<?php echo addslashes( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', $image['alt'] ) ); ?>',
									caption: '<?php echo addslashes( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', $image['caption'] ) ); ?>',
									title: '<?php echo addslashes( str_replace( array("\r\n","\r","\n","\\r","\\n","\\r\\n"), '<br />', $image['title'] ) ); ?>',
									index: <?php echo $count; ?>,
									thumbnail: '<?php echo  $thumb; ?>',
									mobile_thumbnail: '<?php echo ( isset( $image['mobile_thumb'] ) ? $image['mobile_thumb'] : $image['src'] ); ?>'
									<?php do_action( 'envira_gallery_api_lightbox_image_attributes', $image, $image_id, $lightbox_images, $data ); ?>
								});
								<?php
								$count++;
							}

							// Open envirabox when an image is clicked, telling envirabox which images are available to it.
							?>
							envira_galleries['<?php echo $envira_gallery_data_id; ?>'] = $('.envira-gallery-<?php echo $envira_gallery_data_id; ?>').envirabox( envira_gallery_options['<?php echo $envira_gallery_data_id; ?>'], envira_gallery_images['<?php echo $envira_gallery_data_id; ?>'] );
							$('#envira-gallery-wrap-<?php echo $envira_gallery_data_id; ?>').on('click', 'a.envira-gallery-link', function(e) {
								e.preventDefault();
								e.stopPropagation();
								$.envirabox.close();
								if ( $('#envira-gallery-wrap-<?php echo $envira_gallery_data_id; ?> div.envira-pagination').length > 0 ) { /* this exists, perhaps pagination doesn't display nav because it's only one page? */
									var envirabox_page = ( $('#envira-gallery-wrap-<?php echo $envira_gallery_data_id; ?> div.envira-pagination').data('page') - 1 );
								} else {
									var envirabox_page = 0;
								}

								if ( $('#envira-gallery-wrap-<?php echo $envira_gallery_data_id; ?> div.envira-pagination').length > 0 ) { /* this exists, perhaps pagination doesn't display nav because it's only one page? */
									var envirabox_per_page = $('#envira-gallery-wrap-<?php echo $envira_gallery_data_id; ?> div.envira-pagination').data('per-page');
								} else {
									var envirabox_per_page = $('.envira-gallery-image').length;
								}

								var envirabox_index = ( Number($('img', $(this)).data('envira-index')) - 1 );
								envira_gallery_options['<?php echo $envira_gallery_data_id; ?>'].index = ((envirabox_page * envirabox_per_page) + envirabox_index);
								envira_galleries['<?php echo $envira_gallery_data_id; ?>'] = $.envirabox( envira_gallery_images['<?php echo $envira_gallery_data_id; ?>'], envira_gallery_options['<?php echo $envira_gallery_data_id; ?>'] );
							});
							<?php
						}




							do_action( 'envira_gallery_api_lightbox', $data ); ?>

						},

						<?php endif; ?>

					};

					if ( envira_gallery['<?php echo $envira_gallery_data_id; ?>'] !== undefined ) {

						envira_gallery['<?php echo $envira_gallery_data_id; ?>'].init();

					}

					<?php

					do_action( 'envira_gallery_api_end', $data );

				} // foreach

				do_action( 'envira_gallery_api_end_global', $this->data );

				?>
			});

			<?php
			// Minify before outputting to improve page load time.
			if ( defined( 'ENVIRA_DEBUG' ) && ENVIRA_DEBUG	){

			 	echo ob_get_clean();

			} else {

				echo envira_minify( ob_get_clean() );

			} ?>
		</script>
		<?php

	}

	/**
	 * Loads a custom gallery display theme.
	 *
	 * @since 1.7.0
	 *
	 * @param string $theme The custom theme slug to load.
	 */
	public function load_gallery_theme( $theme ) {

		// Loop through the available themes and enqueue the one called.
		foreach ( envira_get_gallery_themes() as $array => $data ) {
			if ( $theme !== $data['value'] ) {
				continue;
			}

			if ( file_exists( plugin_dir_path( $data['file'] ) . 'themes/' . $theme . '/style.css' ) ) {
				wp_enqueue_style( ENVIRA_SLUG . $theme . '-theme', plugins_url( 'themes/' . $theme . '/style.css', $data['file'] ), array( ENVIRA_SLUG . '-style' ) );
			}
			else {
				wp_enqueue_style( ENVIRA_SLUG . $theme . '-theme', plugins_url( 'themes/' . $theme . '/css/style.css', $data['file'] ), array( ENVIRA_SLUG . '-style' ) );
			}
			break;
		}

	}

	/**
	 * Set the title display per theme
	 *
	 * @since 1.7.0
	 *
	 * @param string|int this is either a string or an integer and can be set accordingly.
	 */
	public function envira_gallery_title_type( $title_display, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$title_display = 'fixed';
				break;

		}

		return $title_display;

	}

	/**
	 * Set the title display per theme
	 *
	 * @since 1.7.0
	 *
	 * @param string|int this is either a string or an integer and can be set accordingly.
	 */
	public function envira_always_show_title( $show, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$show = true;
				break;

		}

		return $show;

	}

	/**
	 * Set the margin
	 *
	 * @since 1.7.0
	 *
	 * @param string|int this is either a string or an integer and can be set accordingly.
	 */
	public function envirabox_margin( $margin, $data ) {

		if ( envira_get_config( 'supersize', $data ) ) {
			// if this is mobile and there no thumbnails on a light/dark theme, then add a negative value to the top
			if ( $this->is_mobile && !envira_get_config( 'mobile_thumbnails', $data ) && ( 'base_dark' == envira_get_config( 'lightbox_theme', $data ) || 'base_light' == envira_get_config( 'lightbox_theme', $data ) ) ) {
				$margin = '[-30,0,0,0]';
			} else {
				$margin = 0;
			}
		}

		if ( envira_get_config( 'arrows_position', $data ) == 'outside' ) {
			$margin = $margin + 50;
		}

		return $margin;

	}


	/**
	 * Set the margin
	 *
	 * @since 1.7.0
	 *
	 * @param string|int this is either a string or an integer and can be set accordingly.
	 */
	public function envirabox_padding( $padding, $data ) {

		if ( envira_get_config( 'supersize', $data ) ) {
			$padding = 0;
		}

		return $padding;

	}

	/**
	 * Set the arrows
	 *
	 * @since 1.7.0
	 *
	 * @param string|int this is either a string or an integer and can be set accordingly.
	 */
	public function envirabox_arrows( $arrows, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$arrows = 'true';
				break;

		}

		return $arrows;

	}

	/**
	 * envirabox_gallery_thumbs_position function.
	 *
	 * @access public
	 * @param mixed $position
	 * @param mixed $data
	 * @return void
	 */
	public function envirabox_gallery_thumbs_position( $position, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$position = 'bottom';
				break;

		}

		return $position;
	}

	/**
	 * Enable the dynamic margin based on the theme
	 *
	 * @since 1.7.0
	 *
	 * @param string $margin This must be a string, not a boolean since its going directly into JS.
	 */
	public function envirabox_dynamic_margin( $margin, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$margin = 'true';
				break;

		}

		return $margin;

	}

	/**
	 * Set the Dynamic Margin amount per theme
	 *
	 * @since 1.7.0
	 *
	 * @param int $amount the dynamic margin amount.
	 */
	public function envirabox_dynamic_margin_amount( $amount, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		switch ( $lightbox_theme ) {

			case 'base_dark':
				$amount = 0;
				break;

		}

		return $amount;

	}

	/**
	 * envirabox_inner_above function.
	 *
	 * @access public
	 * @param mixed $template
	 * @param mixed $data
	 * @return void
	 */
	public function envirabox_inner_above( $template, $data ) {

		// Get gallery theme
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );
		$class = false;
		if ( ( isset( $data['config']['supersize'] ) && $data['config']['supersize'] == 1 ) ) {
			$class = 'supersize';
		}

		return $template .= '<div class="envirabox-actions ' . $lightbox_theme . ' ' . $class . '">' . apply_filters( 'envirabox_actions', '', $data ) . '</div>';
	}

	/**
	 * envirabox_actions function.
	 *
	 * @access public
	 * @param mixed $template
	 * @param mixed $data
	 * @return void
	 */
	public function envirabox_actions( $template, $data ) {

		// Check if Supersize is enabled
		if ( ! in_array( envira_get_config( 'lightbox_theme', $data ), array( 'base_dark' ) ) ) {
			return $template;
		}

		// Build Button
		$button = '<div class="envira-close-button"><a title="' . __( 'Close', 'envira-gallery' ) . '" class="envirabox-item envira-close" href="#"></a></div>';

		// Return
		return $template . $button;
	}

	/**
	 * adds the supersize class to the template.
	 *
	 * @since 1.1.2
	 *
	 * @param string $css_classes the classes near envirabox-wrap.
	 * @param array $data Data for the Envira gallery.
	 * @return string added envira-supersize to the classes.
	 */
	public function envira_supersize_wrap_css_class( $css_classes, $data ) {

		if ( empty( $data['config']['supersize'] ) ) {
			return $css_classes;
		}

		return $css_classes . ' envira-supersize';

	}

	/**
	 * Loads a custom gallery lightbox theme.
	 *
	 * @since 1.7.0
	 *
	 * @param string $theme The custom theme slug to load.
	 */
	public function load_lightbox_theme( $theme ) {

		// Loop through the available themes and enqueue the one called.
		foreach ( envira_get_lightbox_themes() as $array => $data ) {
			if ( $theme !== $data['value'] ) {
				continue;
			}

			if ( file_exists( plugin_dir_path( $data['file'] ) . 'themes/' . $theme . '/style.css' ) ) {
				wp_enqueue_style( ENVIRA_SLUG . $theme . '-theme', plugins_url( 'themes/' . $theme . '/style.css', $data['file'] ), array( ENVIRA_SLUG . '-style' ) );
			}
			else {
				wp_enqueue_style( ENVIRA_SLUG . $theme . '-theme', plugins_url( 'themes/' . $theme . '/css/style.css', $data['file'] ), array( ENVIRA_SLUG . '-style' ) );
			}
			break;
		}

	}

	/**
	 * Helper method for adding custom gallery classes.
	 *
	 * @since 1.7.0
	 *
	 * @param array $data The gallery data to use for retrieval.
	 * @return string		 String of space separated gallery classes.
	 */
	public function get_gallery_classes( $data ) {

		// Set default class.
		$classes	 = array();
		$classes[] = 'envira-gallery-wrap';

		// Add custom class based on data provided.
		$classes[] = 'envira-gallery-theme-' . envira_get_config( 'gallery_theme', $data );
		$classes[] = 'envira-lightbox-theme-' . envira_get_config( 'lightbox_theme', $data );

		// If we have custom classes defined for this gallery, output them now.
		foreach ( (array) envira_get_config( 'classes', $data ) as $class ) {
			$classes[] = $class;
		}

		// If the gallery has RTL support, add a class for it.
		if ( envira_get_config( 'rtl', $data ) ) {
			$classes[] = 'envira-gallery-rtl';
		}

		// Allow filtering of classes and then return what's left.
		$classes = apply_filters( 'envira_gallery_output_classes', $classes, $data );
		return trim( implode( ' ', array_map( 'trim', array_map( 'sanitize_html_class', array_unique( $classes ) ) ) ) );

	}

	/**
	 * Helper method for adding custom gallery classes.
	 *
	 * @since 1.0.4
	 *
	 * @param array $item Array of item data.
	 * @param int $i		The current position in the gallery.
	 * @param array $data The gallery data to use for retrieval.
	 * @return string		 String of space separated gallery item classes.
	 */
	public function get_gallery_item_classes( $item, $i, $data ) {

		// Set default class.
		$classes	 = array();
		$classes[] = 'envira-gallery-item';
		$classes[] = 'enviratope-item';
		$classes[] = 'envira-gallery-item-' . $i;
		if ( isset( $item['video_in_gallery'] ) && $item['video_in_gallery'] == 1 ) {
			$classes[] = 'envira-video-in-gallery';
		}

		// If lazy load exists, add that
		if ( isset( $data['config']['lazy_loading'] ) && $data['config']['lazy_loading'] ) {
			$classes[] = 'envira-lazy-load';
		}

		// Allow filtering of classes and then return what's left.
		$classes = apply_filters( 'envira_gallery_output_item_classes', $classes, $item, $i, $data );
		return trim( implode( ' ', array_map( 'trim', array_map( 'sanitize_html_class', array_unique( $classes ) ) ) ) );

	}

	/**
	 * Changes the link attribute of an image, if the Lightbox config
	 * requires a different sized image to be displayed.
	 *
	 * @since 1.3.6
	 *
	 * @param int $id		  The image attachment ID to use.
	 * @param array $item	Gallery item data.
	 * @param array $data	The gallery data to use for retrieval.
	 * @return array		 Image array
	 */
	public function maybe_change_link( $id, $item, $data ) {

		// Check gallery config
		$image_size = envira_get_config( 'lightbox_image_size', $data );

		//check if the url is a valid image if not return it
		if (! envira_is_image( $item['link'] ) ) {
			return $item;
		}

		// Get media library attachment at requested size
		$image = wp_get_attachment_image_src( $id, $image_size );

		// Determine if the image url resides on a third-party site
 		$url = preg_replace('/(?:https?:\/\/)?(?:www\.)?(.*)\/?$/i', '$1', network_site_url() );
  		if ( strpos($item['link'], $url) === false	){
  		 	return $item;
  		}

		if ( ! is_array( $image ) ) {
			return $item;
		}

		// Inject new image size into $item
		$item['link'] = $image[0];

		// Return
		return $item;

	}

	/**
	 * Helper method to retrieve the proper image src attribute based on gallery settings.
	 *
	 * @since 1.7.0
	 *
	 * @param int		$id			The image attachment ID to use.
	 * @param array		$item		Gallery item data.
	 * @param array		$data		The gallery data to use for retrieval.
	 * @param bool		$this->is_mobile		Whether or not to retrieve the mobile image.
	 * @param bool		$retina		Whether to return a retina sized image.
	 * @return string				The proper image src attribute for the image.
	 */
	public function get_image_src( $id, $item, $data, $mobile = false, $retina = false ) {

		// Define variable
		$src = false;

		// Define type (mobile or not)
		$type = $this->is_mobile ? 'mobile' : 'crop'; // 'crop' is misleading here - it's the key that stores the thumbnail width + height

		// If this image is an instagram, we grab the src and don't use the $id
		// otherwise using the $id refers to a postID in the database and has been known
		// at times to pull up the wrong thumbnail instead of the instagram one

		$instagram = false;

		if ( !empty( $item['src']) && strpos( $item['src'], 'cdninstagram' ) !== false ) :
			// using 'cdninstagram' because it seems all urls contain it - but be watchful in the future
			$instagram	= true;
			$src		= $item['src'];
			$image		= $item['src'];
		endif;

		$image_size = envira_get_config( 'image_size', $data );

		if ( !$src ) :

			if ( envira_get_config( 'crop', $data ) || $image_size == 'full' ){

				$src = apply_filters( 'envira_gallery_retina_image_src', wp_get_attachment_image_src( $id, 'full' ), $id, $item, $data, $this->is_mobile );

			}	elseif ( $image_size != 'full' && ! $retina ) {

				// Check if this Gallery uses a WordPress defined image size
				if ( $image_size != 'default'  ) {
					// If image size is envira_gallery_random, get a random image size.
					if ( $image_size == 'envira_gallery_random' ) {

						// Get random image sizes that have been chosen for this Gallery.
						$image_sizes_random = (array) envira_get_config( 'image_sizes_random', $data );

						if ( count( $image_sizes_random ) == 0 ) {
							// The user didn't choose any image sizes - use them all.
							$wordpress_image_sizes = envira_get_image_sizes( true );
							$wordpress_image_size_random_key = array_rand( $wordpress_image_sizes, 1 );
							$image_size = $wordpress_image_sizes[ $wordpress_image_size_random_key ]['value'];
						} else {
							$wordpress_image_size_random_key = array_rand( $image_sizes_random, 1 );
							$image_size = $image_sizes_random[ $wordpress_image_size_random_key ];
						}

						// Get the random WordPress defined image size
						$src = wp_get_attachment_image_src( $id, $image_size );
					} else {
						// Get the requested WordPress defined image size
						$src = wp_get_attachment_image_src( $id, $image_size );
					}
				} else {

					$isize = $this->find_clostest_size( $this->gallery_data ) != '' ? $this->find_clostest_size( $data ) : 'full';
					$src = apply_filters( 'envira_gallery_default_image_src', wp_get_attachment_image_src( $id, $isize ), $id, $item, $data, $this->is_mobile );

				}

			} else{

				$src = apply_filters( 'envira_gallery_retina_image_src', wp_get_attachment_image_src( $id, 'full' ), $id, $item, $data, $this->is_mobile );

			}

		endif;


		// Check if this returned an image
		if ( ! $src ) {
			// Fallback to the $item's image source
			$image = $item['src'];
		} else if ( ! $instagram ) {
			$image = $src[0];
		}


		// If we still don't have an image at this point, something went wrong
		if ( ! isset( $image ) ) {
			return apply_filters( 'envira_gallery_no_image_src', $item['link'], $id, $item, $data );
		}

		// Prep our indexable images.
		if ( $image && ! $this->is_mobile ) {
			$this->index[ $data['id'] ][ $id ] = array(
				'src' => $image,
				'alt' => ! empty( $item['alt'] ) ? $item['alt'] : ''
			);
		}
		

		
		// If the current layout is justified/automatic
		// if the image size is a WordPress size and we're not requesting a retina image we don't need to resize or crop anything.
		if ( $image_size != 'default' && ! $retina && $type != 'mobile' ) {
		// if ( ( $image_size != 'default' && ! $retina ) ) {
			// Return the image
			return apply_filters( 'envira_gallery_image_src', $image, $id, $item, $data );
		}
		$crop = envira_get_config( 'crop', $data );
		
		if( $crop ){
				
			// If the image size is default (i.e. the user has input their own custom dimensions in the Gallery),
			// we may need to resize the image now
			// This is safe to call every time, as resize_image() will check if the image already exists, preventing thumbnails
			// from being generated every single time.
			$args = array(
				'position' 		=> envira_get_config( 'crop_position', $data ),
				'width'			=> envira_get_config( $type . '_width', $data ),
				'height'		=> envira_get_config( $type . '_height', $data ),
				'quality'		=> 100,
				'retina'		=> $retina,
			);
			
			// If we're requesting a retina image, and the gallery uses a registered WordPress image size,
			// we need use the width and height of that registered WordPress image size - not the gallery's
			// image width and height, which are hidden settings.
	
			// if this is mobile, go with the mobile image settings, otherwise proceed?
			if ( $image_size != 'default' && $retina && $type != 'mobile' ) {
				// Find WordPress registered image size
				$wordpress_image_sizes = envira_get_image_sizes( true ); // true = WordPress only image sizes (excludes random
	
				foreach ( $wordpress_image_sizes as $size ) {
	
					if ( $size['value'] !== $image_size ) {
						continue;
					}
	
					// We found the image size. Use its dimensions
					if ( !empty( $size['width'] ) ) {
						$args['width'] = $size['width'];
					}
					if ( !empty( $size['height'] ) ) {
						$args['height'] = $size['height'];
					}
					break;
	
				}
			}
	
			// Filter
			$args	= apply_filters( 'envira_gallery_crop_image_args', $args );
			
			//Make sure we're grabbing the full image to crop.
			$src = apply_filters( 'envira_gallery_crop_image_src', wp_get_attachment_image_src( $id, 'full' ), $id, $item, $data, $this->is_mobile );
		
			$resized_image = envira_resize_image( $src[0], $args['width'], $args['height'], envira_get_config( 'crop', $data ), envira_get_config( 'crop_position', $data ), $args['quality'], $args['retina'], $data );
	
			// If there is an error, possibly output error message and return the default image src.
			if ( is_wp_error( $resized_image ) ) {
				// If WP_DEBUG is enabled, and we're logged in, output an error to the user
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG && is_user_logged_in() ) {
					echo '<pre>Envira: Error occured resizing image (these messages are only displayed to logged in WordPress users):<br />';
					echo 'Error: ' . $resized_image->get_error_message() . '<br />';
					echo 'Image: ' . $image . '<br />';
					echo 'Args: ' . var_export( $args, true ) . '</pre>';
				}
	
				// Return the non-cropped image as a fallback.
			} else {
				
				return apply_filters( 'envira_gallery_image_src', $resized_image, $id, $item, $data );
			
			}
		}
		//return full image
		return apply_filters( 'envira_gallery_image_src', $image, $id, $item, $data );

	}

	/**
	 * find_clostest_size function.
	 *
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function find_clostest_size( $data ){

		$image_sizes = envira_get_shortcode_image_sizes();
		$dimensions =	 envira_get_config( 'dimensions', $data );
		$width =	envira_get_config( 'crop_width', $data );
		$height =	 envira_get_config( 'crop_height', $data );
		$match   = false;

		usort( $image_sizes, array( $this, 'usort_callback' ) );

		foreach( $image_sizes as $num ) {

			$num['width']  = (int) $num['width'];
			$num['height'] = (int) $num['height'];

			//skip over sizes that are smaller
			if ( $num['height'] < $height || $num['width'] < $width ){
				continue;
			}
			if ( $num['width'] > $width && $num['height'] > $height ) {

				if ( $match === false ) {

					$match = true;
					$size = $num['name'];

					return $size;
				}
			}
		}
		return '';

	}

	/**
	 * Helper function for usort and php 5.3 >.
	 *
	 * @access public
	 * @param mixed $a
	 * @param mixed $b
	 * @return void
	 */
	function usort_callback($a, $b) {

		return intval($a['width']) - intval($b['width'] );

	}

	/**
	 * Helper method to retrieve the proper gallery toolbar template.
	 *
	 * @since 1.7.0
	 *
	 * @param array $data Array of gallery data.
	 * @return string		 String template for the gallery toolbar.
	 */
	public function get_toolbar_template( $data ) {

		global $post;

		$title = false;
		$lightbox_theme = envira_get_config( 'lightbox_theme', $data );

		// Build out the custom template based on options chosen.
		$template	 = '<div id="envirabox-buttons"';

		if ( $lightbox_theme ) {
			$template .= ' class="envirabox-buttons-'.$lightbox_theme.'" ';
		}

		$template .= '>';

			$template .= '<ul>';
				$template  = apply_filters( 'envira_gallery_toolbar_start', $template, $data );

				// Prev
				$template .= '<li><a class="btnPrev" title="' . __( 'Previous', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
				$template  = apply_filters( 'envira_gallery_toolbar_after_prev', $template, $data );

				// Next
				$template .= '<li><a class="btnNext" title="' . __( 'Next', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
				$template  = apply_filters( 'envira_gallery_toolbar_after_next', $template, $data );

				// Title
				if ( envira_get_config( 'toolbar_title', $data ) ) {
					// to get the title, don't grab title from $post first
					// because you'll be grabbing the title of the page
					// the gallery is embedded on.

					if ( envira_get_config( 'title', $data ) ) {
						$title = envira_get_config( 'title', $data );
					} else if ( isset($post->post_title) ) {
						// there should ALWAYS be a title, but just in case revert to grabbing from $post
						$title = $post->post_title;
					}

					// add a filter in case title needs to be manipulated for the toolbar
					$title = apply_filters( 'envira_gallery_toolbar_title', $title, $data );

					$template .= '<li id="envirabox-buttons-title"><span>' . htmlentities( $title, ENT_QUOTES ) . '</span></li>';
					$template	= apply_filters( 'envira_gallery_toolbar_after_title', $template, $data );
				}


				// Close
				$template .= '<li><a class="btnClose" title="' . __( 'Close', 'envira-gallery' ) . '" href="javascript:;"></a></li>';
				$template  = apply_filters( 'envira_gallery_toolbar_after_close', $template, $data );

				$template  = apply_filters( 'envira_gallery_toolbar_end', $template, $data );
			$template .= '</ul>';
		$template .= '</div>';

		// Return the template, filters applied and all.
		return apply_filters( 'envira_gallery_toolbar', $template, $data );

	}

	/**
	* Helper method to retrieve the gallery lightbox template
	*
	* @since 1.3.1.4
	*
	* @param array $data Array of gallery data
	* @return string String template for the gallery lightbox
	*/
	public function get_lightbox_template( $data ) {

		// Build out the lightbox template
		$envirabox_wrap_css_classes = apply_filters( 'envirabox_wrap_css_classes', 'envirabox-wrap', $data );

		$envirabox_theme = apply_filters( 'envirabox_theme', 'envirabox-theme-' . envira_get_config( 'lightbox_theme', $data ), $data );

		$template = '<div class="' . $envirabox_wrap_css_classes . '" tabIndex="-1"><div class="envirabox-skin ' . $envirabox_theme . '"><div class="envirabox-outer"><div class="envirabox-inner">';

		// Lightbox Inner above
		$template = apply_filters( 'envirabox_inner_above', $template, $data );

		// Top Left box
		$template .= '<div class="envirabox-position-overlay envira-gallery-top-left">';
		$template	 = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'top-left' );
		$template .= '</div>';

		// Top Right box
		$template .= '<div class="envirabox-position-overlay envira-gallery-top-right">';
		$template	 = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'top-right' );
		$template .= '</div>';

		// Bottom Left box
		$template .= '<div class="envirabox-position-overlay envira-gallery-bottom-left">';
		$template	 = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'bottom-left' );
		$template .= '</div>';

		// Bottom Right box
		$template .= '<div class="envirabox-position-overlay envira-gallery-bottom-right">';
		$template	 = apply_filters( 'envirabox_output_dynamic_position', $template, $data, 'bottom-right' );
		$template .= '</div>';

		// Lightbox Inner below
		$template = apply_filters( 'envirabox_inner_below', $template, $data );

		$template .= '</div></div></div></div>';

		// Return the template, filters applied
		return apply_filters( 'envira_gallery_lightbox_template', str_replace( "\n", '', $template ), $data );

	}

	/**
	 * Helper method for retrieving config values.
	 *
	 * @since 1.7.0
	 *
	 * @param string $key The config key to retrieve.
	 * @param array $data The gallery data to use for retrieval.
	 * @return string		 Key value on success, default if not set.
	 */
	public function get_config( $key, $data ) {

		// If we are on a mobile device, some config keys have mobile equivalents, which we need to check instead
		if ( $this->is_mobile ) {
			$this->is_mobile_keys = array(
				'lightbox_enabled'	 => 'mobile_lightbox',
				'arrows'			 => 'mobile_arrows',
				'toolbar'			 => 'mobile_toolbar',
				'thumbnails'		 => 'mobile_thumbnails',
				'thumbnails_width'	 => 'mobile_thumbnails_width',
				'thumbnails_height'	 => 'mobile_thumbnails_height',
			);

			$this->is_mobile_keys = apply_filters( 'envira_gallery_get_config_mobile_keys', $this->is_mobile_keys );

			// When on mobile, use used to blindly look at the mobile_social option to determine social sharing button output
			// However, what we need to do is look to see if the settings are active on the addon first
			if ( array_key_exists( 'social', $this->is_mobile_keys ) || array_key_exists( 'social_lightbox', $this->is_mobile_keys ) ) {

				if ( empty( $data['config']['social'] ) ) {
					unset( $this->is_mobile_keys['social'] );
				}
				if ( empty( $data['config']['social_lightbox'] ) ) {
					unset( $this->is_mobile_keys['social_lightbox'] );
				}
			}

			if ( array_key_exists( $key, $this->is_mobile_keys ) ) {
				// Use the mobile array key to get the config value
				$key = $this->is_mobile_keys[ $key ];
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

		return isset( $data['config'][$key] ) ? $data['config'][$key] : envira_get_config_default( $key );

	}
	
	/**
	 * I'm sure some plugins mean well, but they go a bit too far trying to reduce
	 * conflicts without thinking of the consequences.
	 *
	 * 1. Prevents Foobox from completely borking envirabox as if Foobox rules the world.
	 *
	 * @since 1.7.0
	 */
	public function plugin_humility() {

		if ( class_exists( 'fooboxV2' ) ) {
			remove_action( 'wp_footer', array( $GLOBALS['foobox'], 'disable_other_lightboxes' ), 200 );
		}

	}

	/**
	 * Outputs only the first image of the gallery inside a regular <div> tag
	 * to avoid styling issues with feeds.
	 *
	 * @since 1.0.5
	 *
	 * @param array $data		  Array of gallery data.
	 * @return string $gallery Custom gallery output for feeds.
	 */
	public function do_feed_output( $data ) {

		$gallery = '<div class="envira-gallery-feed-output">';
			foreach ( $data['gallery'] as $id => $item ) {
				// Skip over images that are pending (ignore if in Preview mode).
				if ( isset( $item['status'] ) && 'pending' == $item['status'] && ! is_preview() ) {
					continue;
				}

				$imagesrc = $this->get_image_src( $id, $item, $data );
				$gallery .= '<img class="envira-gallery-feed-image" src="' . esc_url( $imagesrc ) . '" title="' . trim( esc_html( $item['title'] ) ) . '" alt="' .trim( esc_html( $item['alt'] ) ) . '" />';
				break;
			 }
		$gallery .= '</div>';

		return apply_filters( 'envira_gallery_feed_output', $gallery, $data );

	}

	/**
	 * Returns a set of indexable image links to allow SEO indexing for preloaded images.
	 *
	 * @since 1.7.0
	 *
	 * @param mixed $id			The slider ID to target.
	 * @return string $images String of indexable image HTML.
	 */
	public function get_indexable_images( $id ) {

		// If there are no images, don't do anything.
		$images = '';
		$i		= 1;
		if ( empty( $this->index[$id] ) ) {
			return $images;
		}

		foreach ( (array) $this->index[$id] as $attach_id => $data ) {
			$images .= '<img src="' . esc_url( $data['src'] ) . '" alt="' . esc_attr( $data['alt'] ) . '" />';
			$i++;
		}

		return apply_filters( 'envira_gallery_indexable_images', $images, $this->index, $id );

	}

	/**
	* Allow users to add a title or caption under an image for legacy galleries
	*
	* @since 1.5.9.3
	*
	* @param string	$output	Output HTML
	* @param int $id Image Attachment ID
	* @param array $item Image Data
	* @param array $data Gallery Data
	* @param int $i Image Count
	* @return string Output HTML
	*/
	public function gallery_image_caption_titles( $output, $id, $item, $data, $i ) {

		// for some reason - probably ajax - the $this->gallery_data is
		// empty on "load more" ajax pagination but $data comes through...

		if ( !$this->gallery_data ) {
			$this->gallery_data = $data;
		}

		// this only applies to legacy, not dark/light themes, etc.
		if ( envira_get_config( 'columns', $this->gallery_data ) == 0 ) {
			return false;
		}

		$revised_output = false;

		$allowed_html_tags = array(
				 'a' => array(
				  'href' => array(),
				  'title' => array(),
				  'class' => array(),
				  'target' => array(),
				 ),
				 'br' => array(),
				 'em' => array(),
				 'strong' => array(),
				 'p' => array(),
				 'strike' => array(),
				 'object' => array(),
		);

		// has user checked off title and is there a title in $item?
		if ( envira_get_config( 'additional_copy_title', $this->gallery_data ) == 1 && ! empty( $item['title'] ) ) {

			$gallery_theme = envira_get_config( 'gallery_theme', $data );
			if ( $gallery_theme != 'captioned' && $gallery_theme != 'polaroid' ) {
				$revised_output .= '<span class="envira-title title-' . $id . '">' . wp_kses( $item['title'], $allowed_html_tags ) . '</span>';
			}

		}

		// has user checked off title and is there a title in $item?
		if ( envira_get_config( 'additional_copy_caption', $this->gallery_data ) == 1 && ! empty( $item['caption'] ) ) {

			$gallery_theme = envira_get_config( 'gallery_theme', $data );
			if ( $gallery_theme != 'captioned' && $gallery_theme != 'polaroid' ) {
				$revised_output .= '<span class="envira-caption caption-' . $id . '">' . wp_kses( $item['caption'], $allowed_html_tags ) . '</span>';
			}
			// check for line breaks, convert them to <br/>
			$revised_output = nl2br( $revised_output );

		}
		
		return apply_filters( 'envira_gallery_image_caption_titles', $revised_output, $id, $item, $data, $i );

	}

}