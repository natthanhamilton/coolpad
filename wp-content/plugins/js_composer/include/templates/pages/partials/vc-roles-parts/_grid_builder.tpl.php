<?php
if (!defined('ABSPATH')) {
    die('-1');
}
vc_include_template('pages/partials/vc-roles-parts/_part.tpl.php', [
    'part'          => $part,
    'role'          => $role,
    'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
    'controller'    => vc_role_access()
        ->who($role)
        ->part($part),
    'options'       => [
        [TRUE, __('Enabled', 'js_composer')],
        [FALSE, __('Disabled', 'js_composer')],
    ],
    'main_label'    => __('Grid Builder', 'js_composer'),
    'custom_label'  => __('Grid Builder', 'js_composer'),
    'description'   => __('Control user access to Grid Builder and Grid Builder Elements.', 'js_composer'),
]);
