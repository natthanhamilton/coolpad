<?php
require_once(get_theme_root() . '/assets/global-functions.php');



add_filter( 'gadwp_backenditem_uri', 'gadwp_uri_correction', 10, 1 );
add_filter( 'gadwp_frontenditem_uri', 'gadwp_uri_correction', 10, 1 );

function gadwp_uri_correction( $uri ){
    return 'support.coolpad.us' . $uri;
}


add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

