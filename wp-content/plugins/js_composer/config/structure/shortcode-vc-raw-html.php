<?php
return [
    'name'          => __('Raw HTML', 'js_composer'),
    'base'          => 'vc_raw_html',
    'icon'          => 'icon-wpb-raw-html',
    'category'      => __('Structure', 'js_composer'),
    'wrapper_class' => 'clearfix',
    'description'   => __('Output raw HTML code on your page', 'js_composer'),
    'params'        => [
        [
            'type'        => 'textarea_raw_html',
            'holder'      => 'div',
            'heading'     => __('Raw HTML', 'js_composer'),
            'param_name'  => 'content',
            'value'       => base64_encode('<p>I am raw html block.<br/>Click edit button to change this html</p>'),
            'description' => __('Enter your HTML content.', 'js_composer'),
        ],
        [
            'type'        => 'textfield',
            'heading'     => __('Extra class name', 'js_composer'),
            'param_name'  => 'el_class',
            'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.',
                                'js_composer'),
        ],
        [
            'type'       => 'css_editor',
            'heading'    => __('CSS box', 'js_composer'),
            'param_name' => 'css',
            'group'      => __('Design Options', 'js_composer'),
        ],
    ],
];