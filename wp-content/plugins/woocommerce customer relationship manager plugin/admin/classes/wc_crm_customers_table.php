<?php
/**
 * Table with list of customers.
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
			'singular' => __( 'customer', 'wc_customer_relationship_manager' ), //singular name of the listed records
			'plural' => __( 'customers', 'wc_customer_relationship_manager' ), //plural name of the listed records
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
			echo '.wp-list-table .column-customer_status { width: 45px;}';
			echo '.wp-list-table .column-customer_name { width: 14%;}';
			echo '.wp-list-table .column-email { width: 16%;}';
			echo '.wp-list-table .column-phone { width: 11%;}';
			echo '.wp-list-table .column-user { width: 10%;}';			echo '.wp-list-table .column-last_purchase { width: 11%;}';
			echo '.wp-list-table .column-num_orders { width: 46px;}';
			echo '.wp-list-table .column-order_value { width: 108px;}';
			echo '.wp-list-table .column-enrolled { width: 47px;}';
			echo '.wp-list-table .column-customer_notes { width: 46px; text-align: center;}';
			echo '.wp-list-table .column-crm_actions { width: 120px;}';
		} else {
			echo '.wp-list-table .column-id { width: 2.2em;}';
			echo '.wp-list-table .column-customer_status { width: 45px;}';
			echo '.wp-list-table .column-customer_name { width: 14%;}';
			echo '.wp-list-table .column-email { width: 16%;}';
			echo '.wp-list-table .column-phone { width: 11%;}';
			echo '.wp-list-table .column-user { width: 10%;}';
			echo '.wp-list-table .column-last_purchase { width: 11%;}';
			echo '.wp-list-table .column-num_orders { width: 46px;}';
			echo '.wp-list-table .column-order_value { width: 108px;}';
			echo '.wp-list-table .column-customer_notes { width: 46px; text-align: center;}';
			echo '.wp-list-table .column-crm_actions { width: 120px;}';
		}
		echo '</style>';
	}

	function no_items() {
		_e( 'No customers data found. Try to adjust the filter.', 'wc_customer_relationship_manager' );
	}

	function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'customer_status':
			case 'customer_name':
			case 'email':
			case 'phone':
			case 'user':
			case 'last_purchase':
			case 'num_orders':
			case 'order_value':
			case 'customer_notes':
			case 'enrolled':
			case 'crm_actions':
				return $item[$column_name];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function get_sortable_columns() {
		$sortable_columns = array(
			'last_purchase' => array('last_purchase', false),
			'num_orders' => array('num_orders', false),
			'order_value' => array('order_value', false),
		);
		if ( woocommerce_crm_mailchimp_enabled() ) {
			$sortable_columns['enrolled'] = array('enrolled', false);
		};
		return $sortable_columns;
	}

	function get_columns() {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'customer_status' => '<i class="status_head tips" data-tip="' . esc_attr__( 'Customer Status', 'wc_customer_relationship_manager' ) . '"></i>',
			'customer_name' => __( 'Customer Name', 'wc_customer_relationship_manager' ),
			'email' => __( 'Email', 'wc_customer_relationship_manager' ),
			'phone' => __( 'Phone', 'wc_customer_relationship_manager' ),
			'user' => __( 'Username', 'wc_customer_relationship_manager' ),
			'last_purchase' => __( 'Last Order', 'wc_customer_relationship_manager' ),
			'num_orders' => '<i class="ico_orders tips" data-tip="' . esc_attr__( 'Number of Orders', 'wc_customer_relationship_manager' ) . '"></i>',
			'customer_notes' => '<i class="ico_notes tips" data-tip="' . esc_attr__( 'Customer Notes', 'wc_customer_relationship_manager' ) . '"></i>',
			'order_value' => __( 'Total Value', 'wc_customer_relationship_manager' ),
		);
		if ( woocommerce_crm_mailchimp_enabled() ) {
			$columns['enrolled'] = '<i class="ico_news tips" data-tip="' . esc_attr__( 'Newsletter Subscription', 'wc_customer_relationship_manager' ) . '"></i>';
		};
		$columns['crm_actions'] = __( 'Actions', 'wc_customer_relationship_manager' );
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

	function column_customer_name( $item ) {
		return sprintf( '<strong>%1$s</strong>', $item['customer_name'] );
	}

	function get_bulk_actions() {
		$actions = array(
			'email' => __( 'Send Email', 'wc_customer_relationship_manager' ),
			'export_csv' => __( 'Export Contacts', 'wc_customer_relationship_manager' ),
			'customer' => __( 'Mark as customer', 'wc_customer_relationship_manager' ),
			'Lead' => __( 'Mark as lead', 'wc_customer_relationship_manager' ),
			'Follow-Up' => __( 'Mark as follow-Up', 'wc_customer_relationship_manager' ),
			'Prospect' => __( 'Mark as prospect', 'wc_customer_relationship_manager' ),
			'Favourite' => __( 'Mark as favourite', 'wc_customer_relationship_manager' ),
			'Blocked' => __( 'Mark as blocked', 'wc_customer_relationship_manager' ),
			'Flagged' => __( 'Mark as flagged', 'wc_customer_relationship_manager' ),
		);
		return $actions;
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="order_id[]" value="%s" />', $item['ID']
		);
	}

	function column_crm_actions( $item ) {
		global $woocommerce;

		$actions = array(
			'orders' => array(
				'classes' => 'view',
				'url' => sprintf( 'edit.php?s=%s&post_status=%s&post_type=%s&shop_order_status&_customer_user&paged=1&mode=list&search_by_email_only', urlencode( $item['email_plain'] ), 'all', 'shop_order' ),
				'action' => 'view',
				'name' => __( 'View Orders', 'wc_customer_relationship_manager' ),
				'target' => ''

			),
			'email' => array(
				'classes' => 'email',
				'url' => sprintf( '?page=%s&action=%s&order_id=%s', $_REQUEST['page'], 'email', $item['ID'] ),
				'name' => __( 'Send Email', 'wc_customer_relationship_manager' ),
				'image_url' => plugins_url( 'assets/img/email.png', dirname( dirname( __FILE__ ) ) ),
				'target' => ''
			),
			'phone' => array(
				'classes' => 'phone',
				'url' => sprintf( '?page=%s&action=%s&order_id=%s', $_REQUEST['page'], 'phone_call', $item['ID'] ),
				'name' => __( 'Call Customer', 'wc_customer_relationship_manager' ),
				'image_url' => plugins_url( 'assets/img/call.png', dirname( dirname( __FILE__ ) ) ),
				'target' => ''
			),
			'activity' => array(
				'classes' => 'activity',
				'url' => sprintf( '?page=%s&order_id=%s', 'wc_crm_logs', $item['ID']  ),
				'name' => __( 'Contact Activity', 'wc_customer_relationship_manager' ),
				'image_url' => plugins_url( 'assets/img/call.png', dirname( dirname( __FILE__ ) ) ),
				'target' => ''
			)
		);

		echo '<p>';
		foreach ( $actions as $action ) {
			printf( '<a class="button tips %s" href="%s" data-tip="%s" %s >%s</a>', esc_attr($action['classes']), esc_url( $action['url'] ), esc_attr( $action['name'] ), esc_attr( $action['target'] ), esc_attr( $action['name'] ) );
		}
		echo '</p>';

	}

	function column_order_value( $item ) {
		return woocommerce_price( $item['order_value'] );
	}

	function prepare_items() {

		$this->data = array();
		global $orders_data, $order_countries;
		woocommerce_crm_get_orders_data();
		require_once( 'wc_crm_customer_details.php' );
		foreach ( $orders_data as $email => $order ) {
			$item = array();
			$item['ID'] = $order['id'];
			if ( $order['user_id'] ) {
				$item['customer_name'] = "<a href='admin.php?page=wc_new_customer&userid=" . $order['user_id'] . "'>" . $order['name'] . "<a>";
			} else {
				$item['customer_name'] = $order['name'];
			}
			$user = get_user_by_email( $email );
			$user_id = $user->ID;
			$customer_status = get_the_author_meta( 'customer_status', $user_id );
			//print_R($customer_status);
			$item['customer_status']='<div style="position: relative;"><span class="'.$customer_status.' tips" data-tip="' . esc_attr__( $customer_status, 'wc_customer_relationship_manager' ) . '"></span></div>';
			if(!empty($user_id))
				$item['customer_notes']='<a href="admin.php?page=wc_new_customer&screen=customer_notes&user_id='.$user_id.'" class="fancybox note-on tips" data-tip="'.WC_Crm_Customer_Details::get_last_customer_note($user_id).'"></a>';
			else
				$item['customer_notes']='<span class="note-off">-</span>';


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

		$user = get_current_user_id();
		$screen = get_current_screen();
		$option = $screen->get_option('per_page', 'option');

		$per_page = get_user_meta($user, $option, true);

		if ( empty ( $per_page) || $per_page < 1 ) {
		    $per_page = $screen->get_option( 'per_page', 'default' );
		}
		//$per_page = 20;
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

	public function change_customer_status()
	{

		if( isset($_POST['order_id']) && !empty($_POST['order_id'])){
			foreach ($_POST['order_id'] as $key => $value) {
				$order  = new WC_Order( $value );
				$email  = $order->billing_email;
				$user = get_user_by_email( $email );
				$user_id = $user->ID;
				switch ($_POST['action']) {
			    case 'Customer':
			        update_usermeta( $user_id, 'customer_status', 'Customer' );
			        break;
			    case 'Lead':
			        update_usermeta( $user_id, 'customer_status', 'Lead' );
			        break;
			    case 'Follow-Up':
			        update_usermeta( $user_id, 'customer_status', 'Follow-Up' );
			        break;
	        case 'Prospect':
			        update_usermeta( $user_id, 'customer_status', 'Prospect' );
			        break;
	        case 'Favourite':
			        update_usermeta( $user_id, 'customer_status', 'Favourite' );
			        break;
	        case 'Blocked':
			        update_usermeta( $user_id, 'customer_status', 'Blocked' );
			        break;
	        case 'Flagged':
			        update_usermeta( $user_id, 'customer_status', 'Flagged' );
			        break;
					}
			}


		}

	}

}