<?php
if (! defined('ABSPATH'))
{
    die('-1');
}
vc_include_template('pages/partials/vc-roles-parts/_part.tpl.php', array(
    'part'          => $part,
    'role'          => $role,
    'params_prefix' => 'vc_roles[' . $role . '][' . $part . ']',
    'controller'    => vc_role_access()
        ->who($role)
        ->part($part),
    'custom_value'  => 'custom',

    'capabilities' => $vc_role->getPostTypes(),
    'options'      => array(
        array(TRUE, __('Pages only', 'js_composer')),
        array('custom', __('Custom', 'js_composer')),
        array(FALSE, __('Disabled', 'js_composer')),
    ),
    'main_label'   => __('Post types', 'js_composer'),
    'custom_label' => __('Post types', 'js_composer'),
    'description'  => __('Enable Visual Composer for pages, posts and custom post types. Note: By default Visual Composer is available for pages only.', 'js_composer'),
));
