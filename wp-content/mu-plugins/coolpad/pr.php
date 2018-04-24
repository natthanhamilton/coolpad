<?php
/**
 * NEWS
 * Custom post type
 * Shortcode
 **/
function pr_CPT()
{
    register_post_type('pr', [
        'label'               => __('Press Releases'),
        'description'         => __('Custom list for internal PR news'),
        'labels'              => [
            'name'               => _x('PR', 'Post Type General Name'),
            'singular_name'      => _x('Press Release', 'Post Type Singular Name'),
            'menu_name'          => __('Press Releases'),
            'parent_item_colon'  => __('Parent PR'),
            'all_items'          => __('All Press Releases'),
            'view_item'          => __('View Press Releases'),
            'add_new_item'       => __('Add New Press Release'),
            'add_new'            => __('Add New'),
            'edit_item'          => __('Edit Press Release'),
            'update_item'        => __('Update Press Release'),
            'search_items'       => __('Search Press Releases'),
            'not_found'          => __('Not Found'),
            'not_found_in_trash' => __('Not found in Trash')
        ],
        'supports'            => [
            'title',
            'editor',
            'excerpt',
            'thumbnail',
            'revisions'
        ], // Features this CPT supports in Post Editor
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
    ]);
}

add_action('init', 'pr_CPT', 0);
add_shortcode('pr', 'pr_shortcode');
function pr_shortcode()
{
    $string = '<div class="grid">';
    $string .= '<div class="grid-sizer"></div>';
    $args   = [
        'post_type'   => 'pr',
        'post_status' => 'publish',
        'posts_per_page' => '8',
        'orderby', 'date',
        'order', 'DESC'
    ];
    $posts  = get_posts($args);
    foreach ($posts as $post) {
        $image = '';
        if (has_post_thumbnail($post->ID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
            $image = $image[0];
        }
        $string .= '<div class="grid-item">';
        $string .= '<a class="tile" href="' . get_permalink( $post->ID ) . '">';
        $string .= "<img class='img-responsive' src='".$image."'>";
        $string .= '<div>';
        $string .= "<h5 class='text-overflow'>".$post->post_title."</h5>";
        $string .= "<p class='small'>".$post->post_excerpt."</p>";
        $string .= '</div>';
        $string .= '</a>';
        $string .= '</div>';
    }
    $string .= '</div>';

    return $string;
}