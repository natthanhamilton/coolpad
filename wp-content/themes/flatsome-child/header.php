<!DOCTYPE html>
<?php global $woo_options, $woocommerce, $flatsome_opt; ?>
<!--[if lte IE 9 ]>
<html class="ie lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="profile" href="http://gmpg.org/xfn/11"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <?php wp_head(); ?>
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="all"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="all"
          href="<?php echo get_site_url() . '/wp-content/themes/flatsome-child/css/style.css'; ?>"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
</head>

<body <?php body_class(); ?>>

<?php
require_once(ABSPATH . 'wp-content/themes/assets/navigation.php');

// HTML Homepage Before Header // Set in Theme Option > HTML Blocks
if ($flatsome_opt['html_intro'] && is_front_page()) echo '<div class="home-intro">' . do_shortcode($flatsome_opt['html_intro']) . '</div>' ?>

<div id="wrapper"<?php if ($flatsome_opt['box_shadow']) echo ' class="box-shadow"'; ?>>
    <div id="main-content" class="site-main hfeed <?php echo $flatsome_opt['content_color']; ?>">
        <?php
        //adds a border line if header is white
        if (strpos($flatsome_opt['header_bg'], '#fff') !== FALSE && $flatsome_opt['nav_position'] == 'top') {
            echo '<div class="row"><div class="large-12 columns"><div class="top-divider"></div></div></div>';
        } ?>

        <?php if ($flatsome_opt['html_after_header']) {
            // AFTER HEADER HTML BLOCK
            echo '<div class="block-html-after-header" style="position:relative;top:-1px;">';
            echo do_shortcode($flatsome_opt['html_after_header']);
            echo '</div>';
        } ?>

        <!-- woocommerce message -->
<?php if (function_exists('wc_print_notices')) {
    wc_print_notices();
} ?>