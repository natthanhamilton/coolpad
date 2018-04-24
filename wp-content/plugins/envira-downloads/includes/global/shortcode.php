<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Downloads
 * @author  Envira Team
 */
class Envira_Downloads_Shortcode {

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;
    public $common;
    
     /**
     * Holds a flag to determine whether metadata has been set
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $meta_data_set = false;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
	    
	    // Load the base class object.
        $this->base = Envira_Downloads::get_instance();
	    $this->common = Envira_Downloads_Common::get_instance();
	    
	    // Register CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/envira-downloads.css', $this->base->file ), array(), $this->base->version );
	    
        // Register JS
        wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/min/envira-downloads-min.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );
        
	    // Gallery
        add_action( 'envira_gallery_before_output', array( $this, 'gallery_output_css_js' ) );
        add_filter( 'envira_gallery_output_before_container', array( $this, 'gallery_output_download_all_button_above' ), 10, 2 );
        add_filter( 'envira_gallery_output_before_container', array( $this, 'gallery_output_error_message' ), 10, 2 );
        add_filter( 'envira_gallery_output_dynamic_position', array( $this, 'gallery_output_html' ), 10, 6 );
        add_filter( 'envira_gallery_output_after_container', array( $this, 'gallery_output_download_all_button_below' ), 10, 2 );
        add_action( 'envirabox_output_dynamic_position', array( $this, 'gallery_output_lightbox_html' ), 10, 3 );
        add_filter( 'envirabox_actions', array( $this, 'envirabox_actions' ), 5, 2 );
        add_filter( 'envira_gallery_output_dynamic_position_css', array( $this, 'gallery_output_dynamic_position_css' ), 10, 7 );
        add_filter( 'envira_always_show_title', array( $this, 'envira_always_show_title' ), 10, 2 );

        // Album
        add_action( 'envira_albums_before_output', array( $this, 'albums_output_css_js' ) );

    }

    /**
     * Add A Custom CSS Class To Gallery HTML div.envira-gallery-position-overlay
     *
     * @since 1.0.0
     *
     * @param array $data Gallery Data
     */
    public function gallery_output_dynamic_position_css( $css, $output, $id, $item, $data, $i, $position ) {

        // Check if Download Button output is enabled
        if ( ! $this->get_config( 'download_all', $data ) && ! $this->get_config( 'download', $data ) && ! $this->get_config( 'download_lightbox', $data ) ) {
            return;
        }

        if ( !$this->get_config( 'download_position', $data ) ) { return $css; }

        if ( $this->get_config( 'download_position', $data ) == $position ) {
            return $css . ' downloads-addon';
        } else {
            return $css;
        }

    }


    /**
	 * Enqueue CSS and JS if the Download Button is enabled
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Gallery Data
	 */
	public function gallery_output_css_js( $data ) {

		// Check if Download Button output is enabled
        if ( ! $this->get_config( 'download_all', $data ) && ! $this->get_config( 'download', $data ) && ! $this->get_config( 'download_lightbox', $data ) ) {
			return;
		}

		// Enqueue CSS + JS
		wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Check if a password exists
        $password = $this->get_config( 'password_protection_download', $data );

        // Localize JS
        wp_localize_script( 
            $this->base->plugin_slug . '-script',
            'envira_downloads',
            array(
                'password_protection' => ( ! empty( $password ) ? true : false ),
                'password_required'   => __( 'Please enter the password to download this image.', 'envira-downloads' ),
                'id'                  => $data['id'],
            )
        );
		
	}

    /**
     * Outputs the Download All button above the Gallery, if enabled
     *
     * @since 1.0.1
     *
     * @param   string  $html   HTML Output
     * @param   array   $data   Gallery Data
     * @return  string          HTML Output
     */
    public function gallery_output_download_all_button_above( $html, $data ) {

        // Bail if the Download All option isn't enabled and set to display before the Gallery.
        if ( ! $this->get_config( 'download_all', $data ) ) {
            return $html;
        }
        if ( 'above' != $this->get_config( 'download_all_position', $data ) ) {
            return $html;
        }

        $envira_dynamic = false;

        // Is this a dynamic gallery?       
        $dynamic = $this->get_config( 'dynamic', $data );
        if ( $dynamic ) {
            $envira_dynamic = 1;
            // If the gallery is dynamic, replace the ID with the dynamic value
            $gallery_id = $dynamic;
            $images = $data['config']['images'];
        } else {
            $gallery_id = $data['id'];
            $images = false;
        }

        // Build link
        $url = add_query_arg( array(
            'envira-downloads-gallery-id'   => $gallery_id,
            'envira-downloads-gallery-image'=> 'all',
            'envira-downloads-gallery-images'=> $images,
            'envira-dynamic'=> $envira_dynamic, 
        ), ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    
        // Append Download All button to the output and return.
        $html .= '<div class="envira-downloads">
                    <a href="' . esc_url_raw ( $url ) . '" class="envira-download-all">
                        ' . $this->get_config( 'download_all_label', $data ) . '
                    </a>
                  </div>';
        
        return $html;

    }

    /**
     * Outputs the Download All button below the Gallery, if enabled
     *
     * @since 1.0.1
     *
     * @param   string  $html   HTML Output
     * @param   array   $data   Gallery Data
     * @return  string          HTML Output
     */
    public function gallery_output_download_all_button_below( $html, $data ) {

        // Bail if the Download All option isn't enabled and set to display before the Gallery.
        if ( ! $this->get_config( 'download_all', $data ) ) {
            return $html;
        }
        if ( 'below' != $this->get_config( 'download_all_position', $data ) ) {
            return $html;
        }

        $envira_dynamic = false;
        $images = false;

        // Is this a dynamic gallery?       
        $dynamic = $this->get_config( 'dynamic', $data );
        if ( $dynamic ) {
            $envira_dynamic = 1;
            // If the gallery is dynamic, replace the ID with the dynamic value
            $gallery_id = $dynamic;
            if ( isset( $data['config']['images'] ) ) {
              $images = $data['config']['images'];
            }
        } else {
            $gallery_id = $data['id'];
            $images = false;
        }

        // Build link
        $url = add_query_arg( array(
            'envira-downloads-gallery-id'   => $gallery_id,
            'envira-downloads-gallery-image'=> 'all',
            'envira-downloads-gallery-images'=> $images,
            'envira-dynamic'=> $envira_dynamic, 
        ), ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
    
        // Append Download All button to the output and return.
        $html .= '<div class="envira-downloads">
                    <a href="' . esc_url_raw( $url ) . '" class="envira-download-all">
                        ' . $this->get_config( 'download_all_label', $data ) . '
                    </a>
                  </div>';
        
        return $html;

    }

    /**
     * Outputs an error message if the URL contains the envira-downloads-invalid-password query parameter, 
     * telling the user the password they entered was invalid.
     *
     * @since 1.0.0
     *
     * @param   string  $html   HTML Output
     * @param   array   $data   Gallery
     * @return  string          HTML Output
     */
    public function gallery_output_error_message( $html, $data ) {

        // Check if the query parameters exists
        if ( ! isset( $_REQUEST['envira-downloads-gallery-id'] ) ) {
            return $html;
        }
        if ( ! isset( $_REQUEST['envira-downloads-invalid-password'] ) ) {
            return $html;
        }

        // Check that the error is for this gallery
        if ( $data['id'] != $_REQUEST['envira-downloads-gallery-id'] && ( isset($data['dynamic']) && $data['dynamic'] != $_REQUEST['envira-downloads-gallery-id'] ) ) {
            return $html;
        }

        // Output the error message.
        $html .= '<div id="envira-downloads-invalid-password">' . $this->get_config( 'download_invalid_password_message', $data ) . '</div>';

        // Return.
        return $html;

    }

    /**
    * Enqueue CSS and JS for Albums if Download Button is enabled
    *
    * @since 1.0.0
    *
    * @param array $data Album Data
    */
    public function albums_output_css_js( $data ) {

        // Check if Download Button output is enabled
        if ( ! $this->get_config( 'download_lightbox', $data ) ) {
            return;
        }
        
        // Enqueue CSS + JS
        wp_enqueue_style( $this->base->plugin_slug . '-style' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Check if a password exists
        $password = $this->get_config( 'password_protection_download', $data );

        // Localize JS
        wp_localize_script( 
            $this->base->plugin_slug . '-script',
            'envira_downloads',
            array(
                'password_protection' => ( ! empty( $password ) ? true : false ),
                'password_required'   => __( 'Please enter the password to download this image.', 'envira-downloads' ),
                'id'                  => $data['id'],
            )
        );
        
    }

    
    /**
	* Outputs Download Button HTML for the Gallery thumbnail
	*
	* @since 1.0.0
	* 
	* @param string    $output HTML Output
	* @param int       $id     Attachment ID
	* @param array     $item   Image Item
	* @param array     $data   Gallery Config
	* @param int       $i      Image number in gallery
	* @return string           HTML Output
	*/
    public function gallery_output_html( $output, $id, $item, $data, $i, $position ) {

        // Check if Social Sharing Buttons output is enabled
        if ( ! $this->get_config( 'download', $data ) ) {
            return $output;
        }

        // Prepend Button
	    $buttons = $this->get_download_button( $id, $item, $data, $i, $position );

		return $output . $buttons;
	    
    }

    public function envirabox_actions( $template, $data ) {

        // Check if Download Button output is enabled
        if ( ! $this->get_config( 'download_lightbox', $data ) || ( ! in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_light', 'base_dark', 'space_dark', 'space_light', 'box_dark', 'box_light', 'burnt_dark', 'burnt_light', 'modern-dark', 'modern-light' ) ) ) ) {
            return $template;
        }

        return $this->gallery_output_lightbox_html( $template, $data );
    }

    public function envira_always_show_title( $show, $data ) {

        if ( ! $this->get_config( 'download_lightbox', $data ) || ( ! in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light' ) ) ) ) {
            return $show;
        }

        return true;
    }
    
    /**
	* Gallery: Outputs Download Button when a lightbox image is displayed from a Gallery
	*
	* @param array $data Gallery Data
	* @return JS
	*/
    public function gallery_output_lightbox_html( $template, $data, $position = null ) {
	    
        // Check if Download Button output is enabled
        if ( ! $this->get_config( 'download_lightbox', $data ) || ( ! empty( $position ) && ( in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light', 'space_dark', 'space_light', 'box_dark', 'box_light', 'burnt_dark', 'burnt_light', 'modern-dark', 'modern-light' ) ) ) ) ) {
            return $template;
        }

        // Get Button
        $button = $this->get_lightbox_download_button( $data, $position );
	   
        return $template . $button;
    }

    /**
    * Helper to output download button for an image
    *
    * @since 1.0.0
    *
    * @param int    $id     Image ID
    * @param array  $item   Image Data
    * @param array  $data   Gallery Data
    * @param int    $i      Index
    * @return string        HTML
    */
    function get_download_button( $id, $item, $data, $i, $position ) {

        if ( $this->get_config( 'download_position', $data ) !== $position ) {
            return;
        }
        
        // Get instance
        $envira_dynamic = false;
        $envira_dynamic_gallery_id = (int) $this->get_config( 'default', $data );

        // echo $this->get_config( 'default', $data ); exit;

        // Is the user assigning a default gallery by passing in an ID?
        if ( $envira_dynamic_gallery_id && is_integer( $envira_dynamic_gallery_id ) ) {
            $envira_dynamic_gallery_id = $this->get_config( 'default', $data );
        } else {
            $envira_dynamic_gallery_id = false;
        }

        // Is this a dynamic gallery?       
        $dynamic = $this->get_config( 'dynamic', $data );
        if ( $dynamic ) {
            $envira_dynamic = 1;
            // If the gallery is dynamic, replace the ID with the dynamic value
            $gallery_id = $dynamic;
        } else {
            $gallery_id = $data['id'];
        }

        // Build link, depending on whether we're forcing a browser download or not
        $password = $this->get_config( 'password_protection_download', $data );
        if ( ! empty( $password ) || $this->get_config( 'download_force', $data ) == '1' ) {
            $url = add_query_arg( array(
                'envira-downloads-gallery-id'   => $data['id'],
                'envira-downloads-gallery-image'=> $id,
                'envira-dynamic'=> $envira_dynamic, 
                'envira_dynamic_gallery_id' => $envira_dynamic_gallery_id,
            ), ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        } else {
            $url = $item['link'];
        }

        // Build Button
        $button = '<div class="envira-download-button">
            <a href="' . esc_url_raw( $url ) . '" target="_blank" title="' . __( 'Click here to download this image', 'envira-downloads' ) . '">' . __( 'Download', 'envira-downloads' ) . '</a>
        </div>';

        // Return
        return $button; 
    }

    /**
    * Helper to output social sharing buttons for the lightbox
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return string HTML
    */
    function get_lightbox_download_button( $data, $position ) {

        if ( $this->get_config( 'download_lightbox_position', $data ) !== $position && ! in_array( $this->get_config( 'lightbox_theme', $data ), array( 'base_dark', 'base_light', 'space_dark', 'space_light', 'box_dark', 'box_light', 'burnt_dark', 'burnt_light', 'modern-dark', 'modern-light' ) ) ) {
            return;
        }

        // Build Button
        $button = '<div class="envira-download-button" data-envira-downloads-force-download="' . $this->get_config( 'download_lightbox_force', $data ) . '"><a href="#" title="' . __( 'Click here to download this image', 'envira-downloads' ) . '">' . __( 'Download', 'envira-downloads' ) . '</a></div>';

        // Return
        return $button;

    }
   
    /**
     * Helper method for retrieving gallery config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_config( $key, $data ) {

        // Determine whether data is for a gallery or album
        $post_type = get_post_type( $data['id'] );

        // If post type is false or simply 'post', we're probably on a dynamic gallery/album
        // Grab the ID from the config
        if ( $post_type == 'post' || ( ! $post_type && isset( $data['config']['id'] ) ) ) {
            $post_type = get_post_type( $data['config']['id'] );
        }

        switch ( $post_type ) {
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();
                break;
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();
                break;
            case 'page':
                // If the instance was $post_type was post, this is likely a dynamic post so return the 'config' (?)
                if ( isset( $data['config'][$key] ) ) {
                    return $data['config'][$key];    
                }                
        }

        // If no instance was set, bail
        if ( ! isset( $instance ) ) {
            return false;
        }

        // Return value
        return $instance->get_config( $key, $data );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Downloads_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Downloads_Shortcode ) ) {
            self::$instance = new Envira_Downloads_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_downloads_shortcode = Envira_Downloads_Shortcode::get_instance();