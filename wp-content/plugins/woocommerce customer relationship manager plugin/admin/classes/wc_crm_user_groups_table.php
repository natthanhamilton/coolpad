<?php
/**
 * Table with list of user groups.
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once( plugin_dir_path( __FILE__ ) . '../../functions.php' );

class WC_Crm_Customers_Table extends WP_List_Table {

	var $data = array();

	function __construct() {
		global $status, $page;

		parent::__construct( array(
			'singular' => __( 'group', 'wc_customer_relationship_manager' ), //singular name of the listed records
			'plural' => __( 'groups', 'wc_customer_relationship_manager' ), //plural name of the listed records
			'ajax' => false //does this table support ajax?

		) );

		add_action( 'admin_head', array(&$this, 'admin_header') );

	}

	function admin_header() {
		global $wc_customer_relationship_manager;
		$page = ( isset( $_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if ( $wc_customer_relationship_manager->id != $page )
			return;
		echo '<style type="text/css">';
		if ( woocommerce_crm_mailchimp_enabled() ) {
			echo '.wp-list-table .column-id { width: 2.2em;}';
			echo '.wp-list-table .column-customer_name { width: 12%;}';
			echo '.wp-list-table .column-email { width: 15%;}';
			echo '.wp-list-table .column-phone { width: 11%;}';
			echo '.wp-list-table .column-user { width: 10%;}';
			echo '.wp-list-table .column-first_purchase { width: 11%;}';
			echo '.wp-list-table .column-last_purchase { width: 11%;}';
			echo '.wp-list-table .column-num_orders { width: 46px; left: 10%;}';
			echo '.wp-list-table .column-order_value { width: 99px;}';
			echo '.wp-list-table .column-enrolled { width: 47px;}';
			echo '.wp-list-table .column-crm_actions { width: 90px;}';
		} else {
			echo '.wp-list-table .column-id { width: 2.2em;}';
			echo '.wp-list-table .column-customer_name { width: 12%;}';
			echo '.wp-list-table .column-email { width: 15%;}';
			echo '.wp-list-table .column-phone { width: 11%;}';
			echo '.wp-list-table .column-user { width: 10%;}';
			echo '.wp-list-table .column-first_purchase { width: 11%;}';
			echo '.wp-list-table .column-last_purchase { width: 11%;}';
			echo '.wp-list-table .column-num_orders { width: 46px; left: 10%;}';
			echo '.wp-list-table .column-order_value { width: 99px;}';
			echo '.wp-list-table .column-crm_actions { width: 90px;}';
		}
		echo '</style>';
	}

	function no_items() {
		_e( 'No groups data found. Try to adjust the filter.', 'wc_customer_relationship_manager' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'group_name':
			case 'group_description':
				return $item[$column_name];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'group_name' => array('group_name', false),
		);
		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'group_name' => __( 'Group Name', 'wc_customer_relationship_manager' ),
			'description' => __( 'Description', 'wc_customer_relationship_manager' ),
		);
		return $columns;
	}

	function usort_reorder( $a, $b ) {
		// If no sort, default to last purchase
		$orderby = ( !empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'last_purchase';
		// If no order, default to desc
		$order = ( !empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';
		// Determine sort order
		if ( $orderby == 'order_value' ) {
			$result = $a[$orderby] - $b[$orderby];
		} else {
			$result = strcmp( $a[$orderby], $b[$orderby] );
		}
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

	function prepare_items() {

		$this->data = array();
		global $orders_data, $order_countries;
		woocommerce_crm_get_orders_data();
		foreach ( $orders_data as $email => $order ) {
			$item = array();
			$item['ID'] = $order['id'];
			if ( $order['user_id'] ) {
				$item['customer_name'] = "<a href='user-edit.php?user_id=" . $order['user_id'] . "'>" . $order['name'] . "<a>";
			} else {
				$item['customer_name'] = $order['name'];
			}
			$item['first_purchase'] = woocommerce_crm_get_pretty_time( $order['first_purchase_id'] );
			$item['last_purchase'] = woocommerce_crm_get_pretty_time( $order['last_purchase_id'] );
			$item['email'] = "<a href='mailto:" . $email . "'>" . $email . "<a>";
			$item['email_plain'] = $email;
			$item['phone'] = $order['phone'];
			$login = __( 'Guest', 'wc_customer_relationship_manager' );
			if ( isset( $order['user_id'] ) && $order['user_id'] > 0 ) {
				$user = get_userdata( $order['user_id'] );
				$login = '<a href="user-edit.php?user_id=' . $user->ID . '">' . $user->user_login . '</a>';
			}
			$item['user'] = $login;
			$item['num_orders'] = $order['num_orders'];
			$item['order_value'] = $order['value'];
			if ( woocommerce_crm_mailchimp_enabled() ) {
				$item['enrolled'] = $order['enrolled'];
			}
			array_push( $this->data, $item );
		}

		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		usort( $this->data, array(&$this, 'usort_reorder') );

		$per_page = 20;
		$current_page = $this->get_pagenum();
		$total_items = count( $this->data );


		$this->found_data = array_slice( $this->data, ( ( $current_page - 1 ) * $per_page ), $per_page );


		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page' => $per_page //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;
	}

	function extra_tablenav( $which ) {
		if ( $which == 'top' ) {
			do_action( 'wc_crm_restrict_list_customers' );
		}
	}

}