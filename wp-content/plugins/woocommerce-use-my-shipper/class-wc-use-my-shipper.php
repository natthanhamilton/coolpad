<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class WC_USE_MY_SHIPPER extends WC_Shipping_Method {


	function __construct() {
		global $ums_enabled; 
	
		$this->id = 'use_my_shipper';
		
		$this->method_title = __( 'Use My Shipper', 'woocommerce' );
		
		$this->init();
	}

	
	function init() {

		$this->init_form_fields();
		
		$this->init_settings();

		$this->enabled		= $this->get_option( 'enabled' );
		$this->title 		= $this->get_option( 'title' );
		$this->availability 	= $this->get_option( 'availability' );
		$this->countries 	= $this->get_option( 'countries' );
		$this->shipper_label	= $this->get_option( 'shipper_label' );
		$this->allowed_shippers = $this->get_option( 'allowed_shippers' );
		
		add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
	}


	function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
							'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Enable This Method', 'woocommerce' ),
							'default' 		=> 'no'
						),
			'title' => array(
							'title' 		=> __( 'Method Title', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default'		=> __( 'Use Your Shipper Account', 'woocommerce' ),
							'desc_tip'		=> true,
						),

			'availability' => array(
							'title' 		=> __( 'Method availability', 'woocommerce' ),
							'type' 			=> 'select',
							'default' 		=> 'all',
							'class'			=> 'availability',
							'options'		=> array(
								'all' 		=> __( 'All allowed countries', 'woocommerce' ),
								'specific' 	=> __( 'Specific Countries', 'woocommerce' )
							)
						),
			'countries' => array(
							'title' 		=> __( 'Specific Countries', 'woocommerce' ),
							'type' 			=> 'multiselect',
							'class'			=> 'chosen_select',
							'css'			=> 'width: 450px;',
							'default' 		=> '',
							'options'		=> WC()->countries->get_shipping_countries(),
							'custom_attributes' => array(
								'data-placeholder' => __( 'Select some countries', 'woocommerce' )
							)
						),
			'shipper_label' => array(
							'title' 		=> __( 'Field Label', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __( 'The label displayed with the shipper account input field', 'woocommerce' ),
							'default'		=> __( 'Shipper Account Number', 'woocommerce' ),
							'desc_tip'		=> true,
						),
			'allowed_shippers' => array(
							'title' 		=> __( 'Allowed Shippers', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __( 'A comma separated list of allowed shippers', 'woocommerce' ),
							'default'		=> 'Fedex, UPS',
							'desc_tip'		=> true,
						),
			'shipping_note' => array(
							'title' 		=> __( 'Enable Shipping Note', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'description' 	=> __( 'Enable a shipping comment box to appear beneath the list of shippers. This field would be optional and allow the shopper to enter a message to you.', 'woocommerce' ),
							'default'		=> '',
							'desc_tip'		=> true,
						),
			'add_to_email' => array(
							'title' 		=> __( 'Add to order emails', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Enable', 'woocommerce' ),
							'default' 		=> 'no',
							'description' 	=> __( 'Insert shipper, account, and note into order emails for customers and admins ', 'woocommerce' ),
							'desc_tip'		=> true,
						),
			);

	}

	public static function ign_use_my_shipper() { 

		$settings = get_option( 'woocommerce_use_my_shipper_settings' );
		
		if ( 'yes' !== $settings['enabled'] ) 
			return;
		
		$shippers = explode( ',', $settings['allowed_shippers'] );
		
		$acct = isset( $_REQUEST['use_my_shipper'] ) ? $_REQUEST['use_my_shipper'] : '';
		
		if ( !empty( $_REQUEST['post_data'] ) ) { 
		
			$args = wp_parse_args(  $_REQUEST['post_data'] );;
			
			if ( !empty( $args['my_shipper'] ) )
				$selected = $args['my_shipper'];
			else 
				$selected = null;
				
			if ( !empty( $args['use_my_shipper'] ) )
				$acct = $args['use_my_shipper'];
		
		}
		
		$packages = WC()->shipping->get_packages();
		if ( count( $packages[0]['rates'] ) <= 1 ) 
			$css = '';
		else 
			$css = 'display:none';
		?>
		
		<tr id="use_my_shipper" style="<?php echo $css ?>">
			<th><?php echo $settings['shipper_label'] ?></th>
			<td>
				<div style="margin-bottom: 0.5em">
				<select class="my_shipper" name="my_shipper" style="width:200px">
					<?php foreach( $shippers as $s ) { ?>
					<option value="<?php echo $s ?>" <?php selected( $s, $selected, true ) ?>><?php echo $s ?></option>
					<?php } ?>
				</select>
				</div>
				<input class="input-text" type="text" name="use_my_shipper" value="<?php echo $acct ?>" placeholder="<?php _e( 'Account number', 'woocommerce') ?>">

				<?php if ( !empty( $settings['shipping_note'] ) && 'yes' == $settings['shipping_note'] ) { ?>
				
				<textarea class="input-text shipping_note" name="use_my_shipper_note" placeholder="<?php _e( 'Shipping instructions...', 'woocommerce') ?>" style="width:98%;margin-top:5px;min-height:80px;font-size:100%"></textarea>
				
				<?php } ?>
				
				<script>
				jQuery( document ).ready( function( $ ) { 
				
					<?php if ( version_compare( WOOCOMMERCE_VERSION, '2.3', '>=' ) ) { ?>
					$( '.my_shipper' ).select2({width: "200px"});
					<?php } else { ?>
					$( '.my_shipper' ).chosen({width: "200px"});
					<?php } ?>
					
					<?php if ( version_compare( WOOCOMMERCE_VERSION, '2.5', '<' ) && 'select' === get_option( 'woocommerce_shipping_method_format' ) ) { ?>
					val = $( '.shipping_method option:selected' ).val();
					<?php } else { ?>
					val = $( '.shipping_method:checked' ).val();
					<?php } ?>

					if ( NaN == val || null == val ) 
						return;
						
					if ( 'use_my_shipper' == val ) 
						$( '#use_my_shipper' ).show();
					else
						$( '#use_my_shipper' ).hide();
				
				});
				</script>
			</td>
		</tr>
		<?php
	}
	
	
	public static function my_shipper_review() { 
		global $woocommerce;

		$shipper = $_REQUEST['shipping_method'];

		if ( empty( $shipper ) )
			return;
			
		foreach ( (array)$shipper as $s ) {
		
			if ( 'use_my_shipper' == $s ) {
		
				if ( empty( $_REQUEST['use_my_shipper'] ) ) {
				
					$settings = get_option( 'woocommerce_use_my_shipper_settings' );
		
					$label = $settings['shipper_label'];
		
					if ( function_exists( 'wc_add_notice' ) )
						wc_add_notice( sprintf( '<strong>%s</strong> is required', $label ), 'error' );
					else
						$woocommerce->add_error( sprintf( '<strong>%s</strong> is required', $label ) );
				
				}
					
			
			}
		}
		
	
	}
	

	public function admin_options() {

		?>
		
		<h3><?php _e( 'Free Shipping', 'woocommerce' ); ?></h3>
		
		<table class="form-table">
		<?php
			$this->generate_settings_html();
		?>
		</table> 
		
		<?php
	}


	function is_available( $package ) {

		if ( 'no' == $this->enabled )
			return false;
		
		$shippers = explode( ',', $this->allowed_shippers );

		$shippers = array_filter( $shippers );
		
		if ( empty( $shippers ) || count( $shippers ) <= 0 )
			return false;
		
		$ship_to_countries = '';

		if ( 'specific' == $this->availability )
			$ship_to_countries = $this->countries;
		else
			$ship_to_countries = array_keys( WC()->countries->get_shipping_countries() );

		if ( is_array( $ship_to_countries ) )
			if ( ! in_array( $package['destination']['country'], $ship_to_countries ) )
				return false;

		$is_available = true;

		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available );
	}


	function calculate_shipping() {
	
		$args = array(
			'id' 	=> $this->id,
			'label' => $this->title,
			'cost' 	=> 0,
			'taxes' => false
		);
		
		$this->add_rate( $args );
	}

}