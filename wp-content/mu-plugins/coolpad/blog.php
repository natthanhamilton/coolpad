<?php
/**
 * NEWS
 * Custom post type
 * Shortcode
 **/
function blog_CPT()
{
    register_post_type('blog', [
        'label'               => __('Blogs'),
        'description'         => __('List of blog articles'),
        'labels'              => [
            'name'               => _x('Blogs', 'Post Type General Name'),
            'singular_name'      => _x('Blog', 'Post Type Singular Name'),
            'menu_name'          => __('Blogs'),
            'parent_item_colon'  => __('Parent Blogs'),
            'all_items'          => __('All Blogs'),
            'view_item'          => __('View Blog'),
            'add_new_item'       => __('Add New Blog'),
            'add_new'            => __('Add New'),
            'edit_item'          => __('Edit Blog'),
            'update_item'        => __('Update Blog'),
            'search_items'       => __('Search Blogs'),
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

add_action('init', 'blog_CPT', 0);
add_shortcode('blog', 'blog_shortcode');
function blog_shortcode()
{
    $string = '';
    $args   = [
        'post_type'   => 'blog',
        'post_status' => 'publish'
    ];
    $posts  = get_posts($args);
    foreach ($posts as $post) {
        if (has_post_thumbnail($post->ID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
            $image = $image[0];
        } else {
            $image = '';
        }
        $string .= '<div class="col-sm-4">';
        $string .= '<a href="' . get_permalink( $post->ID ) . '">';
        $string .= "<div class='tile-title' style='background-image: url(" . $image . "')>";
        $string .= $post->post_title;
        $string .= '</div>';
        $string .= '</a>';
        $string .= '</div>';
    }

    return $string;
}