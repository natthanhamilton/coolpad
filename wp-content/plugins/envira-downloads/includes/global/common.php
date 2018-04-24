<?php
/**
 * Common class.
 *
 * @since 1.0.0
 *
 * @package Envira_Downloads
 * @author  Envira Team
 */
class Envira_Downloads_Common {

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

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        add_filter( 'envira_gallery_defaults', array( $this, 'defaults' ), 10, 2 );
        add_filter( 'envira_albums_defaults', array( $this, 'defaults' ), 10, 2 );

    }

    /**
     * Adds the default settings for this addon.
     *
     * @since 1.0.0
     *
     * @param array $defaults  Array of default config values.
     * @param int $post_id     The current post ID.
     * @return array $defaults Amended array of default config values.
     */
    function defaults( $defaults, $post_id ) {
    
        // Add default settings to main defaults array
        $defaults['download_all']                       = 0;
        $defaults['download_all_position']              = 'below';
        $defaults['download_all_label']                 = __( 'Download All Images', 'envira-downloads' );
        $defaults['download']                           = 0;
        $defaults['download_custom_name']               = '';
        $defaults['download_position']                  = 'top-left';
        $defaults['download_image_size']                = '';
        $defaults['download_force']                     = 0;
        $defaults['password_protection_download']       = '';
        $defaults['download_invalid_password_message']  = __( 'The password entered was invalid. Please try again.', 'envira-downloads' );

        // Lightbox defaults
        $defaults['download_lightbox']                  = 0;
        $defaults['download_lightbox_position']         = 'top-left';
        $defaults['download_force']                     = 0;

        // Return
        return $defaults;
    
    }

    /**
     * Helper method for retrieving positions.
     *
     * @since 1.0.0
     *
     * @return array Array of positions.
     */
    public function get_positions() {

        $positions = array(
            'top-left'      => __( 'Top Left', 'envira-downloads' ),
            'top-right'     => __( 'Top Right', 'envira-downloads' ),
            'bottom-left'   => __( 'Bottom Left', 'envira-downloads' ),
            'bottom-right'  => __( 'Bottom Right', 'envira-downloads' ),
        );

        return apply_filters( 'envira_downloads_positions', $positions );

    }

    /**
     * Helper method for retrieving positions for the Download All button.
     *
     * @since 1.0.1
     *
     * @return array Array of positions.
     */
    public function get_positions_all() {

        $positions = array(
            'above'     => __( 'Above Gallery', 'envira-downloads' ),
            'below'     => __( 'Below Gallery', 'envira-downloads' ),
        );

        return apply_filters( 'envira_downloads_positions_all', $positions );

    }

    /**
     * Helper method for retrieving image sizes.
     *
     * @since 1.3.6
     *
     * @global array $_wp_additional_image_sizes Array of registered image sizes.
     *
     * @param   bool    $wordpress_only     WordPress Only (excludes the default and envira_gallery_random options)
     * @return  array                       Array of image size data.
     */
    public function get_image_sizes( $wordpress_only = false ) {

        if ( ! $wordpress_only ) {
            $sizes = array(
                array(
                    'value'  => 'default',
                    'name'   => __( 'Default', 'envira-gallery' ),
                )
            );
        }

        global $_wp_additional_image_sizes;
        $wp_sizes = get_intermediate_image_sizes();
        foreach ( (array) $wp_sizes as $size ) {
            if ( isset( $_wp_additional_image_sizes[$size] ) ) {
                $width  = absint( $_wp_additional_image_sizes[$size]['width'] );
                $height = absint( $_wp_additional_image_sizes[$size]['height'] );
            } else {
                $width  = absint( get_option( $size . '_size_w' ) );
                $height = absint( get_option( $size . '_size_h' ) );
            }

            if ( ! $width && ! $height ) {
                $sizes[] = array(
                    'value'  => $size,
                    'name'   => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ),
                );
            } else {
                $sizes[] = array(
                    'value'  => $size,
                    'name'   => ucwords( str_replace( array( '-', '_' ), ' ', $size ) ) . ' (' . $width . ' &#215; ' . $height . ')',
                    'width'  => $width,
                    'height' => $height,
                );
            }
        }

        // Add A Full/Oringial Size Option
        if ( ! $wordpress_only ) {
            $sizes[] = array(
                'value'  => 'full',
                'name'   => __( 'Original Size', 'envira-gallery' ),
            );
        }

        return apply_filters( 'envira_download_image_sizes', $sizes );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Downloads_Common object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Downloads_Common ) ) {
            self::$instance = new Envira_Downloads_Common();
        }

        return self::$instance;

    }

}

// Load the common class.
$envira_downloads_common = Envira_Downloads_Common::get_instance();