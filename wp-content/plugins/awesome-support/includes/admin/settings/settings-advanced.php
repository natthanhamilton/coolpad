<?php
add_filter('wpas_plugin_settings', 'wpas_core_settings_advanced', 95, 1);
/**
 * Add plugin advanced settings.
 *
 * @param  (array) $def Array of existing settings
 *
 * @return (array)      Updated settings
 */
function wpas_core_settings_advanced($def) {
	$settings = [
		'advanced' => [
			'name'    => __('Advanced', 'awesome-support'),
			'options' => [
				[
					'name'    => __('Custom Login / Registration Page', 'awesome-support'),
					'id'      => 'login_page',
					'type'    => 'select',
					'desc'    => sprintf(__('Only use this option if you know how to create your own registration page, otherwise you might create an infinite redirect. If you need help on creating a registration page you should <a href="%s" target="_blank">start by reading this guide</a>.',
					                        'awesome-support'),
					                     esc_url('http://codex.wordpress.org/Customizing_the_Registration_Form')),
					'default' => '',
					'options' => wpas_list_pages()
				],
				[
					'name'    => __('Admins See All', 'awesome-support'),
					'id'      => 'admin_see_all',
					'type'    => 'checkbox',
					'desc'    => __('Administrators can see all tickets in the tickets list. If unchecked admins will only see tickets assigned to them.',
					                'awesome-support'),
					'default' => TRUE
				],
				[
					'name'    => __('Agent See All', 'awesome-support'),
					'id'      => 'agent_see_all',
					'type'    => 'checkbox',
					'desc'    => __('Agents can see all tickets in the tickets list. If unchecked agents will only see tickets assigned to them.',
					                'awesome-support'),
					'default' => FALSE
				],
				[
					'name' => __('Danger Zone', 'awesome-support'),
					'type' => 'heading',
				],
				[
					'name'    => __('Delete Data', 'awesome-support'),
					'id'      => 'delete_data',
					'type'    => 'checkbox',
					'default' => FALSE,
					'desc'    => __('Delete ALL plugin data on uninstall? This cannot be undone.', 'awesome-support')
				],
			]
		],
	];

	return array_merge($def, $settings);
}