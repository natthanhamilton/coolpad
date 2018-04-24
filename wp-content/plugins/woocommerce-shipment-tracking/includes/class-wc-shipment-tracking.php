<?php

/**
 * # WooCommerce Shipment Tracking Actions
 *
 * @since 1.4.0
 */

class WC_Shipment_Tracking_Actions {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'get_post_metadata', array( $this, 'old_data_filter' ), 10, 4 );
	}

	/* @var object Class Instance */
    private static $instance;

	/**
     * Get the class instance
     */
    public static function get_instance() {
        return null === self::$instance ? ( self::$instance = new self ) : self::$instance;
    }
    

	/**
	 * Function to filter for calls to the old tracking data
	 * Returns value of first tracking item.
	*/
	public function old_data_filter( $metadata, $object_id, $meta_key, $single ) {
		if ( in_array( $meta_key, array( '_tracking_provider', '_custom_tracking_provider', '_custom_tracking_link', '_tracking_number', '_date_shipped' ) ) ) {

			global $wpdb;

			// Check to see if old data exists, if so convert
			// Query DB directly to avoid triggering this filter
			$old_tracking_number = $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = '_tracking_number'", $object_id ) );

			if ( $old_tracking_number ) {
				$this->convert_old_tracking_to_new( $object_id );
			}

			// Get value of request meta key from new data
			$tracking_items = $this->get_tracking_items( $object_id, false );

			if ( count( $tracking_items ) > 0 ) {
				foreach( $tracking_items as $item ) {
					foreach( $item as $key => $value ) {
						if ( "_$key" == $meta_key ) {
							return $value;
						}
					}
				}
			}
			return '';
		}
	}

	/**
	 * Get shiping providers
	 * @return array
	 */
	public function get_providers() {
		return apply_filters( 'wc_shipment_tracking_get_providers', array(
			'Australia' => array(
				'Australia Post'
					=> 'http://auspost.com.au/track/track.html?id=%1$s',
				'Fastway Couriers'
					=> 'http://www.fastway.com.au/courier-services/track-your-parcel?l=%1$s',
			),
			'Austria' => array(
				'post.at' =>
					'http://www.post.at/sendungsverfolgung.php?pnum1=%1$s',
				'dhl.at' =>
					'http://www.dhl.at/content/at/de/express/sendungsverfolgung.html?brand=DHL&AWB=%1$s',
				'DPD.at' =>
					'https://tracking.dpd.de/parcelstatus?locale=de_AT&query=%1$s',
			),
			'Brazil' => array(
				'Correios'
					=> 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=%1$s'
			),
			'Canada' => array(
				'Canada Post'
					=> 'http://www.canadapost.ca/cpotools/apps/track/personal/findByTrackNumber?trackingNumber=%1$s',
			),
			'Czech Republic' => array(
				'PPL.cz'
					=> 'http://www.ppl.cz/main2.aspx?cls=Package&idSearch=%1$s',
				'Česká pošta'
					=> 'https://www.postaonline.cz/trackandtrace/-/zasilka/cislo?parcelNumbers=%1$s',
				'DHL.cz'
					=> 'http://www.dhl.cz/cs/express/sledovani_zasilek.html?AWB=%1$s',
				'DPD.cz'
					=> 'https://tracking.dpd.de/parcelstatus?locale=cs_CZ&query=%1$s',
			),
			'Finland' => array(
				'Itella'
					=> 'http://www.posti.fi/itemtracking/posti/search_by_shipment_id?lang=en&ShipmentId=%1$s',
			),
			'France' => array(
				'Colissimo'
					=> 'http://www.colissimo.fr/portail_colissimo/suivre.do?language=fr_FR&colispart=%1$s',
			),
			'Germany' => array(
				'DHL Intraship (DE)'
					=> 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s&rfn=&extendedSearch=true',
				'Hermes'
					=> 'https://tracking.hermesworld.com/?TrackID=%1$s',
				'Deutsche Post DHL'
					=> 'http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc=%1$s',
				'UPS Germany'
					=> 'http://wwwapps.ups.com/WebTracking/processInputRequest?sort_by=status&tracknums_displayed=1&TypeOfInquiryNumber=T&loc=de_DE&InquiryNumber1=%1$s',
				'DPD.de'
					=> 'https://tracking.dpd.de/parcelstatus?query=%1$s&locale=en_DE',
			),
			'Ireland' => array(
				'DPD.ie'
					=> 'http://www2.dpd.ie/Services/QuickTrack/tabid/222/ConsignmentID/%1$s/Default.aspx',
			),
			'Italy' => array(
				'BRT (Bartolini)'
					=> 'http://as777.brt.it/vas/sped_det_show.hsm?referer=sped_numspe_par.htm&Nspediz=%1$s',
				'DHL Express'
					=> 'http://www.dhl.it/it/express/ricerca.html?AWB=%1$s&brand=DHL'
			),
			'India' => array(
				'DTDC'
					=> 'http://www.dtdc.in/dtdcTrack/Tracking/consignInfo.asp?strCnno=%1$s',
			),
			'Netherlands' => array(
				'PostNL'
					=> 'https://mijnpakket.postnl.nl/Claim?Barcode=%1$s&Postalcode=%2$s&Foreign=False&ShowAnonymousLayover=False&CustomerServiceClaim=False',
				'DPD.NL'
					=> 'http://track.dpdnl.nl/?parcelnumber=%1$s',
			),
			'New Zealand' => array(
				'Courier Post'
					=> 'http://trackandtrace.courierpost.co.nz/Search/%1$s',
				'NZ Post'
					=> 'http://www.nzpost.co.nz/tools/tracking?trackid=%1$s',
				'Fastways'
					=> 'http://www.fastway.co.nz/courier-services/track-your-parcel?l=%1$s',
				'PBT Couriers'
					=> 'http://www.pbt.com/nick/results.cfm?ticketNo=%1$s',
			),
			'South African' => array(
				'SAPO'
					=> 'http://sms.postoffice.co.za/TrackingParcels/Parcel.aspx?id=%1$s',
			),
			'Sweden' => array(
				'Posten AB'
					=> 'http://www.posten.se/sv/Kundservice/Sidor/Sok-brev-paket.aspx?search=%1$s',
				'DHL.se'
					=> 'http://www.dhl.se/content/se/sv/express/godssoekning.shtml?brand=DHL&AWB=%1$s',
				'Bring.se'
					=> 'http://tracking.bring.se/tracking.html?q=%1$s',
				'UPS.se'
					=> 'http://wwwapps.ups.com/WebTracking/track?track=yes&loc=sv_SE&trackNums=%1$s',
				'DB Schenker'
					=> 'http://privpakportal.schenker.nu/TrackAndTrace/packagesearch.aspx?packageId=%1$s'
			),
			'United Kingdom' => array(
				'DHL'
					=> 'http://www.dhl.com/content/g0/en/express/tracking.shtml?brand=DHL&AWB=%1$s',
				'DPD.co.uk'
					=> 'http://www.dpd.co.uk/tracking/trackingSearch.do?search.searchType=0&search.parcelNumber=%1$s',
				'InterLink'
					=> 'http://www.interlinkexpress.com/apps/tracking/?reference=%1$s&postcode=%2$s#results',
				'ParcelForce'
					=> 'http://www.parcelforce.com/portal/pw/track?trackNumber=%1$s',
				'Royal Mail'
					=> 'https://www.royalmail.com/track-your-item/?trackNumber=%1$s',
				'TNT Express (consignment)'
					=> 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=CON&respLang=en&
respCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&g
enericSiteIdent=',
				'TNT Express (reference)'
					=> 'http://www.tnt.com/webtracker/tracking.do?requestType=GEN&searchType=REF&respLang=en&r
espCountry=GENERIC&sourceID=1&sourceCountry=ww&cons=%1$s&navigation=1&gen
ericSiteIdent=',
				'UK Mail'
					=> 'https://old.ukmail.com/ConsignmentStatus/ConsignmentSearchResults.aspx?SearchType=Reference&SearchString=%1$s',
			),
			'United States' => array(
				'Fedex'
					=> 'http://www.fedex.com/Tracking?action=track&tracknumbers=%1$s',
				'FedEx Sameday'
					=> 'https://www.fedexsameday.com/fdx_dotracking_ua.aspx?tracknum=%1$s',
				'OnTrac'
					=> 'http://www.ontrac.com/trackingdetail.asp?tracking=%1$s',
				'UPS'
					=> 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=%1$s',
				'USPS'
					=> 'https://tools.usps.com/go/TrackConfirmAction_input?qtc_tLabels1=%1$s',
			),
		) );
	}

	/**
	 * Localisation
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wc_shipment_tracking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	public function admin_styles() {
		wp_enqueue_style( 'shipment_tracking_styles', plugins_url( basename( dirname( dirname( __FILE__ ) ) ) ) . '/assets/css/admin.css' );
	}

	/**
	 * Add the meta box for shipment info on the order page
	 *
	 * @access public
	 */
	public function add_meta_box() {
		add_meta_box( 'woocommerce-shipment-tracking', __( 'Shipment Tracking', 'wc_shipment_tracking' ), array( $this, 'meta_box' ), 'shop_order', 'side', 'high' );
	}

	/**
	 * Returns a HTML node for a tracking item for the admin meta box
	 *
	 * @access public
	 */
	public function display_html_tracking_item_for_meta_box( $order_id, $item ) {
			$formatted = $this->get_formatted_tracking_item( $order_id, $item );
			?>
			<div class="tracking-item" id="tracking-item-<?php echo esc_attr( $item[ 'tracking_id' ] ); ?>">
				<p class="tracking-content">
					<strong><?php echo esc_html( $formatted[ 'formatted_tracking_provider' ] ); ?></strong>
					<?php if( strlen( $formatted[ 'formatted_tracking_link' ] ) > 0 ) : ?>
						- <?php echo sprintf( '<a href="%s" target="_blank" title="' . esc_attr( __( 'Click here to track your shipment', 'wc_shipment_tracking' ) ) . '">' . __( 'Track', 'wc_shipment_tracking' ) . '</a>', $formatted[ 'formatted_tracking_link' ] ); ?>
					<?php endif; ?>
					<br/>
					<em><?php echo esc_html( $item[ 'tracking_number' ] ); ?></em>
				</p>
				<p class="meta">
					<?php echo esc_html( sprintf( __( 'Shipped on %s', 'wc_shipment_tracking' ), date_i18n( 'Y-m-d', $item[ 'date_shipped' ] ) ) ); ?>
					<a href="#" class="delete-tracking" rel="<?php echo esc_attr( $item[ 'tracking_id' ] ); ?>"><?php _e( 'Delete', 'wc_shipment_tracking' ); ?></a>
				</p>
			</div>
			<?php
	}

	/**
	 * Show the meta box for shipment info on the order page
	 *
	 * @access public
	 */
	public function meta_box() {
		global $woocommerce, $post;

		$tracking_items = $this->get_tracking_items( $post->ID );

		echo '<div id="tracking-items">';

		if ( count( $tracking_items ) > 0 ) {
			foreach( $tracking_items as $tracking_item ) {
				$this->display_html_tracking_item_for_meta_box( $post->ID, $tracking_item );
			}
		}

		echo '</div>';

		echo '<button class="button button-show-form" type="button">' . __( 'Add Tracking Number', 'wc_shipment_tracking' ) . '</button>';

		echo '<div id="shipment-tracking-form">';
		// Providers
		echo '<p class="form-field tracking_provider_field"><label for="tracking_provider">' . __( 'Provider:', 'wc_shipment_tracking' ) . '</label><br/><select id="tracking_provider" name="tracking_provider" class="chosen_select" style="width:100%;">';

		echo '<option value="">' . __( 'Custom Provider', 'wc_shipment_tracking' ) . '</option>';

		$selected_provider = '';

		if ( ! $selected_provider )
			$selected_provider = sanitize_title( apply_filters( 'woocommerce_shipment_tracking_default_provider', '' ) );

		foreach ( $this->get_providers() as $provider_group => $providers ) {

			echo '<optgroup label="' . $provider_group . '">';

			foreach ( $providers as $provider => $url ) {

				echo '<option value="' . sanitize_title( $provider ) . '" ' . selected( sanitize_title( $provider ), $selected_provider, true ) . '>' . $provider . '</option>';

			}

			echo '</optgroup>';

		}

		echo '</select> ';

		woocommerce_wp_hidden_input( array(
			'id' 			=> 'wc_shipment_tracking_delete_nonce',
			'value'			=> wp_create_nonce( 'delete-tracking-item' )
		) );

		woocommerce_wp_hidden_input( array(
			'id' 			=> 'wc_shipment_tracking_create_nonce',
			'value'			=> wp_create_nonce( 'create-tracking-item' )
		) );

		woocommerce_wp_text_input( array(
			'id' 			=> 'custom_tracking_provider',
			'label' 		=> __( 'Provider Name:', 'wc_shipment_tracking' ),
			'placeholder' 	=> '',
			'description' 	=> '',
			'value'			=> ''
		) );

		woocommerce_wp_text_input( array(
			'id' 			=> 'tracking_number',
			'label' 		=> __( 'Tracking number:', 'wc_shipment_tracking' ),
			'placeholder' 	=> '',
			'description' 	=> '',
			'value'			=> ''
		) );

		woocommerce_wp_text_input( array(
			'id' 			=> 'custom_tracking_link',
			'label' 		=> __( 'Tracking link:', 'wc_shipment_tracking' ),
			'placeholder' 	=> 'http://',
			'description' 	=> '',
			'value'			=> ''
		) );

		woocommerce_wp_text_input( array(
			'id' 			=> 'date_shipped',
			'label' 		=> __( 'Date shipped:', 'wc_shipment_tracking' ),
			'placeholder' 	=> date_i18n( __( 'Y-m-d', 'wc_shipment_tracking' ), time() ),
			'description' 	=> '',
			'class'			=> 'date-picker-field',
			'value'			=> date_i18n( __( 'Y-m-d', 'wc_shipment_tracking' ), current_time( 'timestamp' ) )
		) );

		echo '<button class="button button-primary button-save-form">' . __( 'Save Tracking', 'wc_shipment_tracking' ) . '</button>';

		// Live preview
		echo '<p class="preview_tracking_link">' . __( 'Preview:', 'wc_shipment_tracking' ) . ' <a href="" target="_blank">' . __( 'Click here to track your shipment', 'wc_shipment_tracking' ) . '</a></p>';

		echo '</div>';

		$provider_array = array();

		foreach ( $this->get_providers() as $providers ) {
			foreach ( $providers as $provider => $format ) {
				$provider_array[sanitize_title( $provider )] = urlencode( $format );
			}
		}

		$js = "
			jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').hide();

			jQuery('input#custom_tracking_link, input#tracking_number, #tracking_provider').change(function(){

				var tracking = jQuery('input#tracking_number').val();
				var provider = jQuery('#tracking_provider').val();
				var providers = jQuery.parseJSON( '" . json_encode( $provider_array ) . "' );

				postcode = jQuery('#_billing_postcode').val();
				postcode = encodeURIComponent( postcode );

				var link = '';

				if ( providers[ provider ] ) {
					link = providers[provider];
					link = link.replace( '%251%24s', tracking );
					link = link.replace( '%252%24s', postcode );
					link = decodeURIComponent( link );

					jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').hide();
				} else {
					jQuery('p.custom_tracking_link_field, p.custom_tracking_provider_field').show();

					link = jQuery('input#custom_tracking_link').val();
				}

				if ( link ) {
					jQuery('p.preview_tracking_link a').attr('href', link);
					jQuery('p.preview_tracking_link').show();
				} else {
					jQuery('p.preview_tracking_link').hide();
				}

			}).change();";

		if ( function_exists( 'wc_enqueue_js' ) ) {
			wc_enqueue_js( $js );
		} else {
			$woocommerce->add_inline_js( $js );
		}

		wp_enqueue_script( 'wc-shipment-tracking-js', $GLOBALS['WC_Shipment_Tracking']->plugin_url . '/assets/js/admin.min.js' );

	}

	/**
	 * Order Tracking Save
	 *
	 * Function for saving tracking items
	 */
	public function save_meta_box( $post_id, $post ) {

		if ( isset( $_POST['tracking_number'] ) && strlen( $_POST['tracking_number'] ) > 0 ) {

			$args = array(
				'tracking_provider'        => wc_clean( $_POST[ 'tracking_provider' ] ),
				'custom_tracking_provider' => wc_clean( $_POST[ 'custom_tracking_provider' ] ),
				'custom_tracking_link'     => wc_clean( $_POST[ 'custom_tracking_link' ] ),
				'tracking_number'          => wc_clean( $_POST[ 'tracking_number' ] ),
				'date_shipped'             => wc_clean( $_POST[ 'date_shipped' ] )
			);

			$this->add_tracking_item( $post_id, $args );
		}
	}

	/**
	 * Order Tracking Save AJAX
	 *
	 * Function for saving tracking items via AJAX
	 */
	public function save_meta_box_ajax() {

		check_ajax_referer( 'create-tracking-item', 'security', true );

		if ( isset( $_POST['tracking_number'] ) && strlen( $_POST['tracking_number'] ) > 0 ) {

			$order_id = wc_clean( $_POST[ 'order_id' ] );
			$args = array(
				'tracking_provider'        => wc_clean( $_POST[ 'tracking_provider' ] ),
				'custom_tracking_provider' => wc_clean( $_POST[ 'custom_tracking_provider' ] ),
				'custom_tracking_link'     => wc_clean( $_POST[ 'custom_tracking_link' ] ),
				'tracking_number'          => wc_clean( $_POST[ 'tracking_number' ] ),
				'date_shipped'             => wc_clean( $_POST[ 'date_shipped' ] )
			);

			$tracking_item = $this->add_tracking_item( $order_id, $args );

			$this->display_html_tracking_item_for_meta_box( $order_id, $tracking_item );
		}

		die();
	}

	/**
	 * Order Tracking Delete
	 *
	 * Function to delete a tracking item
	 */
	public function meta_box_delete_tracking() {

		check_ajax_referer( 'delete-tracking-item', 'security', true );

		$order_id = wc_clean( $_POST[ 'order_id' ] );
		$tracking_id = wc_clean( $_POST[ 'tracking_id' ] );

		$this->delete_tracking_item( $order_id, $tracking_id );
	}

	/**
	 * Display Shipment info in the frontend (order view/tracking page).
	 *
	 * @access public
	 */
	public function display_tracking_info( $order_id ) {
		wc_get_template( 'myaccount/view-order.php', array( 'tracking_items' => $this->get_tracking_items( $order_id, true ) ), $this->get_plugin_path() . '/templates/', $this->get_plugin_path() . '/templates/' );
	}

	/**
	 * Display shipment info in customer emails.
	 *
	 * @access public
	 * @return void
	 */
	public function email_display( $order, $sent_to_admin, $plain_text = null ) {

		if ( $plain_text === true ) {
			wc_get_template( 'email/plain/tracking-info.php', array( 'tracking_items' => $this->get_tracking_items( $order->id, true ) ), '', $this->get_plugin_path() . '/templates/' );
		}
		else {
			wc_get_template( 'email/tracking-info.php', array( 'tracking_items' => $this->get_tracking_items( $order->id, true ) ), '', $this->get_plugin_path() . '/templates/' );
		}
	}

	/**
	 * Adds support for Customer/Order CSV Export by adding appropraite column headers
	 *
	 * @param array $headers existing array of header key/names for the CSV export
	 * @return array
	 */
	public function add_tracking_info_to_csv_export_column_headers( $headers ) {

		$headers['shipment_tracking'] = 'shipment_tracking';
		return $headers;

	}

	/**
	 * Adds support for Customer/Order CSV Export by adding data for the column headers
	 *
	 * @param array $order_data generated order data matching the column keys in the header
	 * @param WC_Order $order order being exported
	 * @param \WC_CSV_Export_Generator $csv_generator instance
	 * @return array
	 */
	public function add_tracking_info_to_csv_export_column_data( $order_data, $order, $csv_generator ) {

		$tracking_items = $this->get_tracking_items( $order->id, true );

		$shipment_tracking_csv_output = '';

		if ( count( $tracking_items ) > 0 ) {
			foreach( $tracking_items as $item ) {
				$pipe = null;
				foreach( $item as $key => $value ) {
					if ( $key == 'date_shipped' )
						$value = date( 'Y-m-d', $value );
					$shipment_tracking_csv_output .= "$pipe$key:$value";
					if ( !$pipe )
						$pipe = '|';
				}
				$shipment_tracking_csv_output .= ';';
			}
		}

		$new_order_data = array();

		if ( isset( $csv_generator->order_format ) && ( 'default_one_row_per_item' == $csv_generator->order_format || 'legacy_one_row_per_item' == $csv_generator->order_format ) ) {

			foreach ( $order_data as $data ) {
				$new_order_data[] = array_merge( (array) $data, array( 'shipment_tracking' => $shipment_tracking_csv_output ) );
			}

		} else {

			$new_order_data = array_merge( $order_data, array( 'shipment_tracking' => $shipment_tracking_csv_output ) );
		}

		return $new_order_data;
	}

	/**
	 * Prevents data being copied to subscription renewals
	 */
	public function woocommerce_subscriptions_renewal_order_meta_query( $order_meta_query, $original_order_id, $renewal_order_id, $new_order_role ) {
		$order_meta_query .= " AND `meta_key` NOT IN ( '_wc_shipment_tracking_items' )";

		return $order_meta_query;
	}

	/*
	 * Works out the final tracking provider and tracking link and appends then to the returned tracking item
	 *
	*/
	public function get_formatted_tracking_item( $order_id, $tracking_item ) {

		$formatted = array();

		$postcode = get_post_meta( $order_id, '_billing_postcode', true );

		$formatted[ 'formatted_tracking_provider' ] = '';
		$formatted[ 'formatted_tracking_link' ] = '';

		if ( $tracking_item[ 'custom_tracking_provider' ] ) {
			$formatted[ 'formatted_tracking_provider' ] = $tracking_item[ 'custom_tracking_provider' ];
			$formatted[ 'formatted_tracking_link' ] = $tracking_item[ 'custom_tracking_link' ];
		}
		else {

			$link_format = '';

			foreach ( $this->get_providers() as $providers ) {
				foreach ( $providers as $provider => $format ) {
					if ( sanitize_title( $provider ) == $tracking_item[ 'tracking_provider' ] ) {
						$link_format = $format;
						$formatted[ 'formatted_tracking_provider' ] = $provider;
						break;
					}
				}

				if ( $link_format ) {
					break;
				}

			}

			if ( $link_format ) {
				$formatted[ 'formatted_tracking_link' ] = sprintf( $link_format, $tracking_item[ 'tracking_number' ], urlencode( $postcode ) );
			}
		}

		return $formatted;

	}

	/*
	 * Deletes a tracking item from post_meta array
	 *
	*/
	public function delete_tracking_item( $order_id, $tracking_id ) {

		$tracking_items = $this->get_tracking_items( $order_id );

		if ( count( $tracking_items ) > 0 ) {
			foreach( $tracking_items as $key => $item ) {
				if ( $item[ 'tracking_id' ] == $tracking_id ) {
					unset( $tracking_items[ $key ] );
					break;
				}
			}
			$this->save_tracking_items( $order_id, $tracking_items );
		}
	}

	/*
	 * Adds a tracking item to the post_meta array
	 *
	*/
	public function add_tracking_item( $order_id, $args ) {

		$tracking_item = array();

		$tracking_item[ 'tracking_provider' ]        = wc_clean( $args[ 'tracking_provider' ] );
		$tracking_item[ 'custom_tracking_provider' ] = wc_clean( $args[ 'custom_tracking_provider' ] );
		$tracking_item[ 'custom_tracking_link' ]     = wc_clean( $args[ 'custom_tracking_link' ] );
		$tracking_item[ 'tracking_number' ]          = wc_clean( $args[ 'tracking_number' ] );
		$tracking_item[ 'date_shipped' ]             = wc_clean( strtotime( $args[ 'date_shipped' ] ) );

		if ( (int) $tracking_item[ 'date_shipped' ] == 0 ) {
			 $tracking_item[ 'date_shipped' ] = time();
		}

		if ( $tracking_item[ 'custom_tracking_provider' ] ) {
			$tracking_item[ 'tracking_id' ] = md5( "{$tracking_item[ 'custom_tracking_provider' ]}-{$tracking_item[ 'tracking_number' ]}" . microtime() );
		}
		else {
			$tracking_item[ 'tracking_id' ] = md5( "{$tracking_item[ 'tracking_provider' ]}-{$tracking_item[ 'tracking_number' ]}" . microtime() );
		}

		$tracking_items = $this->get_tracking_items( $order_id );

		$tracking_items[] = $tracking_item;

		$this->save_tracking_items( $order_id, $tracking_items );

		return $tracking_item;

	}

	/*
	 * Saves the tracking items array to post_meta
	 *
	*/
	public function save_tracking_items( $order_id, $tracking_items ) {
		update_post_meta( $order_id, '_wc_shipment_tracking_items', $tracking_items );
	}

	/*
	 * Gets a single tracking item from the post_meta array for an order
	 *
	*/
	public function get_tracking_item( $order_id, $tracking_id ) {
		$tracking_items = get_tracking_items( $order_id );

		if ( count( $tracking_items ) ) {
			foreach( $tracking_items as $item ) {
				if ( $item[ 'tracking_id' ] == $tracking_id ) {
					return $item;
				}
			}
		}
	}

	/*
	 * Gets all tracking itesm fron the post meta array for an order
	 *
	 * Accepts: $formatted: Wether or not to reslove the final tracking link and provider in the returned tracking item
	*/
	public function get_tracking_items( $order_id, $formatted = false ) {

		global $wpdb;

		// BW compatibility, check for old tracking numbers
		// Query DB directly to avoid triggering the 'get_post_metadata' filter added above

		
		// Temporarily remove filter so we can access old data
		remove_filter( 'get_post_metadata', array( $this, 'old_data_filter' ) );
		$old_tracking_number = get_post_meta( $order_id, '_tracking_number', true );
		add_filter( 'get_post_metadata', array( $this, 'old_data_filter' ), 10, 4 );

		if ( $old_tracking_number != '' ) {
			$this->convert_old_tracking_to_new( $order_id );
		}

		$tracking_items = get_post_meta( $order_id, '_wc_shipment_tracking_items', true );

		if ( is_array( $tracking_items ) ) {
			if ( $formatted ) {
				foreach( $tracking_items as &$item ) {
					$formatted_item = $this->get_formatted_tracking_item( $order_id, $item );
					$item = array_merge( $item, $formatted_item );
				}
			}
			return $tracking_items;
		}
		else {
			return array();
		}
	}

	/*
	 * Converts the older format for store tracking items to the new one
	 *
	*/
	public function convert_old_tracking_to_new( $order_id ) {

		global $wpdb;

		// Temporarily remove filter so we can access old data
		remove_filter( 'get_post_metadata', array( $this, 'old_data_filter' ) );

		// Query DB directly to avoid triggering the 'get_post_metadata' filter added above
		$tracking_provider        = get_post_meta( $order_id, '_tracking_provider', true );
		$custom_tracking_provider = get_post_meta( $order_id, '_custom_tracking_provider', true );
		$tracking_number          = get_post_meta( $order_id, '_tracking_number', true );
		$custom_tracking_link     = get_post_meta( $order_id, '_custom_tracking_link', true );
		$date_shipped             = get_post_meta( $order_id, '_date_shipped', true );

		if ( $date_shipped == '' ) {
			$date_shipped = time();
		}

		// re-add filter
		add_filter( 'get_post_metadata', array( $this, 'old_data_filter' ), 10, 4 );

		if ( strlen( $tracking_number ) > 0 ) {

			$args = array(
				'tracking_provider'        => $tracking_provider,
				'custom_tracking_provider' => $custom_tracking_provider,
				'custom_tracking_link'     => $custom_tracking_link,
				'tracking_number'          => $tracking_number,
				'date_shipped'             => date( 'Y-m-d', $date_shipped )
			);

			// DO this now or we will create a loop
			delete_post_meta( $order_id, '_tracking_number' );
			delete_post_meta( $order_id, '_tracking_provider' );
			delete_post_meta( $order_id, '_custom_tracking_provider' );
			delete_post_meta( $order_id, '_custom_tracking_link' );
			delete_post_meta( $order_id, '_date_shipped' );

			$this->add_tracking_item( $order_id, $args );
		}
	}

	/**
	* Gets the absolute plugin path without a trailing slash, e.g.
	* /path/to/wp-content/plugins/plugin-directory
	*
	* @return string plugin path
	*/
	public function get_plugin_path() {
		return $this->plugin_path = untrailingslashit( plugin_dir_path( dirname( __FILE__ ) ) );
	}
}
