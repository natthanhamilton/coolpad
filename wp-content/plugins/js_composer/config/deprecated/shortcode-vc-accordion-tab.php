<?php
return [
    'name'                      => __('Section', 'js_composer'),
    'base'                      => 'vc_accordion_tab',
    'allowed_container_element' => 'vc_row',
    'is_container'              => TRUE,
    'deprecated'                => '4.6',
    'content_element'           => FALSE,
    'params'                    => [
        [
            'type'        => 'textfield',
            'heading'     => __('Title', 'js_composer'),
            'param_name'  => 'title',
            'value'       => __('Section', 'js_composer'),
            'description' => __('Enter accordion section title.', 'js_composer'),
        ],
        [
            'type'        => 'el_id',
            'heading'     => __('Section ID', 'js_composer'),
            'param_name'  => 'el_id',
            'description' => sprintf(__('Enter optional row ID. Make sure it is unique, and it is valid as w3c specification: %s (Must not have spaces)',
                                        'js_composer'),
                                     '<a target="_blank" href="http://www.w3schools.com/tags/att_global_id.asp">' . __('link',
                                                                                                                       'js_composer') . '</a>'),
        ],
    ],
    'js_view'                   => 'VcAccordionTabView',
];