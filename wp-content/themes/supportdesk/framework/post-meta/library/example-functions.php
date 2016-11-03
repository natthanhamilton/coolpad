<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter('cmb_meta_boxes', 'cmb_sample_metaboxes');
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 *
 * @return array
 */
function cmb_sample_metaboxes(array $meta_boxes) {
    // Start with an underscore to hide fields from custom fields list
    $prefix = '_cmb_';
    $meta_boxes[] = [
        'id'         => 'test_metabox',
        'title'      => 'Test Metabox',
        'pages'      => ['page',], // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => TRUE, // Show field names on the left
        'fields'     => [
            [
                'name' => 'Test Text',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_text',
                'type' => 'text',
            ],
            [
                'name' => 'Test Text Small',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textsmall',
                'type' => 'text_small',
            ],
            [
                'name' => 'Test Text Medium',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textmedium',
                'type' => 'text_medium',
            ],
            [
                'name' => 'Test Date Picker',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textdate',
                'type' => 'text_date',
            ],
            [
                'name' => 'Test Date Picker (UNIX timestamp)',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textdate_timestamp',
                'type' => 'text_date_timestamp',
            ],
            [
                'name' => 'Test Date/Time Picker Combo (UNIX timestamp)',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_datetime_timestamp',
                'type' => 'text_datetime_timestamp',
            ],
            [
                'name' => 'Test Time',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_time',
                'type' => 'text_time',
            ],
            [
                'name' => 'Test Money',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textmoney',
                'type' => 'text_money',
            ],
            [
                'name' => 'Test Color Picker',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_colorpicker',
                'type' => 'colorpicker',
                'std'  => '#ffffff'
            ],
            [
                'name' => 'Test Text Area',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textarea',
                'type' => 'textarea',
            ],
            [
                'name' => 'Test Text Area Small',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textareasmall',
                'type' => 'textarea_small',
            ],
            [
                'name' => 'Test Text Area Code',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_textarea_code',
                'type' => 'textarea_code',
            ],
            [
                'name' => 'Test Title Weeeee',
                'desc' => 'This is a title description',
                'id'   => $prefix . 'test_title',
                'type' => 'title',
            ],
            [
                'name'    => 'Test Select',
                'desc'    => 'field description (optional)',
                'id'      => $prefix . 'test_select',
                'type'    => 'select',
                'options' => [
                    ['name' => 'Option One', 'value' => 'standard',],
                    ['name' => 'Option Two', 'value' => 'custom',],
                    ['name' => 'Option Three', 'value' => 'none',],
                ],
            ],
            [
                'name'    => 'Test Radio inline',
                'desc'    => 'field description (optional)',
                'id'      => $prefix . 'test_radio_inline',
                'type'    => 'radio_inline',
                'options' => [
                    ['name' => 'Option One', 'value' => 'standard',],
                    ['name' => 'Option Two', 'value' => 'custom',],
                    ['name' => 'Option Three', 'value' => 'none',],
                ],
            ],
            [
                'name'    => 'Test Radio',
                'desc'    => 'field description (optional)',
                'id'      => $prefix . 'test_radio',
                'type'    => 'radio',
                'options' => [
                    ['name' => 'Option One', 'value' => 'standard',],
                    ['name' => 'Option Two', 'value' => 'custom',],
                    ['name' => 'Option Three', 'value' => 'none',],
                ],
            ],
            [
                'name'     => 'Test Taxonomy Radio',
                'desc'     => 'Description Goes Here',
                'id'       => $prefix . 'text_taxonomy_radio',
                'type'     => 'taxonomy_radio',
                'taxonomy' => '', // Taxonomy Slug
            ],
            [
                'name'     => 'Test Taxonomy Select',
                'desc'     => 'Description Goes Here',
                'id'       => $prefix . 'text_taxonomy_select',
                'type'     => 'taxonomy_select',
                'taxonomy' => '', // Taxonomy Slug
            ],
            [
                'name'     => 'Test Taxonomy Multi Checkbox',
                'desc'     => 'field description (optional)',
                'id'       => $prefix . 'test_multitaxonomy',
                'type'     => 'taxonomy_multicheck',
                'taxonomy' => '', // Taxonomy Slug
            ],
            [
                'name' => 'Test Checkbox',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_checkbox',
                'type' => 'checkbox',
            ],
            [
                'name'    => 'Test Multi Checkbox',
                'desc'    => 'field description (optional)',
                'id'      => $prefix . 'test_multicheckbox',
                'type'    => 'multicheck',
                'options' => [
                    'check1' => 'Check One',
                    'check2' => 'Check Two',
                    'check3' => 'Check Three',
                ],
            ],
            [
                'name'    => 'Test wysiwyg',
                'desc'    => 'field description (optional)',
                'id'      => $prefix . 'test_wysiwyg',
                'type'    => 'wysiwyg',
                'options' => ['textarea_rows' => 5,],
            ],
            [
                'name' => 'Test Image',
                'desc' => 'Upload an image or enter an URL.',
                'id'   => $prefix . 'test_image',
                'type' => 'file',
            ],
            [
                'name' => 'oEmbed',
                'desc' => 'Enter a youtube, twitter, or instagram URL. Supports services listed at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.',
                'id'   => $prefix . 'test_embed',
                'type' => 'oembed',
            ],
        ],
    ];
    $meta_boxes[] = [
        'id'         => 'about_page_metabox',
        'title'      => 'About Page Metabox',
        'pages'      => ['page',], // Post type
        'context'    => 'normal',
        'priority'   => 'high',
        'show_names' => TRUE, // Show field names on the left
        'show_on'    => ['key' => 'id', 'value' => [2,],], // Specific post IDs to display this metabox
        'fields'     => [
            [
                'name' => 'Test Text',
                'desc' => 'field description (optional)',
                'id'   => $prefix . 'test_text',
                'type' => 'text',
            ],
        ]
    ];

    // Add other metaboxes as needed
    return $meta_boxes;
}

add_action('init', 'cmb_initialize_cmb_meta_boxes', 9999);
/**
 * Initialize the metabox class.
 */
function cmb_initialize_cmb_meta_boxes() {
    if (!class_exists('cmb_Meta_Box')) {
        require_once 'init.php';
    }
}