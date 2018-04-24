<?php
/**
 * NEWS
 * Custom post type
 * Shortcode
 **/
function FAQ_CPT()
{
    register_post_type('faq', [
        'label' => __('FAQ'),
        'description' => __('List of FAQ articles'),
        'labels' => [
            'name' => _x('FAQ', 'Post Type General Name'),
            'singular_name' => _x('FAQ', 'Post Type Singular Name'),
            'menu_name' => __('FAQ'),
            'parent_item_colon' => __('Parent FAQ'),
            'all_items' => __('All FAQ'),
            'view_item' => __('View FAQ'),
            'add_new_item' => __('Add New FAQ'),
            'add_new' => __('Add New'),
            'edit_item' => __('Edit FAQ'),
            'update_item' => __('Update FAQ'),
            'search_items' => __('Search FAQ'),
            'not_found' => __('Not Found'),
            'not_found_in_trash' => __('Not found in Trash')
        ],
        'supports' => [
            'title',
            'editor',
            'revisions',
            'page-attributes'
        ], // Features this CPT supports in Post Editor
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'capability_type' => 'page',
    ]);
}

add_action('init', 'FAQ_CPT', 0);
add_shortcode('FAQ', 'FAQ_shortcode');

function FAQ_shortcode($atts)
{
    $faqID = get_posts( 'post_type=faq&pagename='.$atts['title'] );

    // Find the first level and proceed
    $pages = get_posts( 'post_type=faq&post_parent='.$faqID[0]->ID );

    // FAQ tabs
    $count = 0;
    $string = '<div class="FAQ">';
    $string .= '<div class="tabs-left faq-tabs">';
    $string .= '<ul class="nav nav-tabs">';
    foreach ($pages as $page) {
        $active = $count == 0 ? 'active' : '';

        $title_stripped = str_replace(' ', '-', strtolower($page->post_title));
        $string .= '<li class="' . $active . '"><a href="#' . $title_stripped . '" data-toggle="tab">' . $page->post_title . '</a></li>';
        $count++;
    }
    $string .= '</ul></div>';

    // FAQ sections
    $count = 0;
    $string .= '<div class="tab-content">';
    foreach ($pages as $page) {
        $active = $count == 0 ? 'active' : '';
        $title_stripped = str_replace(' ', '-', strtolower($page->post_title));

        $string .= '<div class="tab-pane '.$active.'" id="' . $title_stripped . '">';
        $string .= '<article class="desktop-titles">';
        $string .= '<h2 class="section-title">' . $page->post_title . '</h2>';
        $string .= '</article>';

        $pages = get_pages([
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order',
            'post_type' => 'faq',
            'post_parent' => 0,
            'parent' => $page->ID,
            'post_status' => 'publish',
        ]);

        foreach ($pages as $page) {
            $string .= '<article id="post-' . $page->ID . '" class="clearfix post-79 st_faq type-st_faq status-publish hentry">';
            $string .= '<h2 class="entry-title">';
            $string .= '<div class="action" style="margin-right: 10px;"><span class="plus">+</span><span class="minus">-</span></div>';
            $string .= '<a name="' . $page->ID . '">' . $page->post_title . '</a></h2>';
            $string .= '<div class="entry-content">' . apply_filters('the_content', $page->post_content) . '</div>';
            $string .= '</article>';
        }
        $string .= '</div>';
        $count++;
    }
    $string .= '</div>';

    return $string;
}