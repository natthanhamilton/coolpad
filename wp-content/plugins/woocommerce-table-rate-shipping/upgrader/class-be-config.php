<?php
/**
 * Bolder Elements Dashboard Configuration.
 *
 * @author 		Bolder Elements
 * @category 	Inc
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Bolder_Elements_Config' ) ) :

	require_once( 'class-be-updater.php' );

	/**
	 * Bolder_Elements_Config
	 */
	class Bolder_Elements_Config {

		/**
		 * Constructor.
		 */
		public function __construct() {

			add_filter( 'init', array( &$this, 'init' ) );
			add_action( 'admin_menu', array( &$this, 'add_option_page' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
			add_action( 'admin_footer', array( $this, 'add_script_admin' ) );

			// AJAX functions
			add_action( 'wp_ajax_be_config_register_plugin', array( &$this, 'register_plugin' ) );
			add_action( 'wp_ajax_be_config_remove_plugin', array( &$this, 'remove_plugin' ) );
		}


		/**
		* init function.
		* initialize variables to be used
		*
		* @access public
		* @return void
		*/
		function init() {
			$this->admin_page_heading = __( 'Bolder Elements Dashboard', 'be-config' );
			$this->admin_page_description = __( 'Manage plugins and support status by registering your purchase codes here', 'be-config' );
		}


		/**
		* init function.
		* initialize variables to be used
		*
		* @access public
		* @return void
		*/
		function add_option_page() {
			add_dashboard_page( "Bolder Elements", "Bolder Elements", "install_plugins", "be-manage-plugins", array( $this, "dashboard_plugins" ) );
		}


		/**
		 * Display Plugins Page
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function dashboard_plugins() {
			// check if PID is submitted
			if( isset( $_GET['pid'] ) && is_numeric( $_GET['pid'] ) ) {
				$this->plugins_activate( (int) $_GET['pid'] );
			} else {
				$this->plugins_listing();
			}
		}


		/**
		 * Display Plugins Page
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function plugins_listing() {
			$activated_plugins = $this->get_activated_plugins();
			$available_plugins = $this->get_available_plugins();
?>
			<h1>Manage Your Bolder Plugins</h1>
			<p class="header_description">View your activated plugins and more available to you</p>
			<h2>Your Registered Plugins</h2>
			<div id="be-registered-plugins">
<?php
			$REGISTERED = false;
			foreach( $available_plugins as $key => $plugin ) {
				// Check is settings exists (plugin is registered)
				$settings = get_site_option( 'be_config_data-' . $plugin[ 'id' ] );
				if( $settings && is_array( $settings ) && ( isset( $settings[ 'username' ] ) && isset( $settings[ 'api_key' ] ) && isset( $settings[ 'purchase_code' ] ) ) ) {
					$this->display_plugin_card( $plugin, true );

					// remove registered plugin from array of unregistered plugins
					unset( $available_plugins[ $key ] );
					$REGISTERED = true;
				}
			}

			if( !$REGISTERED )
				echo "<p><em>" . __( 'You have not registered any plugins yet', 'be-config' ) . "</em></p>";
?>
			</div>
			<div style="clear:both;"></div>

			<h2>Available Plugins</h2>
<?php
			if( !empty( $available_plugins ) && is_array( $available_plugins ) ) :
?>
			<div id="be-manage-plugins">
<?php
				// display each plugin's information
				foreach( $available_plugins as $key => $plugin ) {
					$this->display_plugin_card( $plugin );
				}
			elseif( is_string( $available_plugins ) ) :
				echo $available_plugins;
			endif;
		}


		/**
		 * Get User Activated BE Plugins
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array
		 */
		public function get_activated_plugins() {

			return array();
		}


		/**
		 * Display Plugin Information Card
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array|string
		 */
		function display_plugin_card( $plugin, $registered = false ) {
?>
				<div id="plugin-card-<?php echo $plugin['id']; ?>" class="plugin-card">
					<div class="plugin-card-top" plugin-id="<?php echo $plugin['id']; ?>">
						<a href="<?php echo esc_url( $plugin['url'] ); ?>" class="plugin-icon" target="_blank">
							<img src="<?php echo esc_attr( $plugin['thumbnail'] ); ?>" width="80" height="80" style="width:80px;height:80px;" /></a>
						<div class="name column-name" style="margin-left: 100px;">
							<h4><a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank"><?php echo $plugin['item']; ?></a></h4>
							<?php if( $registered ) : ?>
							<p class="meta-links">
								<a href="http://codecanyon.net/downloads#item-<?php echo $plugin['id']; ?>" target="_blank">Rate Plugin</a> |
								<a href="http://bolderelements.net/support/" target="_blank">Get Support</a>
							</p>
							<?php endif; ?>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<?php if( $registered ) : ?>
								<li><a href="#" class="button remove">Remove Settings</a></li>
								<?php else : ?>
								<li><a href="#" class="button register">Add Purchase Code</a></li>
								<!-- <li><a href="<?php echo esc_url( $plugin['live_preview_url'] ); ?>">Live Demo</a></li> -->
								<?php endif; ?>
							</ul>
						</div>
						<div class="desc column-description"></div>
					</div>
					<div class="plugin-card-bottom">
						<div class="vers column-rating">
							<?php wp_star_rating( array( 'rating' => $plugin['rating_decimal'], 'type' => 'rating', 'number' => 1 ) ); ?>
						</div>
						<div class="column-updated">
							<strong><?php _e( 'Last Updated' ); ?>:</strong> <span title="<?php echo esc_attr( $plugin['last_update'] ); ?>">
								<?php printf( __( '%s ago' ), human_time_diff( strtotime( $plugin['last_update'] ) ) ); ?>
							</span>
						</div>
						<div class="column-downloaded">
							<?php echo sprintf( _n( '%s Purchase', '%s Purchases', $plugin['sales'] ), number_format_i18n( $plugin['sales'] ) ); ?>
						</div>
						<div class="column-compatibility">
							<strong>Price:</strong> $<?php echo $plugin['cost']; ?> USD
						</div>
					</div>
				</div>
<?php
		}


		/**
		 * Get Current BE Plugins (CodeCanyon)
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array|string
		 */
		public function get_available_plugins() {
			// connect to Envato API
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/v3/new-files-from-user:bolderelements,codecanyon.json');
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'bolder-elements');
			$ch_data = curl_exec($ch);
			curl_close($ch);

			if( !empty( $ch_data ) ) :
				$json_data = json_decode($ch_data, true);
			else :
				return __( 'Sorry, but there was a problem connecting to the API', 'be-config' );
			endif;

			return $json_data["new-files-from-user"];
		}


		/**
		 * Verify user input credentials (CodeCanyon)
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array|string
		 */
		function verify_plugin_registration_info( $purchase_code ) {
			// connect to Envato API
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/v3/bolderelements/ofdzk2su2101c1bq4d5vs1zqwdaj88ms/verify-purchase:' . $purchase_code . '.json');
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'bolder-elements');
			$ch_data = curl_exec($ch);
			curl_close($ch);

			if( !empty( $ch_data ) ) :
				$json_data = json_decode($ch_data, true);
				if( is_array( $json_data ) && isset( $json_data[ 'code' ] ) && $json_data[ 'code' ] == 'not_authenticated' )
					return __( 'Username and/or API Key invalid', 'be-config' );
				elseif( is_array( $json_data ) && isset( $json_data[ 'verify-purchase' ] ) )
					return $json_data['verify-purchase'];
				else
					return __( 'Sorry, but there was a problem connecting to the API', 'be-config' );
			endif;

			return __( 'Sorry, but there was a problem connecting to the API', 'be-config' );
		}


		/**
		 * Verify user input credentials (CodeCanyon)
		 *
		 * @since 1.0.0
		 * @access public
		 * @return string
		 */
		function get_plugin_download_file( $username, $api_key, $purchase_code ) {
			// connect to Envato API
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://marketplace.envato.com/api/v3/' . $username . '/' . $api_key . '/download-purchase:' . $purchase_code . '.json');
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT, 'bolder-elements');
			$ch_data = curl_exec($ch);
			curl_close($ch);

			if( !empty( $ch_data ) ) :
				$json_data = json_decode($ch_data, true);
				if( is_array( $json_data ) && isset( $json_data[ 'code' ] ) && $json_data[ 'code' ] == 'not_authenticated' )
					return __( 'Username and/or API Key invalid', 'be-config' );
				elseif( is_array( $json_data ) && isset( $json_data[ 'download-purchase' ] ) )
					return 'true';
				else
					return __( 'Sorry, but there was a problem connecting to the API', 'be-config' );
			endif;

			return __( 'Sorry, but there was a problem connecting to the API', 'be-config' );
		}


		/**
		 * Verify/Register user input credentials (CodeCanyon)
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array|string
		 */
		public function register_plugin() {
			//sanitize input fields
			$plugin_id = (double) $_POST[ 'pid' ];
			$username = sanitize_text_field( $_POST[ 'env_username' ] );
			$purchase_code = sanitize_title( $_POST[ 'env_purchase_code' ] );
			$api_key = sanitize_title( $_POST[ 'env_api_key' ] );

			if( !empty( $plugin_id ) && !empty( $username ) && !empty( $purchase_code ) && !empty( $api_key ) ) {
				//once data is validated, test that the credentials work and are valid for this plugin
				$validation = $this->verify_plugin_registration_info( $purchase_code );
				if( is_array( $validation ) ) {
					// ensure that the purchase code given is valid only for the plugin they are registering
					if( isset( $validation[ 'item_id' ] ) && $validation[ 'item_id' ] == $plugin_id ) {
						// ensure the given username matches the one on the license
						if( isset( $validation[ 'buyer' ] ) && $validation[ 'buyer' ] == $username ) {
							// save data fields and return success message to form
							$save_data = array(
								'username'		=> $username,
								'purchase_code'	=> $purchase_code,
								'api_key'		=> $api_key,
								);
							update_site_option( 'be_config_data-' . $plugin_id, $save_data );
							echo $this->plugins_listing();
						} else
							echo '<div class="error"><p>' . __( 'Error', 'be-config' ) . ': ' . __( 'The username provided does not match the name on the license', 'be-config' ) . '</p></div>';
					} else
						echo '<div class="error"><p>' . __( 'Error', 'be-config' ) . ': ' . __( 'The purchase code provided is not for the selected plugin', 'be-config' ) . '</p></div>';
				} else
					echo '<div class="error"><p>' . $validation . '</p></div>';
			} else
				echo '<div class="error"><p>' . __( 'Error', 'be-config' ) . ': ' . __( 'An error occured when processing the information provided', 'be-config' ) . '</p></div>';

			die();
		}


		/**
		 * Remove Saved Settings for Plugin
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array|string
		 */
		public function remove_plugin() {
			//sanitize input fields
			$plugin_id = (double) $_POST[ 'pid' ];

			$settings = get_site_option( 'be_config_data-' . $plugin_id );
			if( $settings && is_array( $settings ) ) {
				delete_site_option( 'be_config_data-' . $plugin_id );

				echo $this->plugins_listing();
			} else
				echo '<div class="error"><p>' . __( 'Error', 'be-config' ) . ': ' . __( 'Settings for this plugin could not be found', 'be-config' ) . '</p></div>';

			die();
		}


		/**
		 * Add javascript functions to frontend
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function register_plugin_styles() {
			wp_enqueue_script( 'be_config_js', plugins_url( 'upgrader/assets/updater.js', dirname(__FILE__) ), array( 'jquery' ), '1.0', true );
			wp_enqueue_style( 'be_config_css', plugins_url( 'upgrader/assets/updater.css', dirname(__FILE__) ), '1.0', true );
		}


		/**
		 * Add Script Directly to Dashboard Foot
		 */
		public function add_script_admin() {

			// Setup translated strings
			$text_activate_license  = __( 'Activate Your License', 'be-config' );
			$text_activate_desc		= __( 'Enter the following information to unlock additional features', 'be-config' );
			$text_env_username 		= __( 'Envato Marketplace Username', 'be-config' );
			$text_env_api_key	 	= __( 'API Key', 'be-config' );
			$text_env_purchase 		= __( 'Purchase Code', 'be-config' );
			$text_remove_plugin		= __( 'Are you sure you want to remove the settings for this plugin', 'be-config' );
			$text_remove_plugin_desc= __( 'This action cannot be undone', 'be-config' );
			$text_success 			= __( 'Success', 'be-config' );
			$text_close 			= __( 'Close', 'be-config' );
			$text_register 			= __( 'Register', 'be-config' );
			$text_remove 			= __( 'Remove', 'be-config' );
			$text_cancel 			= __( 'Cancel', 'be-config' );
			$error_incomplete_form	= __( 'Error', 'be-config' ) . ': ' . __( 'There are empty required fields in your form.', 'be-config' );
?>
<script type='text/javascript'>
/* <![CDATA[ */
var be_config_data = {"ajax_url":"<?php echo addcslashes( admin_url( 'admin-ajax.php', 'relative' ), '/' ); ?>","ajax_loader_url":"<?php echo addcslashes( plugins_url( 'assets/img/loader.gif', __FILE__ ), '/' ); ?>","text_activate_license":"<?php echo $text_activate_license; ?>","text_activate_desc":"<?php echo $text_activate_desc; ?>","text_envato_username":"<?php echo $text_env_username; ?>","text_api_key":"<?php echo $text_env_api_key; ?>","text_purchase_code":"<?php echo $text_env_purchase; ?>","text_success":"<?php echo $text_success; ?>","text_close":"<?php echo $text_close; ?>","text_register":"<?php echo $text_register; ?>","text_remove":"<?php echo $text_remove; ?>","text_cancel":"<?php echo $text_cancel; ?>","error_incomplete_form":"<?php echo $error_incomplete_form; ?>","text_remove_plugin":"<?php echo $text_remove_plugin; ?>","text_remove_plugin_desc":"<?php echo $text_remove_plugin_desc; ?>"};
/* ]]> */
</script>
<?php
				}

	}

	return new Bolder_Elements_Config();

endif;

?>