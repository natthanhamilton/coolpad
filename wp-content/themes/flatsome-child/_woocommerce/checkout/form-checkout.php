<?php
/**
 * Checkout Form
 *
 * @author        WooThemes
 * @package       WooCommerce/Templates
 * @version       2.3.0
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
global $woocommerce, $flatsome_opt;
wc_print_notices();

if (get_current_blog_id() == 4) {
    ?>
<div class="row">
	<div class="large-12 columns" style="border: 3px solid #3b3fb6; padding: 15px; margin-bottom: 20px;">
		<span style="font-weight: bold; color: #3b3fb6">IMPORTANT:</span> Free shipping for orders over 100 UNITS
	</div>
</div>
<?php
}
do_action('woocommerce_before_checkout_form', $checkout);
?>

<?php
if (!$checkout->enable_signup && !$checkout->enable_guest_checkout && !is_user_logged_in()) {
    ?>
<div class="row">
	<div class="large-12 columns">

		<?php
		// If checkout registration is disabled and not logged in, the user cannot checkout
        echo "<p>New Customer? <a href='http://partners.coolpad.us/account'>Click Here to register</a></p>";
            echo apply_filters('woocommerce_checkout _must_be_logged_in_message',
                __('You must be logged in to checkout.', 'woocommerce'));

            ?>

            <!-- LOGIN -->
            <?php if (!defined('ABSPATH')) exit; // Exit if accessed directly
            if (is_user_logged_in() || !$checkout->enable_signup) {
            } else {
                $info_message = apply_filters('woocommerce_checkout_login_message',
                    __('Returning customer?', 'woocommerce'));
                ?>
                <?php if (in_array('nextend-facebook-connect/nextend-facebook-connect.php', apply_filters('active_plugins',
                        get_option('active_plugins'))) && $flatsome_opt['facebook_login_checkout']
                ) { ?>
                    <a href="<?php echo wp_login_url(); ?>?loginFacebook=1&redirect=<?php echo the_permalink(); ?>"
                       class="button medium facebook-button "
                       onclick="window.location = '<?php echo wp_login_url(); ?>?loginFacebook=1&redirect='+window.location.href; return false;"><i
                                class="icon-facebook"></i><?php _e('Login / Register with <strong>Facebook</strong>',
                            'flatsome'); ?></a>
                <?php }
            }?>
	</div><!-- .large-12 -->
</div>
<?php
} else {
    ?>

    <form name="checkout" method="post" class="checkout woocommerce-checkout"
          action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
        <div class="row">
            <div class="large-7 columns">
                <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                    <div class="checkout-group woo-billing">
                        <?php do_action('woocommerce_checkout_billing'); ?>
                    </div>

                    <div class="checkout-group woo-shipping">
                        <?php do_action('woocommerce_checkout_shipping'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                <?php endif; ?>
            </div>
            <div class="large-5 columns">
                <div class="order-review">

                    <h3 id="order_review_heading"><?php _e('Your order', 'woocommerce'); ?></h3>

                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>
            </div>
        </div>
    </form>

    <?php
}

do_action('woocommerce_after_checkout_form', $checkout); ?>
