<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Downloads
 * @author  Envira Team
 */
class Envira_Downloads_Metaboxes {

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

        $this->base = Envira_Downloads::get_instance();

        add_action( 'envira_gallery_metabox_scripts', array( $this, 'metabox_scripts' ) );
        add_action( 'envira_albums_metabox_scripts', array( $this, 'metabox_scripts' ) );

        // Envira Gallery
        add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_downloads', array( $this, 'downloads_tab' ) );
        add_filter( 'envira_gallery_save_settings', array( $this, 'gallery_settings_save' ), 10, 2 );

        // Envira Album
        add_filter( 'envira_albums_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_albums_tab_downloads', array( $this, 'downloads_tab' ) );
        add_filter( 'envira_albums_save_settings', array( $this, 'album_settings_save' ), 10, 2 );

    }

    /**
     * Initializes scripts for the metabox admin.
     *
     * @since 1.0.0
     *
     * @param string $key The user license key.
     */
    public function metabox_scripts() {
        // Conditional Fields
        wp_register_script( $this->base->plugin_slug . '-conditional-fields-script', plugins_url( 'assets/js/min/conditional-fields-min.js', $this->base->file ), array( 'jquery', Envira_Gallery::get_instance()->plugin_slug . '-conditional-fields-script' ), $this->base->version, true );
        wp_enqueue_script( $this->base->plugin_slug . '-conditional-fields-script' );
    }

    /**
     * Registers tab(s) for this Addon in the Settings screen
     *
     * @since 1.0.0
     *
     * @param   array   $tabs   Tabs
     * @return  array           Tabs
     */
    function register_tabs( $tabs ) {

        $tabs['downloads'] = __( 'Downloads', 'envira-downloads' );
        return $tabs;

    }
   
    /**
     * Adds addon settings UI to the Downloads tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function downloads_tab( $post ) {

        // Get post type so we load the correct metabox instance and define the input field names
        // Input field names vary depending on whether we are editing a Gallery or Album
        $post_type = get_post_type( $post );
        switch ( $post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Metaboxes::get_instance();
                $key = '_envira_gallery';
                break;
            
            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Metaboxes::get_instance();
                $key = '_eg_album_data[config]';
                break;
        }

        // Gallery options only apply to Galleries, not Albums
        if ( 'envira' == $post_type ) {
            ?>
            <p class="envira-intro">
                <?php _e( 'Downloads Gallery Settings', 'envira-downloads' ); ?>
                <small>
                    <?php _e( 'The settings below adjust the image download options on your Gallery output.', 'envira-downloads' ); ?>
                    <br />
                    <?php _e( 'Need some help?', 'envira-downloads' ); ?>
                    <a href="http://enviragallery.com/docs/downloads-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-downloads' ); ?>
                    </a>
                    or
                    <a href="https://www.youtube.com/embed/PTCJlhVF5Pk" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-downloads' ); ?>
                    </a>
                </small>
            </p>

            <table class="form-table">
                <tbody>
                    <tr id="envira-config-downloads-box">
                        <th scope="row">
                            <label for="envira-config-downloads"><?php _e( 'Display Download Button?', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-downloads" type="checkbox" name="<?php echo $key; ?>[download]" value="1" <?php checked( $instance->get_config( 'download', $instance->get_config_default( 'download' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'Enables or disables displaying a download button on each image in the gallery view.', 'envira-downloads' ); ?></span>
                        </td>
                    </tr>
                    <tr id="envira-config-downloads-image-size">
                        <th scope="row">
                            <label for="envira-config-downloads-image-size"><?php _e( 'Image Size To Download', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-downloads-image-size" name="<?php echo $key; ?>[download_image_size]">
                                <?php foreach ( (array) $this->get_image_sizes() as $size ) : ?>
                                   <option value="<?php echo $size['value']; ?>"<?php selected( $size['value'], $instance->get_config( 'download_image_size', $instance->get_config_default( 'download_image_size' ) ) ); ?>><?php echo $size['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Define what image size and dimensions that you want users to download. Note: Instagram galleries will always attempt to pull the oringial image size.', 'envira-downloads' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-downloads-all-box">
                        <th scope="row">
                            <label for="envira-config-downloads-all"><?php _e( 'Display Download All Button?', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-downloads-all" type="checkbox" name="<?php echo $key; ?>[download_all]" value="1" <?php checked( $instance->get_config( 'download_all', $instance->get_config_default( 'download_all' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'If enabled, displays a Download All option.', 'envira-downloads' ); ?></span>
                        </td>
                    </tr>

                    <tr id="envira-config-downloads-all-custom-name">
                        <th scope="row">
                            <label for="envira-config-downloads-all"><?php _e( 'Custom name the zip file?', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="envira-config-downloads-all-custom-name" name="<?php echo $key; ?>[download_custom_name]" value="<?php echo esc_attr( $instance->get_config( 'download_custom_name', $instance->get_config_default( 'download_custom_name' ) ) ); ?>" />.zip
                            <p class="description"><?php _e( 'If you do not provide a custom name, the default is "envira-gallery.zip".', 'envira-downloads' ); ?></p>
                        </td>
                    </tr>

                    <tr id="envira-config-downloads-all-position-box">
                        <th scope="row">
                            <label for="envira-config-downloads-all-position"><?php _e( 'Download All Button Position', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-downloads-all-position" name="<?php echo $key; ?>[download_all_position]">
                                <?php foreach ( (array) $this->get_positions_all() as $value => $name ) : ?>
                                    <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'download_all_position', $instance->get_config_default( 'download_all_position' ) ) ); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Where to display the download all button in relation to the Gallery.', 'envira-downloads' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-downloads-all-label-box">
                        <th scope="row">
                            <label for="envira-config-downloads-all-label"><?php _e( 'Download All Button Label', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <input type="text" id="envira-config-downloads-all-label" name="<?php echo $key; ?>[download_all_label]" value="<?php echo esc_attr( $instance->get_config( 'download_all_label', $instance->get_config_default( 'download_all_label' ) ) ); ?>" />
                            <p class="description"><?php _e( 'The label to display on the Download All button.', 'envira-downloads' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-downloads-position-box">
                        <th scope="row">
                            <label for="envira-config-downloads-position"><?php _e( 'Download Button Position', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-downloads-position" name="<?php echo $key; ?>[download_position]">
                                <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                    <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'download_position', $instance->get_config_default( 'download_position' ) ) ); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Where to display the download button over the image.', 'envira-downloads' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-downloads-force-box">
                        <th scope="row">
                            <label for="envira-config-downloads-force"><?php _e( 'Force Download?', 'envira-downloads' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-downloads-force" type="checkbox" name="<?php echo $key; ?>[download_force]" value="1" <?php checked( $instance->get_config( 'download_force', $instance->get_config_default( 'download_force' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'If enabled, prompts a file download in the browser instead of display the image in a new browser window/tab.', 'envira-downloads' ); ?></span>
                        </td>
                    </tr>
                    <?php
                    if ( class_exists( 'Envira_Password_Protection' ) ) {
                        ?>
                        <tr id="envira-config-downloads-password-box">
                            <th scope="row">
                                <label for="envira-config-downloads-password"><?php _e( 'Download Password', 'envira-password-protection' ); ?></label>
                            </th>
                            <td>
                                <input id="envira-config-downloads-password" type="text" name="<?php echo $key; ?>[password_protection_download]" value="<?php echo $instance->get_config( 'password_protection_download', $instance->get_config_default( 'password_protection_download' ) ); ?>" />
                                <p class="description"><?php _e( 'If defined, requires the visitor to enter this password when they attempt to download an image.', 'envira-password-protection' ); ?></p>
                            </td>
                        </tr>
                        <tr id="envira-config-downloads-invalid-password-box">
                            <th scope="row">
                                <label for="envira-config-downloads-invalid-password-message"><?php _e( 'Invalid Password Message', 'envira-password-protection' ); ?></label>
                            </th>
                            <td>
                                <textarea id="envira-config-downloads-invalid-password" type="text" name="<?php echo $key; ?>[download_invalid_password_message]"><?php echo $instance->get_config( 'download_invalid_password_message', $instance->get_config_default( 'download_invalid_password_message' ) ); ?></textarea>
                                <p class="description"><?php _e( 'The message to display if the visitor enters an incorrect password.', 'envira-password-protection' ); ?></p>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <?php
        } // Close Gallery Check

        // Lightbox Options
        ?>
        <p class="envira-intro">
            <?php _e( 'Downloads Lightbox Settings', 'envira-downloads' ); ?>
            <small>
                <?php _e( 'The settings below adjust the image download options on your Lightbox output.', 'envira-downloads' ); ?>
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <tr id="envira-config-downloads-lightbox-box">
                    <th scope="row">
                        <label for="envira-config-download-lightbox"><?php _e( 'Display Download Button?', 'envira-downloads' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-download-lightbox" type="checkbox" name="<?php echo $key; ?>[download_lightbox]" value="1" <?php checked( $instance->get_config( 'download_lightbox', $instance->get_config_default( 'download_lightbox' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Enables or disables displaying the download button on each image in the Lightbox view.', 'envira-downloads' ); ?></span>
                    </td>
                </tr>
                <tr id="envira-config-downloads-lightbox-position-box">
                    <th scope="row">
                        <label for="envira-config-downloads-lightbox-position"><?php _e( 'Download Button Position', 'envira-downloads' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-download-lightbox-position" name="<?php echo $key; ?>[download_lightbox_position]">
                            <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'download_lightbox_position', $instance->get_config_default( 'download_lightbox_position' ) ) ); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Where to display the download button over the image.', 'envira-download' ); ?></p>
                    </td>
                </tr>
                <tr id="envira-config-downloads-lightbox-force-box">
                    <th scope="row">
                        <label for="envira-config-download-lightbox-force"><?php _e( 'Force Download?', 'envira-downloads' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-download-force-lightbox" type="checkbox" name="<?php echo $key; ?>[download_lightbox_force]" value="1" <?php checked( $instance->get_config( 'download_lightbox_force', $instance->get_config_default( 'download_lightbox_force' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'If enabled, prompts a file download in the browser instead of display the image in a new browser window/tab.', 'envira-downloads' ); ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
    
    }

    /**
     * Helper method for retrieving positions for the Download All option.
     *
     * @since 1.0.1
     *
     * @return array Array of position data.
     */
    public function get_positions_all() {

        $instance = Envira_Downloads_Common::get_instance();
        return $instance->get_positions_all();

    }

    /**
     * Helper method for retrieving positions.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_positions() {

        $instance = Envira_Downloads_Common::get_instance();
        return $instance->get_positions();

    }

    /**
     * Helper method for retrieving image sizes.
     *
     * @since 1.0.1
     *
     * @param   bool    $wordpress_only     WordPress Only image sizes (default: false)
     * @return array Array of image size data.
     */
    public function get_image_sizes( $wordpress_only = false ) {

        $instance = Envira_Downloads_Common::get_instance();
        return $instance->get_image_sizes( $wordpress_only );

    }
    
    /**
     * Saves the addon's settings for Galleries.
     *
     * @since 1.0.0
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function gallery_settings_save( $settings, $post_id ) {
        
        // Gallery
        $settings['config']['download_all']               = ( isset( $_POST['_envira_gallery']['download_all'] ) ? 1 : 0 );
        $settings['config']['download_all_position']      = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['download_all_position'] );
        $settings['config']['download_all_label']         = sanitize_text_field( esc_attr( $_POST['_envira_gallery']['download_all_label'] ) );
        $settings['config']['download']                   = ( isset( $_POST['_envira_gallery']['download'] ) ? 1 : 0 );
        $settings['config']['download_custom_name']       = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['download_custom_name'] );
        $settings['config']['download_position']          = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['download_position'] );
        if ( isset( $_POST['_envira_gallery']['download_image_size'] ) ) {
            $settings['config']['download_image_size']    = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['download_image_size'] );
        }
        $settings['config']['download_force']             = ( isset( $_POST['_envira_gallery']['download_force'] ) ? 1 : 0 );
        if ( isset( $_POST['_envira_gallery']['password_protection_download'] ) ) {
            $settings['config']['password_protection_download'] = sanitize_text_field( esc_attr( $_POST['_envira_gallery']['password_protection_download'] ) );
            $settings['config']['download_invalid_password_message'] = sanitize_text_field( esc_attr( $_POST['_envira_gallery']['download_invalid_password_message'] ) );
        }

        // Lightbox
        $settings['config']['download_lightbox']          = ( isset( $_POST['_envira_gallery']['download_lightbox'] ) ? 1 : 0 );
        $settings['config']['download_lightbox_position'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['download_lightbox_position'] );
        $settings['config']['download_lightbox_force']    = ( isset( $_POST['_envira_gallery']['download_lightbox_force'] ) ? 1 : 0 );
        
        return $settings;
    
    }

    /**
     * Saves the addon's settings for Albums.
     *
     * @since 1.0.0
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function album_settings_save( $settings, $post_id ) {
        
        // Lightbox
        $settings['config']['download_lightbox']          = ( isset( $_POST['_eg_album_data']['config']['download_lightbox'] ) ? 1 : 0 );
        $settings['config']['download_lightbox_position'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_eg_album_data']['config']['download_lightbox_position'] );
        $settings['config']['download_lightbox_force']    = ( isset( $_POST['_eg_album_data']['config']['download_lightbox_force'] ) ? 1 : 0 );
        
        return $settings;
    
    }
    
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Downloads_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Downloads_Metaboxes ) ) {
            self::$instance = new Envira_Downloads_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_downloads_metaboxes = Envira_Downloads_Metaboxes::get_instance();