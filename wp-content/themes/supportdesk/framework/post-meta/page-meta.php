<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */
add_filter('cmb_meta_boxes', 'cmb_page_metaboxes');
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 *
 * @return array
 */
function cmb_page_metaboxes(array $meta_boxes) {
	// Start with an underscore to hide fields from custom fields list
	$prefix = '_st_';
	$meta_boxes[] = [
		'id'         => 'page_options',
		'title'      => 'Page Options',
		'pages'      => ['page',], // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => TRUE, // Show field names on the left
		'fields'     => [
			[
				'name' => 'Page Tagline',
				'desc' => 'Add a tagline below the page title.',
				'id'   => $prefix . 'page_tagline',
				'type' => 'text',
			],
			[
				'name' => 'Disable Breadcrumbs?',
				'desc' => 'Check this box to disable the breadcrumbs.',
				'id'   => $prefix . 'page_breadcrumbs',
				'type' => 'checkbox',
			],
			[
				'name'    => 'Sidebar Position',
				'desc'    => 'Adjust the position of the sidebar.',
				'id'      => $prefix . 'page_sidebar',
				'type'    => 'select',
				'options' => [
					['name' => 'Left', 'value' => 'left',],
					['name' => 'Right', 'value' => 'right',],
					['name' => 'Off', 'value' => 'off',],
				],
				'std'     => 'right',
			],
		],
	];

	// Add other metaboxes as needed
	return $meta_boxes;
}

add_action('init', 'cmb_initialize_cmb_meta_boxes', 9999);
