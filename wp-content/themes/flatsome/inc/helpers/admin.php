<?php
/* Add Custom WP Editor CSS */
add_filter('mce_css', 'my_editor_style');
function my_editor_style($url) {
    if (!empty($url)) {
        $url .= ',';
    }
    // Change the path here if using sub-directory
    $url .= trailingslashit(get_template_directory_uri()) . 'css/editor.css';

    return $url;
}

/* Extra Editor Styles (add extra styles to the content editor box) */
add_filter('mce_buttons_2', 'my_mce_buttons_2');
function my_mce_buttons_2($buttons) {
    array_unshift($buttons, 'styleselect');

    return $buttons;
}

add_filter('tiny_mce_before_init', 'ux_formats_before_init');
function ux_formats_before_init($settings) {
    $style_formats = [
        [
            'title' => 'Link styles',
            'items' => [
                [
                    'title'    => 'Button Primary',
                    'selector' => 'a',
                    'classes'  => 'button primary',
                ],
                [
                    'title'    => 'Button White',
                    'selector' => 'a',
                    'classes'  => 'button white',
                ],
                [
                    'title'    => 'Button Secondary',
                    'selector' => 'a',
                    'classes'  => 'button secondary',
                ],
                [
                    'title'    => 'Button Alert',
                    'selector' => 'a',
                    'classes'  => 'button alert',
                ],
                [
                    'title'    => 'Button Success',
                    'selector' => 'a',
                    'classes'  => 'button success',
                ],
                [
                    'title'    => 'Button Alternative Primary',
                    'selector' => 'a',
                    'classes'  => 'button alt-button',
                ],
                [
                    'title'    => 'Button Alternative White',
                    'selector' => 'a',
                    'classes'  => 'button alt-button white',
                ],
                [
                    'title'    => 'Large - Button Primary',
                    'selector' => 'a',
                    'classes'  => 'button large  primary',
                ],
                [
                    'title'    => 'Large Button Secondary',
                    'selector' => 'a',
                    'classes'  => 'button large  secondary',
                ],
                [
                    'title'    => 'Large Button Alert',
                    'selector' => 'a',
                    'classes'  => 'button large  alert',
                ],
                [
                    'title'    => 'Large Button Success',
                    'selector' => 'a',
                    'classes'  => 'button large  success',
                ],
                [
                    'title'    => 'Large Button Alternative Primary',
                    'selector' => 'a',
                    'classes'  => 'button large  alt-button success',
                ],
                [
                    'title'    => 'Large Button Alternative Secondary',
                    'selector' => 'a',
                    'classes'  => 'button large  alt-button secondary',
                ],
                [
                    'title'    => 'Large Button Alternative White',
                    'selector' => 'a',
                    'classes'  => 'button large alt-button white',
                ]
            ]
        ],
        [
            'title'    => 'Pull text inn',
            'selector' => 'p',
            'classes'  => 'text-pull-inn',
            'exact'    => 'true',
        ],
        [
            'title'    => 'Paragraph - Lead',
            'selector' => 'p',
            'classes'  => 'lead',
            'exact'    => 'true',
        ],
        [
            'title'    => 'Paragraph - Lead, Centered',
            'selector' => 'p',
            'classes'  => 'lead text-center',
            'exact'    => 'true',
        ],
        [
            'title'    => 'Uppercase',
            'selector' => '*',
            'classes'  => 'uppercase',
        ],
        [
            'title'    => 'Thin Font',
            'selector' => '*',
            'classes'  => 'thin-font',
        ],
        [
            'title'    => 'Hide on Mobile screens',
            'selector' => '*',
            'classes'  => 'hide-for-small',
        ],
        [
            'title'    => 'Alternative Font',
            'selector' => '*',
            'classes'  => 'alt-font',
        ],
        [
            'title'    => 'Title - Large',
            'selector' => '*',
            'classes'  => 'h-large',
        ],
        [
            'title'    => 'Title - X-Large',
            'selector' => '*',
            'classes'  => 'h-xlarge',
        ],
        [
            'title'    => 'Backgroud - Black',
            'selector' => '*',
            'classes'  => 'text-box-dark',
        ],
        [
            'title'    => 'Background - White',
            'selector' => '*',
            'classes'  => 'text-box-light',
        ],
        [
            'title'    => 'Background - Primary Color',
            'selector' => '*',
            'classes'  => 'text-box-primary',
        ],
        [
            'title'    => 'Text Border White',
            'selector' => '*',
            'classes'  => 'text-bordered-white',
        ],
        [
            'title'    => 'Text Border Primary',
            'selector' => '*',
            'classes'  => 'text-bordered-primary',
        ],
        [
            'title'    => 'Text Border Dark',
            'selector' => '*',
            'classes'  => 'text-bordered-dark',
        ]
        ,
        [
            'title'    => 'Text Border Top and Bottom White',
            'selector' => '*',
            'classes'  => 'text-boarder-top-bottom-white',
        ]
        ,
        [
            'title'    => 'Text Border Top and Bottom Dark',
            'selector' => '*',
            'classes'  => 'text-boarder-top-bottom-dark',
        ],
        [
            'title'    => 'Tilt Left',
            'selector' => '*',
            'classes'  => 'tilt-left',
        ],
        [
            'title'    => 'Text Border Top and Bottom Dark',
            'selector' => '*',
            'classes'  => 'tilt-right',
        ]
        ,
        [
            'title'    => 'Bullets List - Check mark',
            'selector' => 'li',
            'classes'  => 'bullet-checkmark',
        ],
        [
            'title'    => 'Bullets List - Arrow',
            'selector' => 'li',
            'classes'  => 'bullet-arrow',
        ],
        [
            'title'    => 'Bullets List - Star',
            'selector' => 'li',
            'classes'  => 'bullet-star',
        ],
        [
            'title'    => 'Text shadow',
            'selector' => '*',
            'classes'  => 'drop-shadow',
        ],
        [
            'title'    => 'Animate -Fade In',
            'selector' => '*',
            'classes'  => 'animated fadeIn',
        ],
        [
            'title'    => 'Animate - Fade In Left',
            'selector' => '*',
            'classes'  => 'animated fadeInLeft',
        ],
        [
            'title'    => 'Animate - Fade In Right',
            'selector' => '*',
            'classes'  => 'animated fadeInRight',
        ],
    ];
    $settings['style_formats'] = json_encode($style_formats);

    return $settings;
}

/* Extra Editor Colors */
function ux_mce4_options($init) {
    global $flatsome_opt;
    $default_colours
                           = '
      "000000", "Black",        "993300", "Burnt orange", "333300", "Dark olive",   "003300", "Dark green",   "003366", "Dark azure",   "000080", "Navy Blue",      "333399", "Indigo",       "333333", "Very dark gray", 
      "800000", "Maroon",       "FF6600", "Orange",       "808000", "Olive",        "008000", "Green",        "008080", "Teal",         "0000FF", "Blue",           "666699", "Grayish blue", "808080", "Gray", 
      "FF0000", "Red",          "FF9900", "Amber",        "99CC00", "Yellow green", "339966", "Sea green",    "33CCCC", "Turquoise",    "3366FF", "Royal blue",     "800080", "Purple",       "999999", "Medium gray", 
      "FF00FF", "Magenta",      "FFCC00", "Gold",         "FFFF00", "Yellow",       "00FF00", "Lime",         "00FFFF", "Aqua",         "00CCFF", "Sky blue",       "993366", "Brown",        "C0C0C0", "Silver", 
      "FF99CC", "Pink",         "FFCC99", "Peach",        "FFFF99", "Light yellow", "CCFFCC", "Pale green",   "CCFFFF", "Pale cyan",    "99CCFF", "Light sky blue", "CC99FF", "Plum",         "FFFFFF", "White"
  ';
    $custom_colours
                           = '
      "e14d43", "Primary Color", "d83131", "Color 2 Name", "ed1c24", "Color 3 Name", "f99b1c", "Color 4 Name", "50b848", "Color 5 Name", "00a859", "Color 6 Name",   "00aae7", "Color 7 Name", "282828", "Color 8 Name"
  ';
    $init['textcolor_map'] = '[' . $custom_colours . ',' . $default_colours . ']';

    return $init;
}

add_filter('tiny_mce_before_init', 'ux_mce4_options');
/* Enable SVG upload */
function ux_enable_svg($mimes) {
    $mimes['svg'] = 'image/svg+xml';

    return $mimes;
}

add_filter('upload_mimes', 'ux_enable_svg');
function ux_enable_font_upload($mimes) {
    $mimes['ttf'] = 'application/octet-stream';
    $mimes['otf'] = 'font/opentype';

    return $mimes;
}

add_filter('upload_mimes', 'ux_enable_font_upload');
/* Ajax Search. Finds Products, Post and Pages */
function flatsome_pre_get_posts_action($query) {
    global $flatsome_opt;
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    // Stop if searching from admin
    if ($action == 'woocommerce_json_search_products') {
        return;
    }
    if ($action == 'woocommerce_json_search_products_and_variations') {
        return;
    }
    // Include posts and pages in ajax search.
    if (defined('DOING_AJAX') && DOING_AJAX && !empty($query->query_vars['s']) && $flatsome_opt['search_result']) {
        $query->query_vars['post_type']  = [$query->query_vars['post_type'], 'post', 'page'];
        $query->query_vars['meta_query'] = new WP_Meta_Query(['relation' => 'OR', $query->query_vars['meta_query']]);
    }
}

add_action('pre_get_posts', 'flatsome_pre_get_posts_action');