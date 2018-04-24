<?php
/*
	Plugin Name: WooCommerce Shipment Tracking
	Plugin URI: http://www.woothemes.com/products/shipment-tracking/
	Description: Add tracking numbers to orders allowing customers to track their orders via a link. Supports many shipping providers, as well as custom ones if neccessary via a regular link.
	Version: 1.4.5
	Author: WooThemes
	Author URI: http://www.woothemes.com/
	Text Domain: wc_shipment_tracking
	Domain Path: /languages

	Copyright: © 2009-2012 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '1968e199038a8a001c9f9966fd06bf88', '18693' );

if ( is_woocommerce_active() ) {

	/**
	 * WC_Shipment_Tracking class
	 */
	if ( ! class_exists( 'WC_Shipment_Tracking' ) ) {

		class WC_Shipment_Tracking {

			/**
			 * Constructor
			 */
			public function __construct() {
				$this->plugin_dir = untrailingslashit( plugin_dir_path( __FILE__ ) );
				$this->plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );

				// include required files
				$this->includes();

				add_action( 'admin_print_styles', array( $this->actions, 'admin_styles' ) );
				add_action( 'add_meta_boxes', array( $this->actions, 'add_meta_box' ) );
				add_action( 'woocommerce_process_shop_order_meta', array( $this->actions, 'save_meta_box' ), 0, 2 );
				add_action( 'plugins_loaded', array( $this->actions, 'load_plugin_textdomain' ) );

				// View Order Page
				add_action( 'woocommerce_view_order', array( $this->actions, 'display_tracking_info' ) );
				add_action( 'woocommerce_email_before_order_table', array( $this->actions, 'email_display' ), 0, 3 );

				// Order page metabox actions
				add_action( 'wp_ajax_wc_shipment_tracking_delete_item', array( $this->actions, 'meta_box_delete_tracking' ) );
				add_action( 'wp_ajax_wc_shipment_tracking_save_form', array( $this->actions, 'save_meta_box_ajax' ) );

				// Customer / Order CSV Export column headers/data
				add_filter( 'wc_customer_order_csv_export_order_headers', array( $this->actions, 'add_tracking_info_to_csv_export_column_headers' ) );
				add_filter( 'wc_customer_order_csv_export_order_row', array( $this->actions, 'add_tracking_info_to_csv_export_column_data' ), 10, 3 );

				$subs_version = class_exists( 'WC_Subscriptions' ) && ! empty( WC_Subscriptions::$version ) ? WC_Subscriptions::$version : null;

				// Prevent data being copied to subscriptions
				if ( null !== $subs_version && version_compare( $subs_version, '2.0.0', '>=' ) ) {
					add_filter( 'wcs_renewal_order_meta_query', array( $this->actions, 'woocommerce_subscriptions_renewal_order_meta_query' ), 10, 4 );
				} else {
					add_filter( 'woocommerce_subscriptions_renewal_order_meta_query', array( $this->actions, 'woocommerce_subscriptions_renewal_order_meta_query' ), 10, 4 );
				}

			}


			/**
			 * Include required files
			 *
			 * @since 1.4.0
			 */
			private function includes() {
				require( 'includes/class-wc-shipment-tracking.php' );
				$this->actions = WC_Shipment_Tracking_Actions::get_instance();
			}

			/**
			* Gets the absolute plugin path without a trailing slash, e.g.
			* /path/to/wp-content/plugins/plugin-directory
			*
			* @return string plugin path
			*/
			public function get_plugin_path() {
				if ( isset( $this->plugin_path ) ) {
					return $this->plugin_path;
				}

				return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}
		}

	}

	/**
	 * Register this class globally
	 */
	$GLOBALS['WC_Shipment_Tracking'] = new WC_Shipment_Tracking();


	// Helper functions

	/*
	 * Function wc_st_add_tracking_number
	 * Adds a tracking number to an order
	 *
	 * $order_id : The order id of the order you want to attach this tracking number to
	 * $tracking_number : The tracking number
	 * $provider : The tracking provider. If you use one from WC_Shipment_Tracking_Actions::get_providers() the tracking url will be taken case of
	 * $date_shipped : The timestamp of the shipped date
	 * $custom_url (optional) If you are not using a provder from WC_Shipment_Tracking_Actions::get_providers() you can add a url for tracking here
	 *
	 */

	function wc_st_add_tracking_number( $order_id, $tracking_number, $provider, $date_shipped = null, $custom_url = false ) {

		if ( ! $date_shipped ) {
			$date_shipped = current_time( 'timestamp' );
		}

		$st = WC_Shipment_Tracking_Actions::get_instance();

		$provider_list = $st->get_providers();

		$custom = true;

		foreach ( $provider_list as $country ) {
			foreach ( $country as $provider_code => $url ) {
				if ( sanitize_title( $provider ) === sanitize_title( $provider_code ) ) {
					$provider = sanitize_title( $provider_code );
					$custom = false;
					break;
				}
			}

			if ( ! $custom ) {
				break;
			}
		}

		if ( $custom ) {
			$args = array(
				'tracking_provider' => '',
				'custom_tracking_provider' => $provider,
				'custom_tracking_link' => $custom_url,
				'tracking_number' => $tracking_number,
				'date_shipped' => date( 'Y-m-d', $date_shipped )
			);
		}
		else {
			$args = array(
				'tracking_provider' => $provider,
				'custom_tracking_provider' => '',
				'custom_tracking_link' => '',
				'tracking_number' => $tracking_number,
				'date_shipped' => date( 'Y-m-d', $date_shipped )
			);
		}

		$st->add_tracking_item( $order_id, $args );

	}

	/*
	 * Function wc_st_delete_tracking_number
	 * Deletes tracking information based on tracking_number
	 * relating to an order.
	 *
	 * $order_id: the id of the order
	 * $tracking_number: the tracking number to be deleted
	 * $provider (optional): You can filter the delete by specifying a tracking provider
	 *
	 *
	 */
	function wc_st_delete_tracking_number( $order_id, $tracking_number, $provider = false ) {

		$st = WC_Shipment_Tracking_Actions::get_instance();

		$tracking_items = $st->get_tracking_items( $order_id );

		if ( count( $tracking_items ) > 0 ) {

			foreach ( $tracking_items as $item ) {
				if ( ! $provider ) {
					if ( $item[ 'tracking_number' ] == $tracking_number ) {
						$st->delete_tracking_item( $order_id,  $item[ 'tracking_id' ] );
						return true;
					}
				}
				else {
					if ( $item[ 'tracking_number' ] === $tracking_number && ( $item[ 'tracking_provider' ] === sanitize_title( $provider ) || $item[ 'custom_tracking_provider' ] === sanitize_title( $provider ) ) ) {
						$st->delete_tracking_item( $order_id,  $item[ 'tracking_id' ] );
						return true;
					}
				}
			}
		}
		return false;
	}
}
