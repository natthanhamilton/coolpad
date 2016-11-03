<?php
add_filter('wpas_plugin_settings', 'wpas_core_settings_licenses', 99, 1);
/**
 * Add plugin core settings.
 *
 * @param  (array) $def Array of existing settings
 *
 * @return (array)      Updated settings
 */
function wpas_core_settings_licenses($def) {
	$licenses = apply_filters('wpas_addons_licenses', []);
	if (empty($licenses)) {
		return $def;
	}
	$settings = [
		'licenses' => [
			'name'    => __('Licenses', 'awesome-support'),
			'options' => $licenses
		],
	];

	return array_merge($def, $settings);
}