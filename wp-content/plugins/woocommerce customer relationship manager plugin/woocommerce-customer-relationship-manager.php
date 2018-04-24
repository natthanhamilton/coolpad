<?php
/**
 * Plugin Name: WooCommerce Customer Relationship Manager
 * Plugin URI: http://actualityextensions.com/
 * Description: Allows for better overview of WooCommerce customers, communication with customers, listing amount spent by customers for certain period and more!
 * Version: 2.0
 * Author: Actuality Extensions
 * Author URI: http://actualityextensions.com/
 * Tested up to: 3.7.1
 *
 * Copyright: (c) 2012-2013 Actuality Extensions
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     WC-Customer-Relationship-Manager
 * @author      Actuality Extensions
 * @category    Plugin
 * @copyright   Copyright (c) 2012-2013, Actuality Extensions
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) return; // Check if WooCommerce is active

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

if ( !class_exists( 'MCAPI_Wc_Crm' ) ) {
	require_once( 'admin/classes/api/MCAPI.class.php' );
}
if ( !class_exists( 'WooCommerce_Customer_Relationship_Manager' ) ) {

	class WooCommerce_Customer_Relationship_Manager {


		public function __construct() {
      global $wc_crm_db_version;
      $wc_crm_db_version = "1.2";

      // installation after woocommerce is available and initialized
        if (is_admin() && !defined('DOING_AJAX'))
            add_action('woocommerce_init', array($this, 'wc_crm_install'));


			$this->current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

			// settings tab
			$this->settings_tabs = array(
				'customer_relationship' => __( 'Customer Relationship', 'wc_customer_relationship_manager' )
			);

			add_action( 'admin_enqueue_scripts', array($this, 'enqueue_dependencies_admin') );

			add_action( 'woocommerce_settings_tabs', array($this, 'add_tab'), 10 );

			// Run these actions when generating the settings tabs.
			foreach ( $this->settings_tabs as $name => $label ) {
				add_action( 'woocommerce_settings_tabs_' . $name, array($this, 'settings_tab_action'), 10 );
				add_action( 'woocommerce_update_options_' . $name, array($this, 'save_settings'), 10 );
			}

			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array($this, 'action_links') );

			// Add the settings fields to each tab.
			add_action( 'woocommerce_customer_relationship_settings', array($this, 'add_settings_fields'), 10 );

			add_action( 'woocommerce_init', array($this, 'includes') );
      add_action( 'wc_crm_restrict_list_customers', array($this, 'woocommerce_crm_restrict_list_customers') );
			add_action( 'wc_crm_restrict_list_logs', array($this, 'woocommerce_crm_restrict_list_logs') );

			add_filter( 'woocommerce_shop_order_search_fields', array($this, 'woocommerce_crm_search_by_email') );
			add_filter( 'views_edit-shop_order', array($this, 'views_shop_order') );
			add_action( 'admin_post_export_csv', array($this, 'export_csv') );

			/*AJAX EVENTS*/
			add_action( 'wp_ajax_woocommerce_crm_json_search_customers', array( $this, 'json_search_customers') );
      add_action( 'wp_ajax_woocommerce_crm_json_search_state', array( $this, 'json_search_state') );
      add_action( 'wp_ajax_woocommerce_crm_json_search_variations', array( $this, 'json_search_variations') );

      add_action( 'wp_ajax_woocommerce_crm_add_customer_note', array( $this, 'add_customer_note_ajax') );
			add_action( 'wp_ajax_woocommerce_crm_delete_customer_note', array( $this, 'delete_customer_note_ajax') );

      add_filter('user_contactmethods', array( $this, 'modify_contact_methods') );
      add_action( 'show_user_profile', array( $this, 'add_user_field_status') );
      add_action( 'edit_user_profile', array( $this, 'add_user_field_status') );

      add_action( 'personal_options_update', array( $this, 'save_user_field_status')  );
      add_action( 'edit_user_profile_update', array( $this, 'save_user_field_status')  );

      add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'select_customer_id' ) );


		}

		public function select_customer_id(){
      if( isset($_GET['user_id']) && !empty($_GET['user_id']) ){
        $user_id = $_GET['user_id'];
        wc_enqueue_js( "
          jQuery('#customer_user').append('<option selected=\'selected\' value=\'".$user_id."\'>".get_the_author_meta( 'user_firstname', $user_id ) . " " . get_the_author_meta( 'user_lastname', $user_id )." (#".$user_id." – ".get_the_author_meta( 'user_email', $user_id ).")</option>')
        " );

      }
    }
    public function activate(){
			$this->wc_crm_install();
		}

		public function wc_crm_install() {
			global $wpdb;
			global $wc_crm_db_version;
      $wpdb->hide_errors();
      $installed_ver = get_option( "wc_crm_db_version" );
#print_R($installed_ver);
#print_R('--');
#print_R($wc_crm_db_version);
      if( $installed_ver != $wc_crm_db_version ){

        $collate = '';
                if ($wpdb->has_cap('collation')) {
                    if (!empty($wpdb->charset))
                        $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                    if (!empty($wpdb->collate))
                        $collate .= " COLLATE $wpdb->collate";
                }

        // initial install
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $table_name = $wpdb->prefix . "wc_crm_log";
  			$sql = "CREATE TABLE $table_name (
  							ID bigint(20) NOT NULL AUTO_INCREMENT,
  							subject text NOT NULL,
  							activity_type VARCHAR(50) DEFAULT '' NOT NULL,
  							user_id bigint(20) NOT NULL,
  							created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                message text NOT NULL,
                user_email text NOT NULL,
                phone text NOT NULL,
                call_type text NOT NULL,
                call_purpose text NOT NULL,
                related_to text NOT NULL,
                number_order_product text NOT NULL,
                call_duration text NOT NULL,
                log_status text NOT NULL,
  							PRIMARY KEY  (id)
  			)" . $collate;
  			dbDelta( $sql );

        if(get_option( "wc_crm_db_version" )) {
          update_option( "wc_crm_db_version", $wc_crm_db_version );
        }else{
          add_option( "wc_crm_db_version", $wc_crm_db_version );
        }
      }
		}

		/*function wc_crm_install_data() {
			global $wpdb;
			$welcome_name = "Mr. WordPress";
			$welcome_text = "Congratulations, you just completed the installation!";
			$table_name = $wpdb->prefix . "wc_crm_log";
			$rows_affected = $wpdb->insert( $table_name, array( 'time' => current_time('mysql'), 'name' => $welcome_name, 'text' => $welcome_text ) );
		}*/


		/**
		 * The plugin's id
		 * @var string
		 */
		var $id = 'wc-customer-relationship-manager';

		/**
		 * Enqueue admin CSS and JS dependencies
		 */
		public function enqueue_dependencies_admin() {
			wp_enqueue_script( array('jquery', 'editor', 'thickbox', 'media-upload') );
			wp_enqueue_style( 'thickbox' );
			wp_register_script( 'textbox_js', plugins_url( 'assets/js/TextboxList.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'textbox_js' );
			wp_register_script( 'timer', plugins_url( 'assets/js/jquery.timer.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'timer' );
			wp_register_script( 'jquery-ui', plugins_url( 'assets/js/jquery-ui.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'jquery-ui' );
			wp_register_script( 'growing_input', plugins_url( 'assets/js/GrowingInput.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'growing_input' );
			wp_register_style( 'textbox_css', plugins_url( 'assets/css/TextboxList.css', __FILE__ ) );
			wp_enqueue_style( 'textbox_css' );
			wp_register_style( 'jquery-ui-css', plugins_url( 'assets/css/jquery-ui.css', __FILE__ ) );
			wp_enqueue_style( 'jquery-ui-css' );
			wp_register_style( 'woocommerce-customer-relationship-style-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array('textbox_css') );
			wp_enqueue_style( 'woocommerce-customer-relationship-style-admin' );
			wp_register_script( 'woocommerce-customer-relationship-script-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array('jquery', 'textbox_js', 'growing_input') );
			wp_enqueue_script( 'woocommerce-customer-relationship-script-admin' );
			wp_register_style( 'woocommerce_frontend_styles', plugins_url() . '/woocommerce/assets/css/admin.css' );
			wp_enqueue_style( 'woocommerce_frontend_styles' );
			wp_register_script( 'woocommerce_admin_crm', plugins_url() . '/woocommerce/assets/js/admin/woocommerce_admin.min.js', array('jquery', 'jquery-blockui', 'jquery-placeholder', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip') );
			wp_enqueue_script( 'woocommerce_admin_crm' );
			wp_register_script( 'woocommerce_tiptip_js', plugins_url() . '/woocommerce/assets/js/jquery-tiptip/jquery.tipTip.min.js' );
			wp_enqueue_script( 'woocommerce_tiptip_js' );
			wp_register_script( 'chosen_js', plugins_url( 'assets/js/chosen.jquery.min.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'chosen_js' );
			wp_register_script( 'ajax-chosen_js', plugins_url( 'assets/js/ajax-chosen.jquery.min.js', __FILE__ ), array('jquery', 'chosen') );
			wp_enqueue_script( 'ajax-chosen_js' );


				wp_register_script( 'mousewheel', plugins_url( 'assets/js/jquery.mousewheel.js', __FILE__ ), array('jquery') );
				wp_enqueue_script( 'mousewheel' );
				wp_register_script( 'fancybox', plugins_url( 'assets/js/jquery.fancybox.pack.js', __FILE__ ), array('jquery', 'mousewheel') );
				wp_enqueue_script( 'fancybox' );

				wp_register_style( 'fancybox_styles', plugins_url('/assets/css/fancybox/jquery_fancybox.css', __FILE__ ) );
				wp_enqueue_style( 'fancybox_styles' );
				wp_register_style( 'fancybox-buttons', plugins_url('/assets/css/fancybox/jquery.fancybox-buttons.css', __FILE__ ) );
				wp_enqueue_style( 'fancybox-buttons' );

        if( isset($_GET['page']) && $_GET['page'] == 'wc_new_customer' ){
            wp_register_script( 'postbox', admin_url() . '/js/postbox.min.js', array( 'postbox' ), '2.66', true );

            wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.min.js', array( 'jquery' ), '2.66', true );
            wp_register_script( 'accounting', WC()->plugin_url() . '/assets/js/admin/accounting.min.js', array( 'jquery' ), '0.3.2' );
            wp_register_script( 'round', WC()->plugin_url() . '/assets/js/admin/round.min.js', array( 'jquery' ), WC_VERSION );
            wp_register_script( 'woocommerce_admin_meta_boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes.js', array( 'jquery'), WC_VERSION );

              wp_enqueue_script( 'postbox' );
              wp_enqueue_script( 'jquery-blockui' );
              wp_enqueue_script( 'accounting' );
              wp_enqueue_script( 'round' );
              wp_enqueue_script( 'woocommerce_admin_meta_boxes' );
              $params = array(
              'remove_item_notice'      => __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'woocommerce' ),
              'i18n_select_items'       => __( 'Please select some items.', 'woocommerce' ),
              'remove_item_meta'        => __( 'Remove this item meta?', 'woocommerce' ),
              'remove_attribute'        => __( 'Remove this attribute?', 'woocommerce' ),
              'name_label'          => __( 'Name', 'woocommerce' ),
              'remove_label'          => __( 'Remove', 'woocommerce' ),
              'click_to_toggle'       => __( 'Click to toggle', 'woocommerce' ),
              'values_label'          => __( 'Value(s)', 'woocommerce' ),
              'text_attribute_tip'      => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
              'visible_label'         => __( 'Visible on the product page', 'woocommerce' ),
              'used_for_variations_label'   => __( 'Used for variations', 'woocommerce' ),
              'new_attribute_prompt'      => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
              'calc_totals'           => __( 'Calculate totals based on order items, discounts, and shipping?', 'woocommerce' ),
              'calc_line_taxes'         => __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'woocommerce' ),
              'copy_billing'          => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
              'load_billing'          => __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'woocommerce' ),
              'load_shipping'         => __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
              'featured_label'        => __( 'Featured', 'woocommerce' ),
              'prices_include_tax'      => esc_attr( get_option('woocommerce_prices_include_tax') ),
              'round_at_subtotal'       => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
              'no_customer_selected'      => __( 'No customer selected', 'woocommerce' ),
              'plugin_url'          => WC()->plugin_url(),
              'ajax_url'            => admin_url('admin-ajax.php'),
              'order_item_nonce'        => wp_create_nonce("order-item"),
              'add_attribute_nonce'       => wp_create_nonce("add-attribute"),
              'save_attributes_nonce'     => wp_create_nonce("save-attributes"),
              'calc_totals_nonce'       => wp_create_nonce("calc-totals"),
              'get_customer_details_nonce'  => wp_create_nonce("get-customer-details"),
              'search_products_nonce'     => wp_create_nonce("search-products"),
              'grant_access_nonce'      => wp_create_nonce("grant-access"),
              'revoke_access_nonce'     => wp_create_nonce("revoke-access"),
              'add_order_note_nonce'      => wp_create_nonce("add-order-note"),
              'delete_order_note_nonce'   => wp_create_nonce("delete-order-note"),
              'calendar_image'        => WC()->plugin_url().'/assets/images/calendar.png',
              'post_id'           => isset( $post->ID ) ? $post->ID : '',
              'base_country'          => WC()->countries->get_base_country(),
              'currency_format_num_decimals'  => absint( get_option( 'woocommerce_price_num_decimals' ) ),
              'currency_format_symbol'    => get_woocommerce_currency_symbol(),
              'currency_format_decimal_sep' => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
              'currency_format_thousand_sep'  => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
              'currency_format'       => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS
              'rounding_precision'            => WC_ROUNDING_PRECISION,
              'tax_rounding_mode'             => WC_TAX_ROUNDING_MODE,
              'product_types'         => array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
              'default_attribute_visibility'  => apply_filters( 'default_attribute_visibility', false ),
              'default_attribute_variation'   => apply_filters( 'default_attribute_variation', false ),
              'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
              'i18n_permission_revoke'    => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
            );

            wp_localize_script( 'woocommerce_admin_meta_boxes', 'woocommerce_admin_meta_boxes', $params );
      }

			add_thickbox();
		}

		/**
		 * Add action links under WordPress > Plugins
		 *
		 * @param $links
		 * @return array
		 */
		public function action_links( $links ) {

			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=woocommerce&tab=customer_relationship' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}

		/**
		 * Add the tab under WooCommerce menu.
		 *
		 * @access public
		 * @return void
		 */
		public function add_tab() {
			foreach ( $this->settings_tabs as $name => $label ) {
				$class = 'nav-tab';
				if ( $this->current_tab == $name )
					$class .= ' nav-tab-active';
				echo '<a href="' . admin_url( 'admin.php?page=woocommerce&tab=' . $name ) . '" class="' . $class . '">' . $label . '</a>';
			}
		}

		/**
		 * Action to include the settings.
		 *
		 * @access public
		 * @return void
		 */
		public function settings_tab_action() {
			global $woocommerce_settings;

			// Determine the current tab in effect.
			$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_settings_tabs_' );

			// Hook onto this from another function to keep things clean.
			do_action( 'woocommerce_customer_relationship_settings' );

			// Display settings for this tab (make sure to add the settings to the tab).
			woocommerce_admin_fields( $woocommerce_settings[$current_tab] );
		}

		/**
		 * Save settings in a single field in the database for each tab's fields (one field per tab).
		 */
		function save_settings() {
			global $woocommerce_settings;

			// Make sure our settings fields are recognised.
			$this->add_settings_fields();

			$current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_update_options_' );
			woocommerce_update_options( $woocommerce_settings[$current_tab] );
		}

		/**
		 * Include required files
		 */
		public function includes() {
			if ( is_admin() ) {
				require_once( 'admin/admin-init.php' ); // Admin section
			}
		}

		/**
		 * Handle CSV file download
		 */
		function export_csv() {

			global $orders_data, $order_countries;

			woocommerce_crm_get_orders_data();

			if ( !current_user_can( 'manage_options' ) )
				return;

			header( 'Content-Type: application/csv' );
			header( 'Content-Disposition: attachment; filename=customers_' . date( 'Y-m-d' ) . '.csv' );
			header( 'Pragma: no-cache' );

			echo "customer_name,email,phone,username,last_purchase,number_of_orders,total_value,subscribed_to_newsletter\n";
			foreach ( $orders_data as $email => $item ) {
				$user = @get_userdata( $item['user_id'] );
				echo '"' . $item['name'] . '","' . $email . '","' . $item['phone'] . '","' . ( isset( $user->user_login ) ? $user->user_login : __( 'Guest', 'wc_customer_relationship_manager' ) ) . '","'
					. woocommerce_crm_get_pretty_time( $item['last_purchase_id'], true ) . '","'
					. $item['num_orders'] . '","' . $item['value'] . '","' . $item['enrolled_plain'] . "\"\n";
			}

		}

		public function woocommerce_crm_customer_name_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select id="dropdown_customers" name="_customer_user">
          <option value=""><?php _e( 'Show all customers', 'wc_customer_relationship_manager' ) ?></option>
          <?php
          if ( !empty( $_POST['_customer_user'] ) ) {
            $user = $_POST['_customer_user'];
            echo '<option value="' . $user . '" ';
            selected( 1, 1 );
            echo '>' . $user . '</option>';
          }
          ?>
        </select>
			<?php
		}
		public function woocommerce_crm_products_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_customer_product' id='dropdown_product'>
          <option value=""><?php _e( 'Show all products', 'wc_customer_relationship_manager' ); ?></option>
          <?php

          foreach ( $order_products as $product_id => $count ) {
            $product = get_product($product_id);
            if( empty( $product ) ) {
              continue;
            }
            echo '<option value="' . $product->id . '" ';
            if ( !empty( $_POST['_customer_product'] ) && $_POST['_customer_product'] == $product->id ) {
              echo 'selected';
            }
            echo '>' . esc_html__( $product->get_title() ) . '</option>';
          }
          ?>
        </select>
			<?php
		}
		public function woocommerce_crm_country_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_customer_country' id='dropdown_country'>
          <option value=""><?php _e( 'Show all countries', 'wc_customer_relationship_manager' ); ?></option>
          <?php

          foreach ( $order_countries as $country => $count ) {
            echo '<option value="' . $country . '" ';
            if ( !empty( $_POST['_customer_country'] ) && $_POST['_customer_country'] == $country ) {
              echo 'selected';
            }
            echo '>' . esc_html__( $country ) . ' - ' . $woocommerce->countries->countries[$country] . ' (' . absint( $count ) . ')</option>';
          }
          ?>
        </select>
			<?php
		}
		public function woocommerce_crm_state_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_customer_state' id='dropdown_state'>
          <option value=""><?php _e( 'Show all states', 'wc_customer_relationship_manager' ); ?></option>
          <?php

          foreach ( $order_states as $state => $count ) {
            echo '<option value="' . $state . '" ';
            if ( !empty( $_POST['_customer_state'] ) && $_POST['_customer_state'] == $state ) {
              echo 'selected';
            }
            echo '>' . esc_html__( $state ) . ' (' . absint( $count ) . ')</option>';
          }
          ?>
        </select>
			<?php
		}
		public function woocommerce_crm_city_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_customer_city' id='dropdown_city'>
          <option value=""><?php _e( 'Show all cities', 'wc_customer_relationship_manager' ); ?></option>
          <?php

          foreach ( $order_city as $city => $count ) {
            echo '<option value="' . $city . '" ';
            if ( !empty( $_POST['_customer_city'] ) && $_POST['_customer_city'] == $city ) {
              echo 'selected';
            }
            echo '>' . esc_html__( $city ) . ' (' . absint( $count ) . ')</option>';
          }
          ?>
        </select>
			<?php
		}
		public function woocommerce_crm_last_order_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_customer_date_from' id='dropdown_date_from'>
          <option value=""><?php _e( 'All time results', 'wc_customer_relationship_manager' ); ?></option>

          <option
            value="<?php echo date( 'Y-m-d H:00:00', strtotime( '-24 hours' ) ); ?>" <?php if ( !empty( $_POST['_customer_date_from'] ) && date( 'Y-m-d H:00:00', strtotime( '-24 hours' ) ) == $_POST['_customer_date_from'] ) {
            echo "selected";
          } ?> ><?php _e( 'Last 24 hours', 'wc_customer_relationship_manager' ); ?></option>

          <option
            value="<?php echo date( 'Y-m-01 00:00:00', strtotime( 'this month' ) ); ?>" <?php if ( !empty( $_POST['_customer_date_from'] ) && date( 'Y-m-01 00:00:00', strtotime( 'this month' ) ) == $_POST['_customer_date_from'] ) {
            echo "selected";
          } ?> ><?php _e( 'This month', 'wc_customer_relationship_manager' ); ?></option>
          <option
            value="<?php echo date( 'Y-m-d 00:00:00', strtotime( '-30 days' ) ); ?>" <?php if ( !empty( $_POST['_customer_date_from'] ) && date( 'Y-m-d 00:00:00', strtotime( '-30 days' ) ) == $_POST['_customer_date_from'] ) {
            echo "selected";
          } ?> ><?php _e( 'Last 30 days', 'wc_customer_relationship_manager' ); ?></option>
          <option
            value="<?php echo date( 'Y-m-d 00:00:00', strtotime( '-6 months' ) ); ?>" <?php if ( !empty( $_POST['_customer_date_from'] ) && date( 'Y-m-d 00:00:00', strtotime( '-6 months' ) ) == $_POST['_customer_date_from'] ) {
            echo "selected";
          } ?> ><?php _e( 'Last 6 months', 'wc_customer_relationship_manager' ); ?></option>
          <option
            value="<?php echo date( 'Y-m-d 00:00:00', strtotime( '-12 months' ) ); ?>" <?php if ( !empty( $_POST['_customer_date_from'] ) && date( 'Y-m-d 00:00:00', strtotime( '-12 months' ) ) == $_POST['_customer_date_from'] ) {
            echo "selected";
          } ?>><?php _e( 'Last 12 months', 'wc_customer_relationship_manager' ); ?></option>
        </select>
			<?php
		}
		public function woocommerce_crm_user_roles_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name='_user_type' id='dropdown_user_type'>
          <option value=""><?php _e( 'Show all user roles', 'wc_customer_relationship_manager' ); ?></option>
          <?php
          global $wp_roles;
          foreach ( $wp_roles->role_names as $role => $name ) : ?>

            <option value="<?php echo strtolower($name); ?>" <?php if ( !empty( $_POST['_user_type'] ) && strtolower($name) == $_POST['_user_type'] ) {
              echo "selected";
            } ?>><?php _e( $name, 'wc_customer_relationship_manager' ); ?></option>

          <?php
          endforeach;
          ?>

                    <option value="guest_user" <?php if ( !empty( $_POST['_user_type'] ) && 'guest_user' == $_POST['_user_type'] ) {
            echo "selected";
          } ?>><?php _e( 'Guest', 'wc_customer_relationship_manager' ); ?></option>
        </select>
			<?php
		}
		public function woocommerce_crm_products_variations_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
			<select name="_products_variations[]" id="dropdown_products_and_variations" multiple="multiple" data-placeholder="<?php _e( 'Search for a product&hellip;', 'woocommerce' ); ?>" style="width: 400px">
				<?php
						$product_ids = $_POST['_products_variations'];
						if ( $product_ids ) {
							foreach ( $product_ids as $product_id ) {
								$product = get_product( $product_id );
								echo '<option value="' . esc_attr( $product_id ) . '" selected="selected">' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
							}
						}
					?>
			</select>

			<?php
		}
		public function woocommerce_crm_order_status_filter() {
      global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
      woocommerce_crm_get_orders_data();
			?>
      <select name='_order_status' id='dropdown_order_status'>
        <option value=""><?php _e( 'Show all statuses', 'woocommerce' ); ?></option>
        <?php
          $terms = get_terms('shop_order_status');

          foreach ( $terms as $term ) {
            echo '<option value="' . esc_attr( $term->slug ) . '"';

            if ( isset( $_POST['_order_status'] ) ) {
              selected( $term->slug, $_POST['_order_status'] );
            }

            echo '>' . esc_html__( $term->name, 'woocommerce' ) . ' (' . absint( $term->count ) . ')</option>';
          }
        ?>
      </select>

			<?php
		}

    public function woocommerce_crm_types_of_activity_filter() {
      global $logs_data, $activity_types;
      woocommerce_crm_get_logs_data();
      ?>
      <select name='activity_types' id='dropdown_activity_types'>
        <option value=""><?php _e( 'Show all types', 'woocommerce' ); ?></option>
        <?php
          foreach ( $activity_types as $type=>$count ) {
            echo '<option value="' . esc_attr( $type ) . '"';

            if ( isset( $_REQUEST['activity_types'] ) ) {
              selected( $type, $_REQUEST['activity_types'] );
            }
            echo '>' . esc_html__( $type, 'woocommerce' ) . ' (' . absint( $count ) . ')</option>';
          }
        ?>
      </select>
      <?php
    }
    public function woocommerce_crm_created_date_filter() {
      global $months, $wp_locale;
      $month_count = count( $months );

      if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
        return;

      $m = isset( $_GET['created_date'] ) ? (int) $_GET['created_date'] : 0;
      $m = isset( $_POST['created_date'] ) ? (int) $_POST['created_date'] : $m;
        ?>
            <select name='created_date' id="created_date">
              <option<?php selected( $m, 0 ); ?> value='0'><?php _e( 'Show all dates' ); ?></option>
        <?php
            foreach ( $months as $arc_row ) {
              if ( 0 == $arc_row->year )
                continue;

              $month = zeroise( $arc_row->month, 2 );
              $year = $arc_row->year;

              printf( "<option %s value='%s'>%s</option>\n",
                selected( $m, $year . $month, false ),
                esc_attr( $arc_row->year . $month ),
                /* translators: 1: month name, 2: 4-digit year */
                sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
              );
            }
        ?>
      </select>
        <?php
    }
    public function woocommerce_crm_log_username_filter() {
      global $logs_data, $log_users;
      woocommerce_crm_get_logs_data();
      ?>
      <select name='log_users' id='dropdown_log_users'>
        <option value=""><?php _e( 'Show all authors', 'woocommerce' ); ?></option>
        <?php
          foreach ( $log_users as $userid=>$count ) {
            $userdata = get_userdata( $userid );
            echo '<option value="' . absint( $userid ) . '"';

            if ( isset( $_REQUEST['log_users'] ) ) {
              selected( $userid, $_REQUEST['log_users'] );
            }
            echo '>' . $userdata->first_name.' '.$userdata->last_name  . ' (' . absint( $count ) . ')</option>';
          }
        ?>
      </select>
      <?php
    }

    /**
     * Filter for Logs page
     *
     */
    public function woocommerce_crm_restrict_list_logs() {
        $woocommerce_crm_filters_log = array(
            'types_of_activity',
            'created_date',
            'log_username'
          );
          ?>
          <div class="alignleft actions">
          <?php
            foreach ($woocommerce_crm_filters_log as $key => $value) {
                add_action( 'woocommerce_crm_add_filters_log', array($this, 'woocommerce_crm_'.$value.'_filter') );
            }
            do_action( 'woocommerce_crm_add_filters_log');
          ?>
          <input type="submit" id="post-query-submit" class="button action" value="Filter"/>
        </div>
          <?php
          $js = "
                jQuery('select#dropdown_activity_types').css('width', '150px').chosen();

                jQuery('select#dropdown_log_users').css('width', '150px').chosen();

                jQuery('select#created_date').css('width', '150px').chosen();
            ";

      if ( class_exists( 'WC_Inline_Javascript_Helper' ) ) {
        $woocommerce->get_helper( 'inline-javascript' )->add_inline_js( $js );
      } elseif( function_exists('wc_enqueue_js') ){
        wc_enqueue_js($js);
      }  else {
        $woocommerce->add_inline_js( $js );
      }
    }
		/**
		 * Provides the select boxes to filter Customers, Country and Time Period.
		 *
		 */
		public function woocommerce_crm_restrict_list_customers() {
			global $wp_query, $woocommerce, $orders_data, $order_countries, $order_products, $order_states, $order_city;
			$woocommerce_crm_filters = get_option( 'woocommerce_crm_filters' );
			if( !empty($woocommerce_crm_filters) ) :
			?>
			<div class="alignleft actions">
				<?php
					foreach ($woocommerce_crm_filters as $key => $value) {
							add_action( 'woocommerce_crm_add_filters', array($this, 'woocommerce_crm_'.$value.'_filter') );
					}
					do_action( 'woocommerce_crm_add_filters');
				?>
			<input type="submit" id="post-query-submit" class="button action" value="Filter"/>

				<?php
				$_customer_user = isset( $_POST['_customer_user'] ) ? $_POST['_customer_user'] : '';
				$_customer_country = isset( $_POST['_customer_country'] ) ? $_POST['_customer_country'] : '';
				$_customer_date_from = isset( $_POST['_customer_date_from'] ) ? $_POST['_customer_date_from'] : '';
				?>

			<!--	<a class="button action"
				   href="<?php echo admin_url( "admin-post.php?action=export_csv&_customer_user=$_customer_user&_customer_country=$_customer_country&_customer_date_from=$_customer_date_from" ); ?>"><?php _e( 'Export Contacts', 'wc_customer_relationship_manager' ); ?></a> -->

			</div>

			<?php
			endif;

			$js = "
              jQuery('#doaction').click(function(){
                var val = $('select[name=\"action\"]').val();
                if( val == 'export_csv'){
                 location.href='admin-post.php?action=export_csv&_customer_user=$_customer_user&_customer_country=$_customer_country&_customer_date_from=$_customer_date_from';
                 return false;
               }
              });

                jQuery('select#dropdown_product').css('width', '150px').chosen();

                jQuery('select#dropdown_country').css('width', '150px').chosen();

                jQuery('select#dropdown_state').css('width', '150px').chosen();

                jQuery('select#dropdown_city').css('width', '150px').chosen();

                jQuery('select#dropdown_date_from').css('width', '150px').chosen();

                jQuery('select#dropdown_user_type').css('width', '150px').chosen();

                jQuery('select#dropdown_order_status').css('width', '150px').chosen();

                jQuery('select#dropdown_customers').css('width', '200px').ajaxChosen({
                    method: 		'GET',
                    url: 			'" . admin_url( 'admin-ajax.php' ) . "',
                    dataType: 		'json',
                    afterTypeDelay: 100,
                    minTermLength: 	1,
                    data:		{
                        action: 	'woocommerce_crm_json_search_customers',
                        security: 	'" . wp_create_nonce( "search-customers" ) . "',
                        default:	'" . __( 'Show all customers', 'wc_customer_relationship_manager' ) . "',
                    }
                }, function (data) {

                    var terms = {};

                    $.each(data, function (i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });



								jQuery('select#dropdown_products_and_variations').css('width', '400px').ajaxChosen({
                    method: 		'GET',
                    url: 			'" . admin_url( 'admin-ajax.php' ) . "',
                    dataType: 		'json',
                    afterTypeDelay: 100,
                    minTermLength: 	1,
                    data:		{
                        action: 	'woocommerce_crm_json_search_variations',
                        security: 	'" . wp_create_nonce( "search-products" ) . "',
                    }
                }, function (data) {

                    var terms = {};

                    $.each(data, function (i, val) {
                        terms[i] = val;
                    });

                    return terms;
                });
            ";

			if ( class_exists( 'WC_Inline_Javascript_Helper' ) ) {
				$woocommerce->get_helper( 'inline-javascript' )->add_inline_js( $js );
			} elseif( function_exists('wc_enqueue_js') ){
				wc_enqueue_js($js);
			}  else {
				$woocommerce->add_inline_js( $js );
			}
		}

		/**
     * AJAX initiated call to obtain list of filtered customers
     */
    public function json_search_customers() {

      global $orders_data;

      check_ajax_referer( 'search-customers', 'security' );

      header( 'Content-Type: application/json; charset=utf-8' );

      $term = urldecode( stripslashes( strip_tags( $_GET['term'] ) ) );

      if ( empty( $term ) )
        die();

      $found_customers = array();

      woocommerce_crm_get_orders_data();

      if ( $orders_data ) {
        foreach ( $orders_data as $email => $item ) {
          if ( strpos( strtoupper( $item['name'] ), strtoupper( $term ) ) !== false || strpos( $item['user_id'], $term ) !== false || strpos( strtoupper( sanitize_email( $email ) ), strtoupper( $term ) ) !== false ) {
            $found_customers[$email] = $item['name'] . ' (' . ( !empty( $item["user_id"] ) ? '#' . $item["user_id"] : __( "Guest", 'wc_customer_relationship_manager' ) ) . ' &ndash; ' . sanitize_email( $email ) . ')';
          }
        }
      }

      echo json_encode( $found_customers );
      die();
    }


    public function modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['twitter'] = 'Twitter Username';
        $profile_fields['skype'] = 'Skype';

        return $profile_fields;
      }

    public function save_user_field_status($user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;
        update_usermeta( $user_id, 'customer_status', $_POST['customer_status'] );
      }

    public function add_user_field_status($user ) {
      ?>
      <table class="form-table">
          <tr>
              <th><label for="dropdown"><?php _e( 'Customer status', 'wc_customer_relationship_manager' ) ?></label></th>
              <td>
                  <select id="customer_status" name="customer_status" class="chosen_select">
                      <?php 
                      $selected = get_the_author_meta( 'customer_status', $user->ID );
                      if ( empty($selected) ) $selected ='Lead';
                      $statuses = array(
                        'Customer' => 'Customer',
                        'Lead' => 'Lead',
                        'Follow-Up' => 'Follow-Up',
                        'Prospect' => 'Prospect',
                        'Favourite' => 'Favourite',
                        'Blocked' => 'Blocked',
                        'Flagged' => 'Flagged'
                        );
                      foreach ( $statuses as $key => $status ) {
                        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $selected, false ) . '>' . esc_html__( $status, 'wc_customer_relationship_manager' ) . '</option>';
                      }
                  ?>
                  </select>
                  <span class="description"></span>
              </td>
          </tr>
      </table>
      <?php
      }

		/**
		 * AJAX initiated call to obtain list of filtered products and variations
		 */
		public function json_search_variations() {

			WC_AJAX::json_search_products( '', array('product', 'product_variation') );
		}
		/**
	 * Output headers for JSON requests
	 */
		public function json_headers() {
			header( 'Content-Type: application/json; charset=utf-8' );
		}

		/**
		 * Overrides the WooCommerce search in orders capability if we search by customer.
		 *
		 * @param $fields
		 * @return array
		 */
		public function woocommerce_crm_search_by_email( $fields ) {
			if ( isset( $_GET["search_by_email_only"] ) ) {
				return array('_billing_email');
			}
			return $fields;
		}

		/**
		 * @param $views
		 * @return array
		 */
		public function views_shop_order( $views ) {
			if ( isset( $_GET["search_by_email_only"] ) ) {
				return array();
			}
			return $views;
		}

		/**
		 * get_tab_in_view()
		 *
		 * Get the tab current in view/processing.
		 */
		function get_tab_in_view( $current_filter, $filter_base ) {
			return str_replace( $filter_base, '', $current_filter );
		}


		/**
		 * add_settings_fields()
		 *
		 * Add settings fields for each tab.
		 */
		function add_settings_fields() {
			global $woocommerce_settings;

			// Load the prepared form fields.
			$this->init_form_fields();

			if ( is_array( $this->fields ) )
				foreach ( $this->fields as $k => $v )
					$woocommerce_settings[$k] = $v;
		}

   /**
   * Add customer note via ajax
   */
    function add_customer_note_ajax() {

      $user_id  = (int) $_POST['user_id'];
      $note   = wp_kses_post( trim( stripslashes( $_POST['note'] ) ) );

      if ( $user_id > 0 ) {
        require_once( 'admin/classes/wc_crm_customer_details.php' );
        $comment_id = WC_Crm_Customer_Details::add_order_note( $note, $user_id);

        echo '<li rel="' . esc_attr( $comment_id ) . '" class="note"><div class="note_content">';
        echo wpautop( wptexturize( $note ) );
        echo '</div><p class="meta"><a href="#" class="delete_customer_note">'.__( 'Delete note', 'woocommerce' ).'</a></p>';
        echo '</li>';
      }

      // Quit out
      die();
    }
    /**
   * Delete customer note via ajax
   */
    function delete_customer_note_ajax() {
      $note_id  = (int) $_POST['note_id'];

      if ($note_id>0) :
        wp_delete_comment( $note_id );
      endif;

      // Quit out
      die();
    }

		/**
		 * init_form_fields()
		 *
		 * Prepare form fields to be used in the various tabs.
		 */
		function init_form_fields() {
			global $woocommerce;

			$api_key = get_option( 'woocommerce_crm_mailchimp_api_key' ) ? get_option( 'woocommerce_crm_mailchimp_api_key' ) : get_option( 'woocommerce_mailchimp_api_key', '' );

			if ( $api_key ) {
				$mailchimp_lists = woocommerce_crm_get_mailchimp_lists( $api_key );
				$mailchimp_list = get_option( 'woocommerce_crm_mailchimp_list' ) ? get_option( 'woocommerce_crm_mailchimp_list' ) : get_option( 'woocommerce_mailchimp_list', '' );
			} else {
				$mailchimp_lists = array();
				$mailchimp_list = '';
			}

			// Define settings
			$this->fields['customer_relationship'] = apply_filters( 'woocommerce_customer_relationship_settings_fields', array(

				array('name' => __( 'MailChimp Integration', 'wc_customer_relationship_manager' ), 'type' => 'title', 'desc' => '', 'id' => 'customer_relationship_mailchimp'),

				array(
					'name' => __( 'Integrate with MailChimp', 'wc_customer_relationship_manager' ),
					'desc' => __( 'Specify whether to integrate Customer Relationship Manager with MailChimp to see which customers signed to the newsletter.', 'wc_customer_relationship_manager' ),
					'id' => 'woocommerce_crm_mailchimp',
					'css' => '',
					'std' => 'yes',
					'type' => 'checkbox',
					'default' => 'no'
				),

				array(
					'name' => __( 'MailChimp API Key', 'wc_customer_relationship_manager' ),
					'desc' => __( 'You can obtain your API key by <a href="https://us2.admin.mailchimp.com/account/api/">logging in to your MailChimp account</a>.', 'wc_customer_relationship_manager' ),
					'tip' => '',
					'id' => 'woocommerce_crm_mailchimp_api_key',
					'css' => '',
					'std' => '',
					'type' => 'text',
					'default' => $api_key
				),

				array(
					'name' => __( 'MailChimp List', 'wc_customer_relationship_manager' ),
					'desc' => __( 'Choose a list customers can subscribe to (you must save your API key first).', 'wc_customer_relationship_manager' ),
					'tip' => '',
					'id' => 'woocommerce_crm_mailchimp_list',
					'css' => '',
					'std' => '',
					'type' => 'select',
					'options' => $mailchimp_lists,
					'default' => $mailchimp_list
				),

				array('type' => 'sectionend', 'id' => 'customer_relationship_mailchimp'),


				array('name' => '', 'type' => 'title', 'desc' => '', 'id' => 'customer_relationship_filters'),
				array(
					'name' => __( 'Filters', 'wc_customer_relationship_manager' ),
					'desc' => 'Choose which filters you would like to display on the Customers page.',
					'id' => 'woocommerce_crm_filters',
					'css' => '',
					'std' => '',
					'type' => 'multiselect',
					'options' => array(
							'user_roles' => __( 'User Roles', 'wc_customer_relationship_manager' ),
							'last_order' => __( 'Last Order', 'wc_customer_relationship_manager' ),
							'state' => __( 'State', 'wc_customer_relationship_manager' ),
							'city' => __( 'City', 'wc_customer_relationship_manager' ),
							'country' => __( 'Country', 'wc_customer_relationship_manager' ),
							'customer_name' => __( 'Customer Name', 'wc_customer_relationship_manager' ),
							'products' => __( 'Products', 'wc_customer_relationship_manager' ),
							'products_variations' => __( 'Products Variations', 'wc_customer_relationship_manager' ),
							'order_status' => __( 'Order Status', 'wc_customer_relationship_manager' ),
						)

				),
				array('type' => 'sectionend', 'id' => 'customer_relationship_filters'),

			) ); // End settings

			$js = "
                jQuery('#woocommerce_crm_mailchimp').change(function(){

                jQuery('#woocommerce_crm_mailchimp_api_key, #woocommerce_crm_mailchimp_list').closest('tr').hide();

                if ( jQuery(this).attr('checked') ) {
                    jQuery('#woocommerce_crm_mailchimp_api_key, #woocommerce_crm_mailchimp_list').closest('tr').show();
                }

            }).change();
						jQuery('select#woocommerce_crm_filters').css('width', '350px').chosen();
            ";

			// the following lines make the plugin work with both WooCommerce 2.0 and 2.1
			if ( class_exists( 'WC_Inline_Javascript_Helper' ) ) {
				$woocommerce->get_helper( 'inline-javascript' )->add_inline_js( $js );
			} elseif( function_exists('wc_enqueue_js') ){
				wc_enqueue_js($js);
			}  else {
				$woocommerce->add_inline_js( $js );
			}

		}

	}

	$wc_customer_relationship_manager = new WooCommerce_Customer_Relationship_Manager();

}