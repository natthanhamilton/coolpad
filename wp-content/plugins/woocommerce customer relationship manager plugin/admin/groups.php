<?php
/**
 * Logic related to displaying Groups page.
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once( 'classes/wc_crm_customers_table.php' );
require_once( 'classes/wc_crm_email_handling.php' );

function wc_customer_relationship_manager_groups_add_options() {
	global $wc_crm_customers_table;
	$option = 'per_page';
	$args = array(
		'label' => __( 'Lists', 'wc_customer_relationship_manager' ),
		'default' => 20,
		'option' => 'groups_per_page'
	);
	add_screen_option( $option, $args );
	$wc_crm_customers_table = new WC_Crm_Customers_Table();
}

/**
 * Renders CRM page.
 */
function wc_customer_relationship_manager_render_groups_list_page() {
	global $wc_crm_customers_table;
	echo '<div class="wrap" id="wc-crm-page"><div class="icon32"><img src="' . plugins_url( 'assets/img/customers-icons.png', dirname( __FILE__ ) ) . '" width="29" height="29" /></div><h2>' . __( 'Customer Relationship Manager', 'wc_customer_relationship_manager' ) . '</h2>';
	?>
	<form method="post">
		<input type="hidden" name="page" value="wc-customer-relationship-manager">
		<?php
		if ( isset( $_POST['send'] ) && isset( $_POST['recipients'] ) && isset( $_POST['emaileditor'] ) && isset( $_POST['subject'] ) ) {
			WC_Crm_Email_Handling::process_form();
		} else if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'email' ) {
			WC_Crm_Email_Handling::display_form();
		} else {
			$wc_crm_customers_table->prepare_items();
			$wc_crm_customers_table->display();
		}
		?>
	</form></div>
<?php
}
