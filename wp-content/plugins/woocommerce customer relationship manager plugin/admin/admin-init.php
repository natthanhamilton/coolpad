<?php
/**
 * Admin init logic
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */


if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'customers.php' );
require_once( 'groups.php' );
require_once( 'logs.php' );
require_once( 'customer_details.php' );

add_action( 'admin_menu', 'wc_customer_relationship_manager_add_menu' );

/**
 * Add the menu item
 */
function wc_customer_relationship_manager_add_menu() {
	global $wc_customer_relationship_manager;

	$hook = add_menu_page(
		__( 'Customers', 'wc_customer_relationship_manager' ), // page title
		__( 'Customers', 'wc_customer_relationship_manager' ), // menu title
		'manage_woocommerce', // capability
		$wc_customer_relationship_manager->id, // unique menu slug
		'wc_customer_relationship_manager_render_list_page',
		null,
		56
	);
	$new_customer_hook = add_submenu_page( $wc_customer_relationship_manager->id, __( "Add New Customer", 'wc_customer_relationship_manager' ), __( "Add New", 'wc_customer_relationship_manager'), 'manage_woocommerce', 'wc_new_customer', 'wc_customer_relationship_manager_render_new_customer_page' );

/* 	$groups_hook = add_submenu_page( $wc_customer_relationship_manager->id, __( "Groups", 'wc_customer_relationship_manager' ), __( "Groups", 'wc_customer_relationship_manager'), 'manage_woocommerce', 'wc_user_grps', 'wc_customer_relationship_manager_render_groups_list_page' ); */

	$logs_hook = add_submenu_page($wc_customer_relationship_manager->id, __( "Activity", 'wc_customer_relationship_manager' ), __( "Activity", 'wc_customer_relationship_manager'), 'manage_woocommerce', 'wc_crm_logs', 'wc_customer_relationship_manager_render_logs_page' );

	add_action( "load-$hook", 'wc_customer_relationship_manager_add_options' );
	add_action( "load-$groups_hook", 'wc_customer_relationship_manager_groups_add_options' );
	add_action( "load-$logs_hook", 'wc_customer_relationship_manager_logs_add_options' );
	add_action( "load-$new_customer_hook", 'wc_customer_relationship_manager_new_customer_add_options' );
}

