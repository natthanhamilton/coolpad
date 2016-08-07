<?php
return [
    'name'                    => __('Accordion', 'js_composer'),
    'base'                    => 'vc_accordion',
    'show_settings_on_create' => FALSE,
    'is_container'            => TRUE,
    'icon'                    => 'icon-wpb-ui-accordion',
    'deprecated'              => '4.6',
    'category'                => __('Content', 'js_composer'),
    'description'             => __('Collapsible content panels', 'js_composer'),
    'params'                  => [
        [
            'type'        => 'textfield',
            'heading'     => __('Widget title', 'js_composer'),
            'param_name'  => 'title',
            'description' => __('Enter text used as widget title (Note: located above content element).',
                                'js_composer'),
        ],
        [
            'type'        => 'textfield',
            'heading'     => __('Active section', 'js_composer'),
            'param_name'  => 'active_tab',
            'value'       => 1,
            'description' => __('Enter section number to be active on load or enter "false" to collapse all sections.',
                                'js_composer'),
        ],
        [
            'type'        => 'checkbox',
            'heading'     => __('Allow collapse all sections?', 'js_composer'),
            'param_name'  => 'collapsible',
            'description' => __('If checked, it is allowed to collapse all sections.', 'js_composer'),
            'value'       => [__('Yes', 'js_composer') => 'yes'],
        ],
        [
            'type'        => 'checkbox',
            'heading'     => __('Disable keyboard interactions?', 'js_composer'),
            'param_name'  => 'disable_keyboard',
            'description' => __('If checked, disables keyboard arrow interactions (Keys: Left, Up, Right, Down, Space).',
                                'js_composer'),
            'value'       => [__('Yes', 'js_composer') => 'yes'],
        ],
        [
            'type'        => 'textfield',
            'heading'     => __('Extra class name', 'js_composer'),
            'param_name'  => 'el_class',
            'description' => __('Style particular content element differently - add a class name and refer to it in custom CSS.',
                                'js_composer'),
        ],
    ],
    'custom_markup'           => '
<div class="wpb_accordion_holder wpb_holder clearfix vc_container_for_children">
%content%
</div>
<div class="tab_controls">
    <a class="add_tab" title="' . __('Add section',
                                     'js_composer') . '"><span class="vc_icon"></span> <span class="tab-label">' . __('Add section',
                                                                                                                      'js_composer') . '</span></a>
</div>
',
    'default_content'         => '
    [vc_accordion_tab title="' . __('Section 1', 'js_composer') . '"][/vc_accordion_tab]
    [vc_accordion_tab title="' . __('Section 2', 'js_composer') . '"][/vc_accordion_tab]
',
    'js_view'                 => 'VcAccordionView',
];