<?php
/**
 * Class for E-mail handling.
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Crm_Customer_Details {

	private static $billing_fields;
	private static $shipping_fields;

	private static $user_data;
	private static $user_id;
	private static $last_order;
	private static $order;
	private static $order_id;
	private static $messages;
	private static $error;


	/**
	 * Displays content.
	 */
	public static function display() {
		if( isset($_GET['userid']) && !empty($_GET['userid']) ){
			if(get_userdata( $_GET['userid'] )){
				self::$user_id = $_GET['userid'];
				self::$user_data = get_userdata( $_GET['userid'] );
			}
		}
		self::$last_order = new WP_Query(array(
																'numberposts' => 1,
																'meta_key' => '_customer_user',
	         											'meta_value' => self::$user_id,
	         											'post_type' => 'shop_order',
	       												'post_status' => 'publish',
															));
		self::$order_id  = self::$last_order->posts[0]->ID;
		self::$order  = new WC_Order( self::$order_id );
	?>
	<div class="wrap">
		<?php if( !empty(self::$user_id) ): ?>
			<h2><?php _e('Edit Customer ', 'wc_customer_relationship_manager'); ?><a class="add-new-h2" href="admin.php?page=wc_new_customer"><?php _e('Add New Customer', 'wc_customer_relationship_manager'); ?></a></h2>
		<?php else: ?>
			<h2><?php _e('Add New Customer', 'wc_customer_relationship_manager'); ?></h2>
		<?php endif; ?>
		<?php if( !empty(self::$messages) ):
			echo '<div class="updated below-h2" id="message"  style="display: block;">';
			foreach (self::$messages as $value) {
				echo '<p>'.$value.'</p>';
			}
			echo '</div>';
			endif;
			if( !empty(self::$error) ):
			echo '<div class="error below-h2" style="display: block;">';
			foreach (self::$error as $value) {
				echo '<p>'.$value.'</p>';
			}
			echo '</div>';
			endif; ?>
		<form id="wc_crm_edit_customer_form" method="post">
			<input type="hidden" id="customer_user" name="customer_user" value="<?php echo self::$user_id; ?>">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="postbox-container-1" class="postbox-container">
						<div id="side-sortables" class="meta-box-sortables">
							<div class="postbox " id="woocommerce-order-actions" style="display: block;">
								<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Customer Actions</span></h3>
								<div class="inside">
										<ul class="order_actions submitbox">
											<li id="actions" class="wide">
												<select name="wc_crm_customer_action" id="wc_crm_customer_action">
													<option value="">Actions</option>
													<option value="wc_crm_customer_action_new_order">New order</option>
													<option value="wc_crm_customer_action_send_email">Send email</option>
													<option value="wc_crm_customer_action_phone_call">Add a new call</option>
												</select>
												<button title="Apply" class="button wc-reload wc_crm_new_action"><span>Apply</span></button>
												<a href="" class="wc_crm_new_action_href" target="_blank" style="display: none;">_</a>
											</li>

											<li class="wide">
												<input type="submit" value="Save Customer" name="save" style="float: right;" class="button save_customer button-primary wc_crm_new_action">
											</li>
										</ul>
										</div>
								</div>
								<?php if( !empty(self::$user_id) ): ?>
									<div class="postbox " id="woocommerce-customer-notes">
										<div title="Click to toggle" class="handlediv"><br></div><h3 class="hndle"><span>Customer Notes</span></h3>
										<div class="inside" style="margin:0px; padding:0px;">
										<ul class="order_notes">
											<?php  $notes = self::get_customer_notes(); ?>
													<?php if ( $notes ) {
																	foreach( $notes as $note ) {
																		?>
																		<li style="padding: 0 10px;"rel="<?php echo absint( $note->comment_ID ) ; ?>">
																			<div class="note_content">
																				<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
																			</div>
																			<p class="meta">
																				<abbr class="exact-date" title="<?php echo $note->comment_date_gmt; ?> GMT"><?php printf( __( 'added %s ago', 'wc_customer_relationship_manager' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?></abbr>
																				<?php if ( $note->comment_author !== __( 'WooCommerce', 'wc_customer_relationship_manager' ) ) printf( ' ' . __( 'by %s', 'wc_customer_relationship_manager' ), $note->comment_author ); ?>
																				<a href="#" class="delete_customer_note"><?php _e( 'Delete note', 'wc_customer_relationship_manager' ); ?></a>
																			</p>
																		</li>
																		<?php
																	}
																} else {
																	echo '<li>' . __( 'There are no notes for this customer yet.', 'wc_customer_relationship_manager' ) . '</li>';
																} ?>
														</ul>
														<div class="add_note">
															<h4>Add note</h4>
													<p>
														<textarea rows="5" cols="20" class="input-text" id="add_order_note" name="order_note" type="text"></textarea>
													</p>
													<p>
														<a class="add_note_customer button" href="#">Add</a>
													</p>
												</div>
												</div>
										</div>
								<?php endif; ?>
						</div>
					</div>
					<div id="postbox-container-2" class="postbox-container">
						<div id="normal-sortables" class="meta-box-sortables">
							<div id="woocommerce-customer-detail" class="postbox ">
								<div class="inside" style="margin:0px; padding:0px;">
									<?php self::get_customer_detail(); ?>
								</div>
							</div>
							<?php if( !empty(self::$user_id) ): ?>
								<div id="woocommerce-customer-orders" class="postbox ">
									<div title="Click to toggle" class="handlediv"><br></div>
									<h3 class="hndle"><span>Customer Orders</span></h3>
									<div class="inside" style="margin:0px; padding:0px;">
										<?php self::get_customer_orders(); ?>
									</div>
								</div>
								<div id="woocommerce-customer-activity" class="postbox ">
									<div title="Click to toggle" class="handlediv"><br></div>
									<h3 class="hndle"><span>Activity</span></h3>
									<div class="inside" style="margin:0px; padding:0px;">
										<?php self::get_customer_activity(); ?>
									</div>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<?php
	}

	public static function init_address_fields() {
		self::$billing_fields = apply_filters( 'woocommerce_admin_billing_fields', array(
			'first_name' => array(
				'label' => __( 'First Name', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'last_name' => array(
				'label' => __( 'Last Name', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'company' => array(
				'label' => __( 'Company', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'address_1' => array(
				'label' => __( 'Address 1', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'address_2' => array(
				'label' => __( 'Address 2', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'city' => array(
				'label' => __( 'City', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'postcode' => array(
				'label' => __( 'Postcode', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'country' => array(
				'label' => __( 'Country', 'wc_customer_relationship_manager' ),
				'show'	=> false,
				'type'	=> 'select',
				'options' => array( '' => __( 'Select a country&hellip;', 'wc_customer_relationship_manager' ) ) + WC()->countries->get_allowed_countries()
				),
			'state' => array(
				'label' => __( 'State/County', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'email' => array(
				'label' => __( 'Email', 'wc_customer_relationship_manager' ),
				),
			'phone' => array(
				'label' => __( 'Phone', 'wc_customer_relationship_manager' ),
				),
		) );

		self::$shipping_fields = apply_filters( 'woocommerce_admin_shipping_fields', array(
			'first_name' => array(
				'label' => __( 'First Name', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'last_name' => array(
				'label' => __( 'Last Name', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'company' => array(
				'label' => __( 'Company', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'address_1' => array(
				'label' => __( 'Address 1', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'address_2' => array(
				'label' => __( 'Address 2', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'city' => array(
				'label' => __( 'City', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'postcode' => array(
				'label' => __( 'Postcode', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
			'country' => array(
				'label' => __( 'Country', 'wc_customer_relationship_manager' ),
				'show'	=> false,
				'type'	=> 'select',
				'options' => array( '' => __( 'Select a country&hellip;', 'wc_customer_relationship_manager' ) ) + WC()->countries->get_shipping_countries()
				),
			'state' => array(
				'label' => __( 'State/County', 'wc_customer_relationship_manager' ),
				'show'	=> false
				),
		) );
	}
/**
	 * Get customer detail
	 */
	public function get_customer_detail() {
			self::init_address_fields();
			?>
			<div class="panel-wrap woocommerce" id="customer_data">
				<div id="order_data" class="panel">

					<h2><?php _e( 'Customer Details', 'wc_customer_relationship_manager' ); ?></h2>
					<?php if( !empty(self::$user_id) ): ?>
					<p class="order_number"><?php
						echo __( 'Customer number', 'wc_customer_relationship_manager' ) . ' #' . self::$user_id . '. ';
					?></p>
				<?php endif; ?>

					<div class="order_data_column_container">
						<div class="order_data_column">
							<h4><?php _e( 'General Details', 'wc_customer_relationship_manager' ); ?></h4>


							<p class="form-field form-field-wide"><label for="customer_status"><?php _e( 'Customer status:', 'wc_customer_relationship_manager' ) ?></label>
							<select id="customer_status" name="customer_status" class="chosen_select">
								<?php
										$selected = get_the_author_meta( 'customer_status', self::$user_id );
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
							</select></p>

							<p class="form-field form-field-wide">
							<label for="customer_role"><?php _e( 'Role:', 'wc_customer_relationship_manager' ) ?></label>
							<select id="customer_role" name="customer_role" class="chosen_select">
								<?php
									global $wp_roles;
									foreach ( $wp_roles->role_names as $role => $name ) {
										echo '<option value="' . esc_attr( $role ) . '" ' . selected( $role, self::$user_data->roles[0], false ) . '>' . esc_html__( $name, 'wc_customer_relationship_manager' ) . '</option>';
									}
								?>
							</select></p>

							<p class="form-field form-field-wide"><label for="date_of_birth">Date of Birth:</label>
								<input type="text" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])"  value="<?php echo get_the_author_meta( 'date_of_birth', self::$user_id ); ?>"  maxlength="10" id="date_of_birth" name="date_of_birth">
							</p>

							<p class="form-field form-field-wide">
								<label for="customer_site">Website:</label>
								<input type="text" id="customer_site" name="customer_site"  value="<?php echo get_the_author_meta( 'url', self::$user_id ); ?>" >
							</p>

							<p class="form-field form-field-wide">
								<label for="customer_twitter">Twitter:</label>
								<input type="text" id="customer_twitter" name="customer_twitter"  value="@<?php echo get_the_author_meta( 'twitter', self::$user_id ); ?>" >
							</p>

							<p class="form-field form-field-wide">
								<label for="customer_skype">Skype:</label>
								<input type="text" id="customer_skype" name="customer_skype" value="<?php echo get_the_author_meta( 'skype', self::$user_id ); ?>" >
							</p>
						</div>
						<div class="order_data_column">
							<h4><?php _e( 'Billing Details', 'wc_customer_relationship_manager' ); ?> <a class="edit_address" href="#" <?php echo ( ( empty(self::$user_id) ) ? 'style="display: none;"' : ''); ?> ><img src="<?php echo WC()->plugin_url(); ?>/assets/images/icons/edit.png" alt="Edit" width="14" /></a></h4>
							<?php
								if( !empty(self::$user_id) ):
									// Display values
									echo '<div class="address">';

										if ( self::get_formatted_billing_address()  )
											echo '<p><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong>' . wp_kses( self::get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
										else
											echo '<p class="none_set"><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong> ' . __( 'No billing address set.', 'wc_customer_relationship_manager' ) . '</p>';

										foreach ( self::$billing_fields as $key => $field ) {
											if ( isset( $field['show'] ) && $field['show'] === false )
												continue;

											$field_name = 'billing_' . $key;
											$field_value = get_the_author_meta( $field_name , self::$user_id );

											if ( !empty($field_value) )
												echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . make_clickable( esc_html( $field_value ) ) . '</p>';
										}
										if ( WC()->payment_gateways() )
											$payment_gateways = WC()->payment_gateways->payment_gateways();

										$payment_method = ! empty( $order->payment_method ) ? $order->payment_method : '';

										if ( $payment_method )
											echo '<p><strong>' . __( 'Preferred Payment Method', 'wc_customer_relationship_manager' ) . ':</strong> ' . ( isset( $payment_gateways[ $payment_method ] ) ? esc_html( $payment_gateways[ $payment_method ]->get_title() ) : esc_html( $payment_method ) ) . '</p>';

									echo '</div>';
								endif;
								// Display form
								echo '<div class="edit_address" ' . ( ( empty(self::$user_id) ) ? 'style="display: block;"' : '') . '>';

								foreach ( self::$billing_fields as $key => $field ) {
									if ( ! isset( $field['type'] ) )
										$field['type'] = 'text';
									switch ( $field['type'] ) {
										case "select" :
											woocommerce_wp_select( array( 'id' => '_billing_' . $key, 'label' => $field['label'], 'options' => $field['options'], 'value' => get_the_author_meta( 'billing_'.$key, self::$user_id ) ) );
										break;
										default :
											woocommerce_wp_text_input( array( 'id' => '_billing_' . $key, 'label' => $field['label'], 'value' => get_the_author_meta( 'billing_'.$key, self::$user_id ) ) );
										break;
									}
								}

								?>
								<p class="form-field form-field-wide">
									<label><?php _e( 'Payment Method:', 'wc_customer_relationship_manager' ); ?></label>
									<select name="_payment_method" id="_payment_method" class="first">
										<option value=""><?php _e( 'N/A', 'wc_customer_relationship_manager' ); ?></option>
										<?php
											$found_method 	= false;

											foreach ( $payment_gateways as $gateway ) {
												if ( $gateway->enabled == "yes" ) {
													echo '<option value="' . esc_attr( $gateway->id ) . '" ' . selected( $payment_method, $gateway->id, false ) . '>' . esc_html( $gateway->get_title() ) . '</option>';
													if ( $payment_method == $gateway->id )
														$found_method = true;
												}
											}

											if ( ! $found_method && ! empty( $payment_method ) ) {
												echo '<option value="' . esc_attr( $payment_method ) . '" selected="selected">' . __( 'Other', 'wc_customer_relationship_manager' ) . '</option>';
											} else {
												echo '<option value="other">' . __( 'Other', 'wc_customer_relationship_manager' ) . '</option>';
											}
										?>
									</select>
								</p>
								<?php

								echo '</div>';

								do_action( 'woocommerce_admin_order_data_after_billing_address', $order );
							?>
						</div>
						<div class="order_data_column">

							<h4><?php _e( 'Shipping Details', 'wc_customer_relationship_manager' ); ?> <a class="edit_address" href="#" <?php echo ( ( empty(self::$user_id) ) ? 'style="display: none;"' : ''); ?> ><img src="<?php echo WC()->plugin_url(); ?>/assets/images/icons/edit.png" alt="Edit" width="14" /></a></h4>
							<?php
								if( !empty(self::$user_id) ):
										// Display values
										echo '<div class="address">';

											if ( self::get_formatted_shipping_address() )
												echo '<p><strong>' . __( 'Address', 'wc_customer_relationship_manager' ). ':</strong>'. wp_kses( self::get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
											else
												echo '<p class="none_set"><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong> ' . __( 'No shipping address set.', 'wc_customer_relationship_manager' ) . '</p>';

											if ( self::$shipping_fields ) foreach ( self::$shipping_fields as $key => $field ) {
												if ( isset( $field['show'] ) && $field['show'] === false )
													continue;

												$field_name = 'shipping_' . $key;
												$field_value = get_the_author_meta( $field_name , self::$user_id );

												if ( ! empty( $order->$field_name ) )
													echo '<p><strong>' . esc_html( $field['label'] ) . ':</strong> ' . make_clickable( esc_html( $field_value ) ) . '</p>';
											}

										echo '</div>';
								endif;

								// Display form
								echo '<div class="edit_address" ' . ( ( empty(self::$user_id) ) ? 'style="display: block;"' : '') . '><p><button class="button billing-same-as-shipping">'. __( 'Copy from billing', 'wc_customer_relationship_manager' ) . '</button></p>';

								if ( self::$shipping_fields ) foreach ( self::$shipping_fields as $key => $field ) {
									if ( ! isset( $field['type'] ) )
										$field['type'] = 'text';
									switch ( $field['type'] ) {
										case "select" :
											woocommerce_wp_select( array( 'id' => '_shipping_' . $key, 'label' => $field['label'], 'options' => $field['options'], 'value' => get_the_author_meta( 'shipping_'.$key, self::$user_id ) ) );
										break;
										default :
											woocommerce_wp_text_input( array( 'id' => '_shipping_' . $key, 'label' => $field['label'], 'value' => get_the_author_meta( 'shipping_'.$key, self::$user_id ) ) );
										break;
									}
								}

								echo '</div>';

								do_action( 'woocommerce_admin_order_data_after_shipping_address', $order );
							?>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
			<?php


	}
	public function	get_formatted_billing_address() {
		$address = array();
		foreach ( self::$billing_fields as $key => $field ) {
			$address[$key] = get_the_author_meta( 'billing_'.$key, self::$user_id );
		}

		$formatted_address = WC()->countries->get_formatted_address( $address );
		return $formatted_address;
	}
	public function	get_formatted_shipping_address() {
		$address = array();
		foreach ( self::$shipping_fields as $key => $field ) {
			$address[$key] = get_the_author_meta( 'shipping_'.$key, self::$user_id );
		}

		$formatted_address = WC()->countries->get_formatted_address( $address );
		return $formatted_address;
	}
	public function get_customer_orders(){
			require_once( 'wc_crm_order_list.php');
			$wc_crm_order_list = new WC_Crm_Order_List();
			$wc_crm_order_list->prepare_items();
			$wc_crm_order_list->display();
	}
	public function get_customer_activity(){
		require_once( 'wc_crm_logs.php' );
		$logs = new WC_Crm_Logs();
		$logs->prepare_items();
		$logs->display();
		?>
			<!-- <iframe src="<?php echo get_admin_url(); ?>admin.php?page=wc_crm_logs&order_id=<?php echo self::$order_id; ?>&iframe=true" frameborder="0" style="width: 100%;"></iframe> -->
		<?
	}

	public static function save( $user_id, $new=false) {

		if ( !empty($user_id ) ) {

			update_usermeta( $user_id, 'customer_status', $_POST['customer_status'] );

			wp_update_user(array(
				'ID' => $user_id,
				'role' => $_POST['customer_role'],
				'user_url' => $_POST['customer_site']
				));

			update_usermeta( $user_id, 'date_of_birth', $_POST['date_of_birth'] );
			update_usermeta( $user_id, 'twitter', str_replace('@', '', $_POST['customer_twitter']) );
			update_usermeta( $user_id, 'skype', $_POST['customer_skype'] );

			update_usermeta( $user_id, 'first_name', $_POST['_billing_first_name'] );
			update_usermeta( $user_id, 'last_name', $_POST['_billing_last_name'] );

			update_usermeta( $user_id, 'billing_first_name', $_POST['_billing_first_name'] );
			update_usermeta( $user_id, 'billing_last_name', $_POST['_billing_last_name'] );
			update_usermeta( $user_id, 'billing_company', $_POST['_billing_company'] );
			update_usermeta( $user_id, 'billing_address_1', $_POST['_billing_address_1'] );
			update_usermeta( $user_id, 'billing_address_2', $_POST['_billing_address_2'] );
			update_usermeta( $user_id, 'billing_city', $_POST['_billing_city'] );
			update_usermeta( $user_id, 'billing_postcode', $_POST['_billing_postcode'] );
			update_usermeta( $user_id, 'billing_country', $_POST['_billing_country'] );
			update_usermeta( $user_id, 'billing_state', $_POST['_billing_state'] );
			update_usermeta( $user_id, 'billing_email', $_POST['_billing_email'] );
			update_usermeta( $user_id, 'billing_phone', $_POST['_billing_phone'] );
			update_usermeta( $user_id, 'payment_method', $_POST['_payment_method'] );
			update_usermeta( $user_id, 'shipping_first_name', $_POST['_shipping_first_name'] );
			update_usermeta( $user_id, 'shipping_last_name', $_POST['_shipping_last_name'] );
			update_usermeta( $user_id, 'shipping_company', $_POST['_shipping_company'] );
			update_usermeta( $user_id, 'shipping_address_1', $_POST['_shipping_address_1'] );
			update_usermeta( $user_id, 'shipping_address_2', $_POST['_shipping_address_2'] );
			update_usermeta( $user_id, 'shipping_city', $_POST['_shipping_city'] );
			update_usermeta( $user_id, 'shipping_postcode', $_POST['_shipping_postcode'] );
			update_usermeta( $user_id, 'shipping_country', $_POST['_shipping_country'] );
			update_usermeta( $user_id, 'shipping_state', $_POST['_shipping_state'] );
			if($new)
				self::$messages[] = __('Customer updated. <a href="admin.php?page=wc_new_customer&userid='.$user_id.'">Edit</a>');
			else
				self::$messages[] = __('Customer updated.');
		}
	}
	public static function create_user() {
			extract($_POST);
			if( empty($_billing_email)){
				self::$error[] = __('<p><strong>ERROR</strong>: The email address isn’t correct.</p>');
			}else	if ( !email_exists( $_billing_email ) ) {
				$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
				$user_id = wp_create_user( $_billing_email, $random_password, $_billing_email );
				self::save($user_id, true);
			} else {
				self::$error[] = __('<p><strong>ERROR</strong>: User already exists.</p>');
			}
	}

		public function add_order_note( $note, $customer_id = 0) {

		if ( is_user_logged_in() && current_user_can( 'manage_woocommerce' ) ) {
			$user                 = get_user_by( 'id', get_current_user_id() );
			$comment_author       = $user->display_name;
			$comment_author_email = $user->user_email;
		} else {
			$comment_author       = __( 'WC_CRM', 'wc_customer_relationship_manager' );
			$comment_author_email = strtolower( __( 'WC_CRM', 'wc_customer_relationship_manager' ) ) . '@';
			$comment_author_email .= isset( $_SERVER['HTTP_HOST'] ) ? str_replace( 'www.', '', $_SERVER['HTTP_HOST'] ) : 'noreply.com';
			$comment_author_email = sanitize_email( $comment_author_email );
		}

		$comment_post_ID 		= 0;
		$comment_author_url 	= '';
		$comment_content 		= $note;
		$comment_agent			= 'WC_CRM';
		$comment_type			= 'customer_note';
		$comment_parent			= 0;
		$comment_approved 		= 1;
		$commentdata 			= apply_filters( 'cw_crm_new_customer_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ));

		$comment_id = wp_insert_comment( $commentdata );

		add_comment_meta( $comment_id, 'customer_id', $customer_id );

		return $comment_id;
	}
	/**
	 * Last customer notes (public)
	 *
	 * @access public
	 * @return string
	 */
	public function get_last_customer_note($userId){
		global $woocommerce, $post;
		self::$user_id = $userId;
		$notes = 'No Customer Notes';
		$notes_array = self::get_customer_notes();
		$count_notes = count($notes_array);
		#print_R($notes_array);
		#ie;
		if( $count_notes == 0 ) return $notes;
		$count_notes--;
		if($count_notes == 0)
			$notes = esc_attr($notes_array[0]->comment_content);
		elseif($count_notes == 1)
			$notes = esc_attr($notes_array[0]->comment_content . '<small style="display:block">plus ' . $count_notes . ' other note</small>');
		else
			$notes = esc_attr($notes_array[0]->comment_content . '<small style="display:block">plus ' . $count_notes . ' other notes</small>');
		return $notes;
	}
	/**
	 * List customer notes (public)
	 *
	 * @access public
	 * @return array
	 */
	public function get_customer_notes() {
		global $woocommerce, $post;
		$notes = array();

		$args = array(
			'post_id' 	=> 0,
			'approve' 	=> 'approve',
			'type' 		=> 'customer_note'
		);
		remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

		$comments = get_comments();

		add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );

		#print_r($comments);

		foreach ( $comments as $comment ) {
			$customer_id = get_comment_meta( $comment->comment_ID, 'customer_id', true );
			$comment->comment_content = make_clickable( $comment->comment_content );
			if ( $customer_id == self::$user_id) {
				$notes[] = $comment;
			}
		}

		return (array) $notes;

	}
	/**
	 * List customer notes (public)
	 *
	 * @access public
	 * @return array
	 */
	public function display_notes($id) {
		self::$user_id = $id;
		?>
		<style>
	    #adminmenuwrap,
	    #screen-meta,
	    #screen-meta-links,
	    #adminmenuback,
	    #wpfooter,
	    #wpadminbar{
	      display: none !important;
	    }
	    #wpbody-content{
	    	padding: 0;
	    }
	    html{
	      padding-top: 0 !important;
	    }
	    #wpcontent{
	      margin: 0 !important;
	    }
	    #wc-crm-page{
	      margin: 15px !important;
	    }
    </style>
    <input type="hidden" id="customer_user" name="customer_user" value="<?php echo self::$user_id; ?>">
		<div id="side-sortables" class="meta-box-sortables">
				<div class="postbox " id="woocommerce-customer-notes">
						<div class="inside">
						<ul class="order_notes">
							<?php  $notes = self::get_customer_notes(); ?>
									<?php if ( $notes ) {
													foreach( $notes as $note ) {
														?>
														<li rel="<?php echo absint( $note->comment_ID ) ; ?>">
															<div class="note_content">
																<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
															</div>
															<p class="meta">
																<abbr class="exact-date" title="<?php echo $note->comment_date_gmt; ?> GMT"><?php printf( __( 'added %s ago', 'wc_customer_relationship_manager' ), human_time_diff( strtotime( $note->comment_date_gmt ), current_time( 'timestamp', 1 ) ) ); ?></abbr>
																<?php if ( $note->comment_author !== __( 'WooCommerce', 'wc_customer_relationship_manager' ) ) printf( ' ' . __( 'by %s', 'wc_customer_relationship_manager' ), $note->comment_author ); ?>
																<a href="#" class="delete_customer_note"><?php _e( 'Delete note', 'wc_customer_relationship_manager' ); ?></a>
															</p>
														</li>
														<?php
													}
												} else {
													echo '<li>' . __( 'There are no notes for this customer yet.', 'wc_customer_relationship_manager' ) . '</li>';
												} ?>
										</ul>
										<div class="add_note">
											<h4>Add note</h4>
									<p>
										<textarea rows="5" cols="20" class="input-text" id="add_order_note" name="order_note" type="text"></textarea>
									</p>
									<p>
										<a class="add_note_customer button" href="#">Add</a>
									</p>
								</div>
								</div>
						</div>
		</div>
		<?php
	}
}