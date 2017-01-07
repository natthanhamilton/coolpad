<?php
/**
 * NEWS
 * Custom post type
 * Shortcode
 **/
function news_CPT()
{
    register_post_type('news', [
        'label' => __('news'),
        'description' => __('Custom list for external news'),
        'labels' => [
            'name' => _x('News', 'Post Type General Name'),
            'singular_name' => _x('News', 'Post Type Singular Name'),
            'menu_name' => __('News'),
            'parent_item_colon' => __('Parent News'),
            'all_items' => __('All News'),
            'view_item' => __('View News'),
            'add_new_item' => __('Add New News'),
            'add_new' => __('Add New'),
            'edit_item' => __('Edit News'),
            'update_item' => __('Update News'),
            'search_items' => __('Search News'),
            'not_found' => __('Not Found'),
            'not_found_in_trash' => __('Not found in Trash')
        ],
        'supports' => ['title', 'thumbnail'], // Features this CPT supports in Post Editor
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => false,
        'publicly_queryable' => true,
        'capability_type' => 'post',
    ]);
}

add_action('init', 'news_CPT', 0);
add_shortcode('news', 'news_shortcode');
function news_shortcode()
{
    $string = '';
    $args = [
        'post_type' => 'news',
        'post_status' => 'publish',
        'posts_per_page' => '6',
        'orderby', 'date',
        'order', 'DESC'
    ];
    $posts = get_posts($args);
    foreach ($posts as $post) {
        if (has_post_thumbnail($post->ID)) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');
        } else $image = get_post_meta($post->ID, "_news_img");
        $string .= '<div class="col-sm-4">';
        $string .= '<a href=' . htmlspecialchars(get_post_meta($post->ID, "_news_url", true)) . '>';
        $string .= "<div class='tile-title' style='background-image: url(" . $image[0] . ")'>";
        $string .= $post->post_title;
        $string .= '</div>';
        $string .= '</a>';
        $string .= '</div>';
    }

    return $string;
}

function add_news_metaboxes()
{
    add_meta_box('coolpad_news_meta', 'News article information', 'coolpad_news_meta', 'news', 'normal', 'default');
}

function coolpad_news_meta()
{
    global $post;

    // Noncename needed to verify where the data originated
    echo '<input type="hidden" name="newsmeta_noncename" id="newsmeta_noncename" value="' .
        wp_create_nonce(plugin_basename(__FILE__)) . '" />';

    // Get the location data if its already been entered
    $url = get_post_meta($post->ID, '_news_url', true);
    $img = get_post_meta($post->ID, '_news_img', true);

    if (empty($url)) $url = 'http://';

    // Echo out the field
    echo '<p>News article URL:</p>';
    echo '<input type="text" name="_news_url" value="' . $url . '" class="widefat" placeholder="http://" />';
    echo '<p>News article image:</p>';
    echo '<input type="text" name="_news_img" value="' . $img . '" class="widefat" />';

}

function coolpad_news_save_meta($post_id, $post)
{

    // verify this came from the our screen and with proper authorization,
    // because save_post can be triggered at other times
    if (!wp_verify_nonce($_POST['newsmeta_noncename'], plugin_basename(__FILE__)))
        return $post->ID;

    // Is the user allowed to edit the post or page?
    if (!current_user_can('edit_post', $post->ID))
        return $post->ID;

    // OK, we're authenticated: we need to find and save the data
    // We'll put it into an array to make it easier to loop though.

    $events_meta['_news_url'] = $_POST['_news_url'];
    $events_meta['_news_img'] = $_POST['_news_img'];

    // Add values of $events_meta as custom fields

    foreach ($events_meta as $key => $value) { // Cycle through the $events_meta array!
        if ($post->post_type == 'revision')
            return; // Don't store custom data twice

        $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)

        if (get_post_meta($post->ID, $key, FALSE)) // If the custom field already has a value
            update_post_meta($post->ID, $key, $value);
        else // If the custom field doesn't have a value
            add_post_meta($post->ID, $key, $value);

        if (!$value) delete_post_meta($post->ID, $key); // Delete if blank
    }

}

add_action('add_meta_boxes', 'add_news_metaboxes');
add_action('save_post', 'coolpad_news_save_meta', 1, 2); // save the custom fields