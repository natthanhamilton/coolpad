<?php
if (! defined('ABSPATH'))
{
    die('-1');
}

/**
 * WPBakery Visual Composer Main manager.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
if (! function_exists('vc_manager'))
{
    /**
     * Visual Composer manager.
     * @since 4.2
     * @return Vc_Manager
     */
    function vc_manager()
    {
        return Vc_Manager::getInstance();
    }
}
if (! function_exists('visual_composer'))
{
    /**
     * Visual Composer instance.
     * @since 4.2
     * @return Vc_Base
     */
    function visual_composer()
    {
        return vc_manager()->vc();
    }
}
if (! function_exists('vc_mapper'))
{
    /**
     * Shorthand for Vc Mapper.
     * @since 4.2
     * @return Vc_Mapper
     */
    function vc_mapper()
    {
        return vc_manager()->mapper();
    }
}
if (! function_exists('vc_settings'))
{
    /**
     * Shorthand for Visual composer settings.
     * @since 4.2
     * @return Vc_Settings
     */
    function vc_settings()
    {
        return vc_manager()->settings();
    }
}
if (! function_exists('vc_license'))
{
    /**
     * Get License manager
     * @since 4.2
     * @return Vc_License
     */
    function vc_license()
    {
        return vc_manager()->license();
    }
}
if (! function_exists('vc_automapper'))
{
    /**
     * @since 4.2
     * @return Vc_Automapper
     */
    function vc_automapper()
    {
        return vc_manager()->automapper();
    }
}
if (! function_exists('vc_frontend_editor'))
{
    /**
     * Shorthand for VC frontend editor
     * @since 4.2
     * @return Vc_Frontend_Editor
     */
    function vc_frontend_editor()
    {
        return vc_manager()->frontendEditor();
    }
}
if (! function_exists('vc_backend_editor'))
{
    /**
     * Shorthand for VC frontend editor
     * @since 4.2
     * @return Vc_Backend_Editor
     */
    function vc_backend_editor()
    {
        return vc_manager()->backendEditor();
    }
}
if (! function_exists('vc_updater'))
{
    /**
     * @since 4.2
     * @return Vc_Updater
     */
    function vc_updater()
    {
        return vc_manager()->updater();
    }
}
if (! function_exists('vc_is_network_plugin'))
{
    /**
     * Vc is network plugin or not.
     * @since 4.2
     * @return bool
     */
    function vc_is_network_plugin()
    {
        return vc_manager()->isNetworkPlugin();
    }
}
if (! function_exists('vc_path_dir'))
{
    /**
     * Get file/directory path in Vc.
     *
     * @param string $name - path name
     * @param string $file
     *
     * @since 4.2
     * @return string
     */
    function vc_path_dir($name, $file = '')
    {
        return vc_manager()->path($name, $file);
    }
}
if (! function_exists('vc_asset_url'))
{
    /**
     * Get full url for assets.
     *
     * @param string $file
     *
     * @since 4.2
     * @return string
     */
    function vc_asset_url($file)
    {
        return vc_manager()->assetUrl($file);
    }
}
if (! function_exists('vc_upload_dir'))
{
    /**
     * Temporary files upload dir;
     * @since 4.2
     * @return string
     */
    function vc_upload_dir()
    {
        return vc_manager()->uploadDir();
    }
}
if (! function_exists('vc_template'))
{
    /**
     * @param $file
     *
     * @since 4.2
     * @return string
     */
    function vc_template($file)
    {
        return vc_path_dir('TEMPLATES_DIR', $file);
    }
}
if (! function_exists('vc_post_param'))
{
    /**
     * Get param value from $_POST if exists.
     *
     * @param $param
     * @param $default
     *
     * @since 4.2
     * @return null|string - null for undefined param.
     */
    function vc_post_param($param, $default = NULL)
    {
        return isset($_POST[$param]) ? $_POST[$param] : $default;
    }
}
if (! function_exists('vc_get_param'))
{
    /**
     * Get param value from $_GET if exists.
     *
     * @param string $param
     * @param $default
     *
     * @since 4.2
     * @return null|string - null for undefined param.
     */
    function vc_get_param($param, $default = NULL)
    {
        return isset($_GET[$param]) ? $_GET[$param] : $default;
    }
}
if (! function_exists('vc_request_param'))
{
    /**
     * Get param value from $_REQUEST if exists.
     *
     * @param $param
     * @param $default
     *
     * @since 4.4
     * @return null|string - null for undefined param.
     */
    function vc_request_param($param, $default = NULL)
    {
        return isset($_REQUEST[$param]) ? $_REQUEST[$param] : $default;
    }
}
if (! function_exists('vc_is_frontend_editor'))
{
    /**
     * @since 4.2
     * @return bool
     */
    function vc_is_frontend_editor()
    {
        return 'admin_frontend_editor' === vc_mode();
    }
}
if (! function_exists('vc_is_page_editable'))
{
    /**
     * @since 4.2
     * @return bool
     */
    function vc_is_page_editable()
    {
        return 'page_editable' === vc_mode();
    }
}
if (! function_exists('vc_action'))
{
    /**
     * Get VC special action param.
     * @since 4.2
     * @return string|null
     */
    function vc_action()
    {
        $vc_action = NULL;
        if (isset($_GET['vc_action']))
        {
            $vc_action = $_GET['vc_action'];
        }
        elseif (isset($_POST['vc_action']))
        {
            $vc_action = $_POST['vc_action'];
        }

        return $vc_action;
    }
}
if (! function_exists('vc_is_inline'))
{
    /**
     * Get is inline or not.
     * @since 4.2
     * @return bool
     */
    function vc_is_inline()
    {
        global $vc_is_inline;
        if (is_null($vc_is_inline))
        {
            $vc_is_inline = (current_user_can('edit_posts') || current_user_can('edit_pages')) && 'vc_inline' === vc_action() || ! is_null(vc_request_param('vc_inline')) || 'true' === vc_request_param('vc_editable');
        }

        return $vc_is_inline;
    }
}
if (! function_exists('vc_is_frontend_ajax'))
{
    /**
     * @since 4.2
     * @return bool
     */
    function vc_is_frontend_ajax()
    {
        return 'true' === vc_post_param('vc_inline') || vc_get_param('vc_inline');
    }
}
/**
 * @depreacted since 4.8 ( use vc_is_frontend_editor )
 * @since 4.2
 * @return bool
 */
function vc_is_editor()
{
    return vc_is_frontend_editor();
}

/**
 * @param $value
 * @param bool $encode
 *
 * @since 4.2
 * @return string
 */
function vc_value_from_safe($value, $encode = FALSE)
{
    $value = preg_match('/^#E\-8_/', $value) ? rawurldecode(base64_decode(preg_replace('/^#E\-8_/', '', $value))) : $value;
    if ($encode)
    {
        $value = htmlentities($value, ENT_COMPAT, 'UTF-8');
    }

    return $value;
}

/**
 * @since 4.2
 *
 * @param bool $disable
 */
function vc_disable_automapper($disable = TRUE)
{
    vc_automapper()->setDisabled($disable);
}

/**
 * @since 4.2
 * @return bool
 */
function vc_automapper_is_disabled()
{
    return vc_automapper()->disabled();
}

/**
 * @param $param
 * @param $value
 *
 * @since 4.2
 * @return mixed|string
 */
function vc_get_dropdown_option($param, $value)
{
    if ('' === $value && is_array($param['value']))
    {
        $value = array_shift($param['value']);
    }
    if (is_array($value))
    {
        reset($value);
        $value = isset($value['value']) ? $value['value'] : current($value);
    }
    $value = preg_replace('/\s/', '_', $value);

    return ('' !== $value ? $value : '');
}

/**
 * @param $prefix
 * @param $color
 *
 * @since 4.2
 * @return string
 */
function vc_get_css_color($prefix, $color)
{
    $rgb_color = preg_match('/rgba/', $color) ? preg_replace(array(
        '/\s+/',
        '/^rgba\((\d+)\,(\d+)\,(\d+)\,([\d\.]+)\)$/',
    ), array(
        '',
        'rgb($1,$2,$3)',
    ), $color) : $color;
    $string = $prefix . ':' . $rgb_color . ';';
    if ($rgb_color !== $color)
    {
        $string .= $prefix . ':' . $color . ';';
    }

    return $string;
}

/**
 * @param $param_value
 * @param string $prefix
 *
 * @since 4.2
 * @return string
 */
function vc_shortcode_custom_css_class($param_value, $prefix = '')
{
    $css_class = preg_match('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value) ? $prefix . preg_replace('/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value) : '';

    return $css_class;
}

/**
 * @param $subject
 * @param $property
 * @param bool|false $strict
 *
 * @since 4.9
 * @return bool
 */
function vc_shortcode_custom_css_has_property($subject, $property, $strict = FALSE)
{
    $styles = array();
    $pattern = '/\{([^\}]*?)\}/i';
    preg_match($pattern, $subject, $styles);
    if (array_key_exists(1, $styles))
    {
        $styles = explode(';', $styles[1]);
    }
    $new_styles = array();
    foreach ($styles as $val)
    {
        $val = explode(':', $val);
        if (is_array($property))
        {
            foreach ($property as $prop)
            {
                $pos = strpos($val[0], $prop);
                $full = ($strict) ? ($pos === 0 && strlen($val[0]) === strlen($prop)) : TRUE;
                if ($pos !== FALSE && $full)
                {
                    $new_styles[] = $val;
                }
            }
        }
        else
        {
            $pos = strpos($val[0], $property);
            $full = ($strict) ? ($pos === 0 && strlen($val[0]) === strlen($property)) : TRUE;
            if ($pos !== FALSE && $full)
            {
                $new_styles[] = $val;
            }
        }
    }

    return ! empty($new_styles);
}

/**
 * Plugin name for VC.
 *
 * @since 4.2
 * @return string
 */
function vc_plugin_name()
{
    return vc_manager()->pluginName();
}

/**
 * @since 4.4.3 used in vc_base when getting an custom css output
 *
 * @param $filename
 *
 * @return bool|mixed|string
 */
function vc_file_get_contents($filename, $partial = FALSE)
{
    global $wp_filesystem;
    if (empty($wp_filesystem))
    {
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }
    /** @var $wp_filesystem WP_Filesystem_Base */
    if (! is_object($wp_filesystem) || ! $output = $wp_filesystem->get_contents($filename))
    {
        /*if ( is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {

        } elseif ( ! $wp_filesystem->connect() ) {

        } elseif ( ! $wp_filesystem->is_writable( $filename ) ) {

        } else {

        }*/
        $output = file_get_contents($filename);
    }

    return $output;
}

/**
 * HowTo: vc_role_access()->who('administrator')->with('editor')->can('frontend_editor');
 * @since 4.8
 * @return Vc_Role_Access;
 */
function vc_role_access()
{
    return vc_manager()->getRoleAccess();
}

/**
 * Get access manager for current user.
 * HowTo: vc_user_access()->->with('editor')->can('frontend_editor');
 * @since 4.8
 * @return Vc_Current_User_Access;
 */
function vc_user_access()
{
    return vc_manager()->getCurrentUserAccess();
}

function vc_user_roles_get_all()
{
    require_once vc_path_dir('SETTINGS_DIR', 'class-vc-roles.php');
    $vc_roles = new Vc_Roles();
    $capabilities = array();
    foreach ($vc_roles->getParts() as $part)
    {
        $partObj = vc_user_access()->part($part);
        $capabilities[$part] = array(
            'state'        => $partObj->getState(),
            'state_key'    => $partObj->getStateKey(),
            'capabilities' => $partObj->getAllCaps(),
        );
    }

    return $capabilities;
}

/**
 * Return a $_GET action param for ajax
 * @since 4.8
 * @return bool
 */
function vc_wp_action()
{
    return isset($_REQUEST['action']) ? $_REQUEST['action'] : FALSE;
}

/**
 * @param $data
 *
 * @return string
 */
function vc_generate_nonce($data)
{
    return wp_create_nonce(is_array($data) ? ('vc-nonce-' . implode('|', $data)) : ('vc-nonce-' . $data));
}

/**
 * @param $nonce
 * @param $data
 *
 * @return bool
 */
function vc_verify_nonce($nonce, $data)
{
    return (bool)wp_verify_nonce($nonce, (is_array($data) ? ('vc-nonce-' . implode('|', $data)) : ('vc-nonce-' . $data)));
}

/**
 * @param $nonce
 *
 * @return bool
 */
function vc_verify_admin_nonce($nonce = '')
{
    return (bool)vc_verify_nonce(! empty($nonce) ? $nonce : vc_request_param('_vcnonce'), 'vc-admin-nonce');
}

/**
 * @param $nonce
 *
 * @return bool
 */
function vc_verify_public_nonce($nonce = '')
{
    return (bool)vc_verify_nonce((! empty($nonce) ? $nonce : vc_request_param('_vcnonce')), 'vc-public-nonce');
}

function vc_check_post_type($type)
{
    if (empty($type))
    {
        $type = get_post_type();
    }
    $valid = apply_filters('vc_check_post_type_validation', NULL, $type);
    if (is_null($valid))
    {
        if (is_multisite() && is_super_admin())
        {
            return TRUE;
        }
        $state = vc_user_access()
            ->part('post_types')
            ->getState();
        if (NULL === $state)
        {
            return in_array($type, vc_default_editor_post_types());
        }
        else if (TRUE === $state && ! in_array($type, vc_default_editor_post_types()))
        {
            $valid = FALSE;
        }
        else
        {
            $valid = vc_user_access()
                ->part('post_types')
                ->can($type)
                ->get();
        }
    }

    return $valid;
}

function vc_user_access_check_shortcode_edit($shortcode)
{
    $do_check = apply_filters('vc_user_access_check-shortcode_edit', NULL, $shortcode);

    if (is_null($do_check))
    {
        $state_check = vc_user_access()
            ->part('shortcodes')
            ->checkStateAny(TRUE, 'edit', NULL)
            ->get();
        if ($state_check)
        {
            return TRUE;
        }
        else
        {
            return vc_user_access()
                ->part('shortcodes')
                ->canAny($shortcode . '_all', $shortcode . '_edit')
                ->get();
        }
    }
    else
    {
        return $do_check;
    }
}

function vc_user_access_check_shortcode_all($shortcode)
{
    $do_check = apply_filters('vc_user_access_check-shortcode_all', NULL, $shortcode);

    if (is_null($do_check))
    {
        return vc_user_access()
            ->part('shortcodes')
            ->checkStateAny(TRUE, 'custom', NULL)
            ->can($shortcode . '_all')
            ->get();
    }
    else
    {
        return $do_check;
    }
}

/**
 * htmlspecialchars_decode_deep
 * Call the htmlspecialchars_decode to a gived multilevel array
 *
 * @since 4.8
 *
 * @param mixed $value The value to be stripped.
 *
 * @return mixed Stripped value.
 */
function vc_htmlspecialchars_decode_deep($value)
{
    if (is_array($value))
    {
        $value = array_map('vc_htmlspecialchars_decode_deep', $value);
    }
    elseif (is_object($value))
    {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data)
        {
            $value->{$key} = vc_htmlspecialchars_decode_deep($data);
        }
    }
    elseif (is_string($value))
    {
        $value = htmlspecialchars_decode($value);
    }

    return $value;
}

function vc_str_remove_protocol($str)
{
    return str_replace(array(
        'https://',
        'http://',
    ), '//', $str);
}
