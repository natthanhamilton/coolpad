<?php
/*
 * Hook into WordPress Automatic Updates
 *
 * Based On Framework Provided By omarabid
 * https://github.com/omarabid/Self-Hosted-WordPress-Plugin-repository
 */
class BolderElements_Plugin_Updater {
    /**
     * Author name
     *
     * @var string
     */
    public $author_name = "Bolder Elements";

    /**
     * Envato Plugin ID
     *
     * @var string
     */
    public $plugin_id;

    /**
     * Envato Plugin ID
     *
     * @var string
     */
    public $plugin_name;

    /**
     * The plugin slug
     *
     * @var string
     */
    private $plugin_slug;

    /**
     * The plugin directory
     *
     * @var string
     */
    private $plugin_dir;

    /**
     * The plugin current version
     *
     * @var string
     */
    public $current_version;

    /**
     * URL to plugin update api
     *
     * @var string
     */
    private $remote_url;

    /**
     * Data taken from plugin file meta data
     *
     * @var array
     */
    private $pluginData;

    /**
     * If plugin is activated
     *
     * @var bool
     */
    public $pluginActivated;
 
    /**
     * Initialize a new instance of the WordPress Auto-Update class
     * @param string $plugin_file
     * @param string $current_version
     */
    function __construct( $plugin_file, $current_version, $plugin_id, $plugin_slug, $plugin_name ) {
        // Set the class public variables
        $this->plugin_id = $plugin_id;
        $this->plugin_slug = $plugin_slug;
        $this->plugin_name = $plugin_name;
        $this->plugin_file = $plugin_file;
        $this->plugin_dir = plugin_basename( $this->plugin_file );
        $this->current_version = $current_version;
        $this->remote_url = "http://bolderelements.net/updates/" . $this->plugin_slug;

        // define the alternative API for updating and information checking
        add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'check_update' ) );
        add_filter( 'plugins_api', array( $this, 'check_info' ), 10, 3 );

        // modify error messages when downloading
        add_filter( "upgrader_pre_download", array( $this, "updatePackageEnvato"), 10, 4 );
        add_filter( "upgrader_pre_install", array( $this, "preInstall" ), 10, 3 );
        add_filter( "upgrader_post_install", array( $this, "postInstall" ), 10, 3 );
        add_filter( "in_plugin_update_message-" . $this->plugin_dir, array( $this, "update_message" ), 10, 2 );

    }


    /**
     * Retrieve Plugin Data
     *
     * @param $transient
     * @return object $ transient
     */
    function initPluginData () {
        $this->pluginData = get_plugin_data( $this->plugin_file );
    }


    /**
     * Display update message if provided
     *
     * @param $transient
     * @return object $ transient
     */
    function update_message( $plugin_data, $r ) {
        // Retrieve update message
        $information = $this->getRemote_information();

        // Display message if one is set
        if( isset( $information->update_message ) && !empty( $information->update_message ) ) :
?>
    <p style="background: #fbe3e4; border: 1px solid #d77163; padding: 5px 15px; margin-top: 10px;"><?php echo wp_kses_post( $information->update_message ); ?></p>
<?php
        endif;
    }


    /**
     * Add our self-hosted autoupdate plugin to the filter transient
     *
     * @param $transient
     * @return object $ transient
     */
    public function check_update( $transient ) {
        if( empty( $transient->checked ) )
            return $transient;

        // Get plugin information
        $this->initPluginData();
        
        // Get the remote version
        $remote_version = $this->getRemote_version();
 
        // If a newer version is available, add the update
        if( version_compare( $this->current_version, $remote_version, '<' ) ) {
            $obj = new stdClass();
            $obj->slug = $this->plugin_slug;
            $obj->plugin = $this->plugin_dir;
            $obj->name  = $this->plugin_name;
            $obj->new_version = $remote_version;
            $obj->package = $this->plugin_slug; // Allows 'View Details' link to appear
            $transient->response[ $this->plugin_dir ] = $obj;

        }

        return $transient;
    }
 
    /**
     * Add our self-hosted description to the filter
     *
     * @param boolean $false
     * @param array $action
     * @param object $arg
     * @return bool|object
     */
    public function check_info( $false, $action, $response ) {
        $array_pattern = array(
            '/^((\d)+(\.)+(\d)+(\.)*(\d)*([\s\-\s])+([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])))/m',
            '/^(\t\-)+([a-zA-Z0-9,.:&()\/\-\'\"\ ]+)/m',
            '/\n\n/',
        );
        $array_replace = array(
            '<h4>$1</h4><ul>',
            '<li>$2</li>',
            '</ul>'
        );

        // Create tabs in the lightbox
        if( isset( $response->slug ) && $response->slug === $this->plugin_slug ) {
            $this->pluginData = get_plugin_data( $this->plugin_file );
            $information = $this->getRemote_information(); 
            $information->package = $this->plugin_slug; // Allows 'View Details' link to appear
            $information->name  = $this->plugin_name;
            $information->author = $this->author_name;
            $information->sections['changelog'] = '<div>' . preg_replace( $array_pattern, $array_replace, $information->sections['changelog'] ) . '</div>';
            return $information;
        }

        return $false;
    }
 
    /**
     * Perform check before installation starts.
     *
     * @param  boolean $true
     * @param  array   $args
     * @return null
     */
    public function preInstall( $true, $args ) {
        // Get plugin information
        $this->initPluginData();
        $this->pluginActivated = is_plugin_active( $this->plugin_dir );
    }
 
    /**
     * Perform additional actions to successfully install our plugin
     *
     * @param  boolean $true
     * @param  string $hook_extra
     * @param  object $result
     * @return object
     */
    public function postInstall( $true, $hook_extra, $result ) {
        global $wp_filesystem;
 
        // Re-activate plugin if needed
        if ( $this->pluginActivated )
            $activate = activate_plugin( $this->plugin_dir );
 
        return $result;
    }
 
    /**
     * Return the remote version
     * @return string $remote_version
     */
    public function getRemote_version() {
        $request = wp_remote_post( $this->remote_url, array( 'body' => array( 'action' => 'version' ) ) );
        if( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
            return $request[ 'body' ];
        }
        return false;
    }
 
    /**
     * Get information about the remote version
     * @return bool|object
     */
    public function getRemote_information() {
        $request = wp_remote_post( $this->remote_url, array( 'body' => array( 'action' => 'info' ) ) );
        if (!is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200) {
            return unserialize( $request[ 'body' ] );
        }
        return false;
    }


    /**
     * Retrieve CodeCanyon download link if registration works
     * @return void
     */
    function getEnvatoUpdateInfo() {
        // select data from database
        $settings = get_site_option( 'be_config_data-' . $this->plugin_id );
        $return = array();

        if( $settings && is_array( $settings ) ) {
            $return[ 'username' ] = ( isset( $settings[ 'username' ] ) ) ? $settings[ 'username' ] : '';
            $return[ 'api_key' ] = ( isset( $settings[ 'api_key' ] ) ) ? $settings[ 'api_key' ] : '';
            $return[ 'purchase_code'] = ( isset( $settings[ 'purchase_code' ] ) ) ? $settings[ 'purchase_code' ] : '';
        } else
            $return = false;

        return $return;
    }
 
    /**
     * Return the download of the plugin
     * @return boolean $remote_license
     */
	protected function envatoDownloadPluginUrl( $username, $purchase_code, $api_key ) {

		return 'http://marketplace.envato.com/api/edge/' . rawurlencode( $username ) . '/' . rawurlencode( $api_key ) . '/download-purchase:' . rawurlencode( $purchase_code ) . '.json';
	}


    /**
     * Update package variable to correct downloadable package
     *
     * @param $reply
     * @param $package
     * @param $updater
     * @return mixed|string|WP_Error
     */
    public function updatePackageEnvato( $reply, $package, $updater ) {
        global $wp_filesystem;

        // Verify proper update
        if( !isset( $updater->skin->plugin_info ) && !isset( $updater->skin->plugin ) ) return $reply;

        // Verify currently updating plugin: Bulk Update & Quick Update
        if( ( isset( $updater->skin->plugin_info ) && $updater->skin->plugin_info['Name'] !== $this->plugin_name ) ||
            ( isset( $updater->skin->plugin ) && $updater->skin->plugin !== $this->plugin_dir ) ) return $reply;

        $user_settings = $this->getEnvatoUpdateInfo();

        if( $user_settings && is_array( $user_settings ) && ( isset( $user_settings[ 'username' ] ) && isset( $user_settings[ 'api_key' ] ) && isset( $user_settings[ 'purchase_code' ] ) ) ) {
            // Change download text to be more accurate
            $updater->strings['downloading_package'] = __( 'Downloading package from Envato Marketplace', 'be-config' ) . '...';

            // Use saved credentials to retrieve plugin package info
            $download_query = wp_remote_get( $this->envatoDownloadPluginUrl( $user_settings[ 'username' ], $user_settings[ 'purchase_code' ], $user_settings[ 'api_key' ] ) );
            $download_url = json_decode( $download_query['body'], true );
            if( !isset( $download_url[ 'download-purchase' ][ 'download_url' ] ) )
                return new WP_Error( 'invalid_credentials', __( 'Could not connect to Envato API', 'be-config' ) );

            // Retrieve downloadable package or error message
            $download_pkg = download_url( $download_url[ 'download-purchase' ][ 'download_url' ] );

            if( is_wp_error( $download_pkg ) )
                return $download_pkg;

            // Setup temporary 'uploads' directory
            $tmp_folder = $wp_filesystem->wp_content_dir() . 'uploads/' . $this->plugin_slug;
            if( is_dir( $tmp_folder ) )
                $wp_filesystem->delete( $tmp_folder );

            // Create temporary directory for updating
            $result = unzip_file( $download_pkg, $tmp_folder );
            $pkg_location = $tmp_folder . '/' . $this->plugin_slug . '.zip';
            if ( $result && is_file( $pkg_location ) )
                return $pkg_location;
        } else
            return new WP_Error( 'missing_credentials', __( "Missing login credentials", 'be-config' ) . '. <a href="' . admin_url( 'admin.php?page=be-manage-plugins' ) . '" target="_blank">' . __( 'Register Plugin', 'be-config' ) . '</a>' );

        return $reply;
    }

}

?>