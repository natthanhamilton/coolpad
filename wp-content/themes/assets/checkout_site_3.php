<?php
/*
global $woo_options, $woocommerce;
$cart     = get_user_meta(get_current_user_id(), '_woocommerce_persistent_cart', TRUE);
$quantity = '';
if (!empty($cart['cart'])) {
$session_id = [];
foreach ($cart['cart'] as $k => $v) $session_id[] = $k;
foreach ($session_id as $id) $quantity += $cart['cart'][ $id ]['quantity'];
}
if (empty($quantity)) $quantity = '0';
?>
<li>
	<?php if (is_user_logged_in()) { ?>
		<a style="padding-right: 0" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"
		   title="<?php _e('My Account', 'woothemes'); ?>"><?php _e('My Account', 'woothemes'); ?></a>
	<?php } else { ?>
		<a href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"
		   title="<?php _e('Login / Register', 'woothemes'); ?>"><?php _e('Login / Register',
		                                                                  'woothemes'); ?></a>
	<?php } ?>
</li>
<li id="cart-icon">
	<a style="padding-left: 0" href="<?= 'http://store.coolpad.us/cart' ?>">
		<div class="cart">
			<strong><?= $quantity ?></strong>
			<span class="handle"></span>
		</div>
	</a>
</li>
<?php */ ?>