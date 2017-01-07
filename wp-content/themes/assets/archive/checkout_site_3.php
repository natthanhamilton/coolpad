<?php
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
        <a class="top-level" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"
           title="<?php _e('My Account', 'woothemes'); ?>"><i class="fa fa-user" aria-hidden="true"></i></a>
    <?php } else { ?>
        <a class="top-level" href="<?php echo get_permalink(get_option('woocommerce_myaccount_page_id')); ?>"
           title="<?php _e('Login / Register', 'woothemes'); ?>"><i class="fa fa-user" aria-hidden="true"></i></a>
    <?php } ?>
</li>
<li>
    <a class="top-level" href="<?= 'http://store.coolpad.us/cart' ?>">
        <i class="fa fa-shopping-cart" aria-hidden="true"></i> <? echo ($quantity == 0) ? '' : $quantity ?>
    </a>
</li>