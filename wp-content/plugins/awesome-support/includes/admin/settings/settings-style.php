<?php
add_filter('wpas_plugin_settings', 'wpas_core_settings_style', 5, 1);
/**
 * Add plugin style settings.
 *
 * @param  (array) $def Array of existing settings
 *
 * @return (array)      Updated settings
 */
function wpas_core_settings_style($def) {
    $settings = [
        'style' => [
            'name'    => __('Style', 'awesome-support'),
            'options' => [
                [
                    'name'    => __('Theme', 'awesome-support'),
                    'id'      => 'theme',
                    'type'    => 'select',
                    'desc'    => __('Which theme to use for the front-end.', 'awesome-support'),
                    'options' => wpas_list_themes(),
                    'default' => 'default'
                ],
                [
                    'name'    => __('Theme Stylesheet', 'awesome-support'),
                    'id'      => 'theme_stylesheet',
                    'type'    => 'checkbox',
                    'desc'    => __('Load the theme stylesheet. Don\'t uncheck if you don\'t know what this means',
                                    'awesome-support'),
                    'default' => TRUE
                ],
                [
                    'name'    => __('Use editor in front-end', 'awesome-support'),
                    'id'      => 'frontend_wysiwyg_editor',
                    'type'    => 'checkbox',
                    'desc'    => __('Show a editor editor for the ticket description when user submits a ticket.',
                                    'awesome-support'),
                    'default' => TRUE
                ],
                [
                    'name' => __('Colors', 'awesome-support'),
                    'type' => 'heading',
                ],
                [
                    'name'    => __('Open Status', 'awesome-support'),
                    'id'      => 'color_open',
                    'type'    => 'color',
                    'default' => '#81d742',
                ],
                [
                    'name'    => __('Closed Status', 'awesome-support'),
                    'id'      => 'color_closed',
                    'type'    => 'color',
                    'default' => '#dd3333',
                ],
                [
                    'name'    => __('Old Status', 'awesome-support'),
                    'id'      => 'color_old',
                    'type'    => 'color',
                    'default' => '#dd9933',
                ],
                [
                    'name'    => __('Awaiting Reply', 'awesome-support'),
                    'id'      => 'color_awaiting_reply',
                    'type'    => 'color',
                    'default' => '#0074a2',
                ],
            ]
        ],
    ];
    $status = wpas_get_post_status();
    $defaults = apply_filters('wpas_labels_default_colors', [
        'queued'     => '#1e73be',
        'processing' => '#a01497',
        'hold'       => '#b56629',
        'unknown'    => '#169baa'
    ]);
    foreach ($status as $id => $label) {
        $option = [
            'name'    => $label,
            'id'      => 'color_' . $id,
            'type'    => 'color',
            'default' => isset($defaults[ $id ]) ? $defaults[ $id ] : $defaults['unknown'],
        ];
        array_push($settings['style']['options'], $option);
    }

    return array_merge($def, $settings);
}