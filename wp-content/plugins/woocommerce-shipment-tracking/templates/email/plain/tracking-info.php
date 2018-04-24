<?php
/**
 * Shipment Tracking
 *
 * Shows tracking information in the plain text order email
 *
 * @author  WooThemes
 * @package WooCommerce Shipment Tracking/templates/email/plain
 * @version 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $tracking_items ) : 
	
	echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'TRACKING INFORMATION', 'wc_shipment_tracking' ) ); 

		echo  "\n";

		foreach ( $tracking_items as $tracking_item ) {
			
			echo esc_html( $tracking_item[ 'formatted_tracking_provider' ] ) . "\n";
			echo esc_html( $tracking_item[ 'tracking_number' ] ) . "\n";
			echo esc_url( $tracking_item[ 'formatted_tracking_link' ] ) . "\n\n";
			
		}

	echo "=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-= \n\n";

endif;

?>
