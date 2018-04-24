<?php
/**
 * View Order: Tracking information
 *
 * Shows tracking numbers view order page
 *
 * @author  WooThemes
 * @package WooCommerce Shipment Tracking/templates/myaccount
 * @version 1.3.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $tracking_items ) : ?>

	<h2><?php echo apply_filters( 'woocommerce_shipment_tracking_my_orders_title', __( 'Tracking Information', 'wc_shipment_tracking' ) ); ?></h2>

	<table class="shop_table shop_table_responsive my_account_tracking">
		<thead>
			<tr>
				<th class="tracking-provider"><span class="nobr"><?php _e( 'Provider', 'wc_shipment_tracking' ); ?></span></th>
				<th class="tracking-number"><span class="nobr"><?php _e( 'Tracking Number', 'wc_shipment_tracking' ); ?></span></th>
				<th class="date-shipped"><span class="nobr"><?php _e( 'Date', 'wc_shipment_tracking' ); ?></span></th>
				<th class="order-actions">&nbsp;</th>
			</tr>
		</thead>
		<tbody><?php
			foreach ( $tracking_items as $tracking_item ) {
				
				?><tr class="tracking">
					<td class="tracking-provider" data-title="<?php _e( 'Provider', 'wc_shipment_tracking' ); ?>">
						<?php echo $tracking_item[ 'formatted_tracking_provider' ]; ?>
					</td>
					<td class="tracking-number" data-title="<?php _e( 'Tracking Number', 'wc_shipment_tracking' ); ?>">
						<?php echo $tracking_item[ 'tracking_number' ]; ?>
					</td>
					<td class="date-shipped" data-title="<?php _e( 'Status', 'wc_shipment_tracking' ); ?>" style="text-align:left; white-space:nowrap;">
						<time datetime="<?php echo date( 'Y-m-d', $tracking_item[ 'date_shipped' ] ); ?>" title="<?php echo date( 'Y-m-d', $tracking_item[ 'date_shipped' ] ); ?>"><?php echo date_i18n( get_option( 'date_format' ), $tracking_item[ 'date_shipped' ] ); ?></time>
					</td>
					<td class="order-actions" style="text-align: center;">
							<a href="<?php echo esc_url( $tracking_item[ 'formatted_tracking_link' ] ); ?>" target="_blank" class="button"><?php _e( 'Track', 'wc_shipment_tracking' ); ?></a>
					</td>
				</tr><?php
			}
		?></tbody>
	</table>

<?php
endif;
