<?php
/*
Plugin Name: WooCommerce Use My Shipper
Plugin URI:  http://ignitewoo.com
Description: Allows shoppers to have their order be shipped using their own shipper account
Version: 3.3.2
Author: IgniteWoo.com
Author URI: http://ignitewoo.com

Copyright (c) 2014 - IgniteWoo.com -- ALL RIGHTS RESERVED
*/

add_action( 'woocommerce_shipping_init', 'init_use_my_shipping' );

function init_use_my_shipping() { 

	require_once( dirname(__FILE__) . '/class-wc-use-my-shipper.php' );
	
	add_filter('woocommerce_shipping_methods', 'add_use_my_shipper_method', 10 );

	// order page meta box
	//add_action( 'add_meta_boxes', array( 'ups_rate', 'add_order_meta_box' ), -1 );
	
	add_action( 'woocommerce_review_order_after_shipping', array( 'WC_USE_MY_SHIPPER', 'ign_use_my_shipper' ), -1 );

}

function add_use_my_shipper_method( $methods ) {

	$methods[] = 'WC_USE_MY_SHIPPER'; 
	return $methods;
}

add_action( 'init', 'use_my_shipper_init', 999 );

function use_my_shipper_init() {

	if ( !defined( 'DOING_AJAX' ) )
		return;
		
	if ( !class_exists( 'WC_USE_MY_SHIPPER' ) )
		require_once( dirname(__FILE__) . '/class-wc-use-my-shipper.php' );
		
	add_action( 'wp_ajax_woocommerce_checkout', array( 'WC_USE_MY_SHIPPER', 'my_shipper_review' ), 1 );
	add_action( 'wp_ajax_nopriv_woocommerce_checkout', array( 'WC_USE_MY_SHIPPER', 'my_shipper_review' ), 1 );
	add_action( 'wc_ajax_checkout', array( 'WC_USE_MY_SHIPPER', 'my_shipper_review' ), 1 );
	
}

// WC 2.0.x
add_action( 'woocommerce_admin_order_totals_after_shipping', 'show_my_shipper', 1, 1 );
// WC 2.1.x
add_action( 'woocommerce_admin_order_totals_after_shipping_item', 'show_my_shipper', 1, 1 );
	
function show_my_shipper( $item_id = null ) { 
	global $post, $ign_shipper_displayed; 
	
	if ( $ign_shipper_displayed )
		return;
	
	$acct = get_post_meta( $post->ID, 'my_shipper_acct', true );
	
	if ( empty( $acct ) )
		return;
		
	$shipper = get_post_meta( $post->ID, 'my_shipper', true );
		
	if ( empty( $shipper ) )
		return;
		
	$shipper_note = get_post_meta( $post->ID, 'my_shipper_note', true );
	
	?>
	<div style="clear:both;border:1px solid #ccc; background-color:#fff; padding: 0.5em; margin-bottom:0.5em; text-align:left">
		<strong><?php echo $shipper ?>: </strong><?php echo $acct; ?>
		<?php if ( !empty( $shipper_note ) ) { ?>
		<br/><br/>
		<?php echo wpautop( $shipper_note ); ?>
		<?php } ?>
	</div>
	<?php
	
	$ign_shipper_displayed = true;
	
}


add_action( 'woocommerce_checkout_order_processed', 'my_shipper_validate', 1, 2 );

function my_shipper_validate( $order_id, $posted ) { 

	if ( empty( $posted ) || empty( $posted['shipping_method'] ) )
		return;
		
	if ( !in_array( 'use_my_shipper', $posted['shipping_method'] ) )
		return; 
		
	$shipper = isset( $_REQUEST['my_shipper'] ) ? $_REQUEST['my_shipper'] : '';
	
	$acct = isset( $_REQUEST['use_my_shipper'] ) ? $_REQUEST['use_my_shipper'] : '';
	
	$note = isset( $_REQUEST['use_my_shipper_note'] ) ? $_REQUEST['use_my_shipper_note'] : '';
	
	if ( empty( $shipper ) || empty( $acct ) )
		return;
		
	update_post_meta( $order_id, 'my_shipper_acct', $acct );
	
	update_post_meta( $order_id, 'my_shipper_note', $note );
	
	update_post_meta( $order_id, 'my_shipper', $shipper );
}


add_action( 'woocommerce_email_order_meta', 'maybe_add_to_emails', 1, 3 );

function maybe_add_to_emails( $order, $dummy1 = true, $dummy2 = false ) { 

	if ( empty( $order ) || empty( $order->id ) ) 
		return;
		
	$settings = get_option( 'woocommerce_use_my_shipper_settings', array() );
	
	if ( empty( $settings ) )
		return;
		
	if ( empty( $settings['add_to_email'] ) || 'yes' !== $settings['add_to_email'] )
		return;
	
	$shipper = get_post_meta( $order->id, 'my_shipper', true );

	$acct = get_post_meta( $order->id, 'my_shipper_acct', true );
   
	$note = get_post_meta( $order->id, 'my_shipper_note', true );
		
	if ( empty( $acct ) || empty( $shipper ) )
		return;
		
	?>
	<p><strong><?php _e( 'Shipper:', 'woocommerce' ); ?></strong> <?php echo $shipper; ?></p>
	<p><strong><?php _e( 'Acct:', 'woocommerce' ); ?></strong> <?php echo $acct; ?></p>
	<?php 
	
	if ( !empty( $note ) ) { 
	?>
	<p><strong><?php _e( 'Shipping Note:', 'woocommerce' ); ?></strong></p>
	<?php echo wpautop( $note ); ?></p>
	<?php
	}
}


add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ign_my_shipper_action_links' );

function ign_my_shipper_action_links( $links ) {

	$plugin_links = array(
		'<a href="http://ignitewoo.com/ignitewoo-software-documentation/" target="_blank">' . __( 'Docs', 'woocommerce' ) . '</a>',
		'<a href="http://ignitewoo.com/" target="_blank">' . __( 'More Plugins', 'woocommerce' ) . '</a>',
		'<a href="http://ignitewoo.com/contact-us" target="_blank">' . __( 'Support', 'woocommerce' ) . '</a>',
	);

	return array_merge( $plugin_links, $links );
}

if ( ! function_exists( 'ignitewoo_queue_update' ) )
	require_once( dirname( __FILE__ ) . '/ignitewoo_updater/ignitewoo_update_api.php' );

$this_plugin_base = plugin_basename( __FILE__ );

add_action( "after_plugin_row_" . $this_plugin_base, 'ignite_plugin_update_row', 1, 2 );


ignitewoo_queue_update( plugin_basename( __FILE__ ), 'be99e94cf92f28b5af3d848efd9bac73', '10773' );

