<?php
/**
 * Enqueues scripts for front-end.
 */

function st_enqueue_scripts()
{

    /*
    * Load our main theme JavaScript file
    */
    wp_enqueue_script('st_theme_custom', get_template_directory_uri() . '/js/functions.js', array('jquery'), FALSE, TRUE);

    /*
    * Adds JavaScript for Live Search
    */
    if (of_get_option('st_live_search') == '0')
    {
        wp_enqueue_script('live_search', get_template_directory_uri() . '/js/jquery.livesearch.js', array('jquery'), FALSE, TRUE);
    }

    /*
    * Adds JavaScript for shortcodes
    * (will be mvoed to plugin soon)
    */
    wp_enqueue_script('st_shortcodes', get_template_directory_uri() . '/framework/shortcodes/shortcodes.js', array('jquery'), FALSE, TRUE);

    /*
    * Adds JavaScript to pages with the comment form to support
    * sites with threaded comments (when in use).
    */
    if (is_singular() && comments_open() && get_option('thread_comments'))
    {
        wp_enqueue_script('comment-reply');
    }


}

add_action('wp_enqueue_scripts', 'st_enqueue_scripts');


/*
* add ie conditional html5 shim to header
*/
function st_add_ie_html5_shim()
{
    echo '<!--[if lt IE 9]>';
    echo '<script src="' . get_template_directory_uri() . '/js/html5.js"></script>';
    echo '<![endif]-->';
}

add_action('wp_head', 'st_add_ie_html5_shim');


/*
* add ie 6-8 conditional selectivizr to header
*/
function st_add_ie_selectivizr()
{
    echo '<!--[if (gte IE 6)&(lte IE 8)]>';
    echo '<script src="' . get_template_directory_uri() . '/js/selectivizr-min.js"></script>';
    echo '<![endif]-->';
}

add_action('wp_head', 'st_add_ie_selectivizr');

/**
 * Add live search JavaScript to the footer
 */

function st_add_live_search()
{
    $url_vars = http_build_query(array('ajax' => 'on', 'post_type' => array('post', 'page', 'st_faq', 'st_kb'), 's' => ''));

    if (of_get_option('st_live_search') == '0')
    {

        if (class_exists('bbPress'))
        {
            if (get_post_type() == 'forum' || get_post_type() == 'topic' || get_post_type() == 'reply' || bbp_is_search())
            {
                // If BBPRess ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('#live-search #s').liveSearch({url: '<?php bbp_search_url(); ?>/?ajax=on&bbp_search='});
                    });
                </script>
                <?php
            }
            else
            {
                // If KB ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('#live-search #s').liveSearch({url: '<?= home_url() . '/?' . $url_vars ?>'});
                    });
                </script>
                <?php
            }
        }
        else
        { // If KB ?>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery('#live-search #s').liveSearch({url: '<?= home_url() . '/?' . $url_vars ?>'});
                });
            </script>
            <?php
        }

    } //if st_live_search

}

add_action('wp_footer', 'st_add_live_search');
