<?php

/*
 *
 * Hijack user registration
 * Set custom role
 * Register to all multisites
 * Disable strong PW
 *
 */
add_action('wp_print_scripts', 'DisableStrongPW', 100);
function DisableStrongPW()
{
    if (wp_script_is('wc-password-strength-meter', 'enqueued')) {
        wp_dequeue_script('wc-password-strength-meter');
    }
}
function add_custom_role( $user_id ){

    foreach( custom_get_blog_list( 0, 'all' ) as $key => $blog ) {

        if( is_user_member_of_blog( $user_id, $blog[ 'blog_id' ] ) )
            continue;

        switch_to_blog( $blog[ 'blog_id' ] );

        $role = 'customer'; // Set role here

        if( $role != 'none' )
            add_user_to_blog( $blog[ 'blog_id' ], $user_id, $role );

        restore_current_blog();
    }
    update_user_meta( $user_id, 'msum_has_caps', 'true' );
}
add_action( 'wpmu_activate_user', 'add_custom_role', 10, 1 );
add_action( 'wpmu_new_user', 'add_custom_role', 10, 1 );
add_action( 'user_register', 'add_custom_role', 10, 1 );



function custom_get_blog_list( $start = 0, $num = 3 ) {
    global $wpdb;

    $blogs = $wpdb->get_results( $wpdb->prepare( "SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND archived = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC", $wpdb->siteid ), ARRAY_A );

    foreach ( (array) $blogs as $details ) {
        $blog_list[ $details[ 'blog_id' ] ] = $details;
        $blog_list[ $details[ 'blog_id' ] ]['postcount'] = $wpdb->get_var( "SELECT COUNT(ID) FROM " . $wpdb->get_blog_prefix( $details['blog_id'] ). "posts WHERE post_status='publish' AND post_type='post'" );
    }
    unset( $blogs );
    $blogs = $blog_list;

    if ( false == is_array( $blogs ) )
        return array();

    if ( $num == 'all' )
        return array_slice( $blogs, $start, count( $blogs ) );
    else
        return array_slice( $blogs, $start, $num );
}
