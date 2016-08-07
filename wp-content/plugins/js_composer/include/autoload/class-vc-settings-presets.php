<?php
if (! defined('ABSPATH'))
{
    die('-1');
}

/**
 * Collection of static methods for work with settings presets
 *
 * @since 4.8
 */
class Vc_Settings_Preset {

    /**
     * Set existing preset as default
     *
     * If this is vendor preset, clone it and set new one as default
     *
     * @param int $id If falsy, no default will be set
     * @param string $shortcode_name
     *
     * @return boolean
     *
     * @since 4.7
     */
    public static function setAsDefaultSettingsPreset($id, $shortcode_name)
    {
        $post_id = self::getDefaultSettingsPresetId($shortcode_name);
        if ($post_id)
        {
            delete_post_meta($post_id, '_vc_default');
        }

        if ($id)
        {
            if (is_numeric($id))
            {
                // user preset

                update_post_meta($id, '_vc_default', TRUE);
            }
            else
            {
                // vendor preset

                $preset = vc_vendor_preset()->get($id);

                if (! $preset || $shortcode_name !== $preset['shortcode'])
                {
                    return FALSE;
                }

                self::saveSettingsPreset(
                    $preset['shortcode'],
                    $preset['title'],
                    json_encode($preset['params']),
                    TRUE
                );
            }
        }

        return TRUE;
    }

    /**
     * Get default preset id for specific shortcode
     *
     * @since 4.7
     *
     * @param string $shortcode_name
     *
     * @return mixed int|null
     */
    public static function getDefaultSettingsPresetId($shortcode_name = NULL)
    {
        if (! $shortcode_name)
        {
            return NULL;
        }

        $args = array(
            'post_type'      => 'vc_settings_preset',
            'post_mime_type' => self::constructShortcodeMimeType($shortcode_name),
            'posts_per_page' => -1,
            'meta_key'       => '_vc_default',
            'meta_value'     => TRUE,
        );

        $posts = get_posts($args);

        if ($posts)
        {
            $default_id = $posts[0]->ID;
        }
        else
        {
            // check for vendor presets
            $default_id = vc_vendor_preset()->getDefaultId($shortcode_name);
        }

        return $default_id;
    }

    /**
     * Get mime type for specific shortcode
     *
     * @since 4.7
     *
     * @param $shortcode_name
     *
     * @return string
     */
    public static function constructShortcodeMimeType($shortcode_name)
    {
        return 'vc-settings-preset/' . str_replace('_', '-', $shortcode_name);
    }

    /**
     * Save shortcode preset
     *
     * @since 4.7
     *
     * @param string $shortcode_name
     * @param string $title
     * @param string $content
     * @param boolean $is_default
     *
     * @return mixed int|false Post ID
     */
    public static function saveSettingsPreset($shortcode_name, $title, $content, $is_default = FALSE)
    {
        $post_id = wp_insert_post(array(
            'post_title'     => $title,
            'post_content'   => $content,
            'post_status'    => 'publish',
            'post_type'      => 'vc_settings_preset',
            'post_mime_type' => self::constructShortcodeMimeType($shortcode_name),
        ), FALSE);

        if ($post_id && $is_default)
        {
            self::setAsDefaultSettingsPreset($post_id, $shortcode_name);
        }

        return $post_id;
    }

    /**
     * Get all default presets
     *
     * @since 4.7
     *
     * @return array E.g. array(shortcode_name => value, shortcode_name => value, ...)
     */
    public static function listDefaultSettingsPresets()
    {
        $list = array();

        $args = array(
            'post_type'      => 'vc_settings_preset',
            'posts_per_page' => -1,
            'meta_key'       => '_vc_default',
            'meta_value'     => TRUE,
        );

        // user presets
        $posts = get_posts($args);
        foreach ($posts as $post)
        {
            $shortcode_name = self::extractShortcodeMimeType($post->post_mime_type);
            $list[$shortcode_name] = (array)json_decode($post->post_content);
        }

        // vendor presets
        $presets = self::listDefaultVendorSettingsPresets();
        foreach ($presets as $shortcode => $params)
        {
            if (! isset($list[$shortcode]))
            {
                $list[$shortcode] = $params;
            }
        }

        return $list;
    }

    /**
     * Get shortcode name from post's mime type
     *
     * @since 4.7
     *
     * @param string $post_mime_type
     *
     * @return string
     */
    public static function extractShortcodeMimeType($post_mime_type)
    {
        $chunks = explode('/', $post_mime_type);

        if (2 !== count($chunks))
        {
            return '';
        }

        return str_replace('-', '_', $chunks[1]);
    }

    /**
     * Get all default vendor presets
     *
     * @since 4.8
     *
     * @return array E.g. array(shortcode_name => value, shortcode_name => value, ...)
     */
    public static function listDefaultVendorSettingsPresets()
    {
        $list = array();

        $presets = vc_vendor_preset()->getDefaults();
        foreach ($presets as $id => $preset)
        {
            $list[$preset['shortcode']] = $preset['params'];
        }

        return $list;
    }

    /**
     * Get specific shortcode preset
     *
     * @since 4.7
     *
     * @param mixed $id Can be int (user preset) or string (vendor preset)
     * @param bool $array If true, return array instead of string
     *
     * @return mixed string?array Post content
     */
    public static function getSettingsPreset($id, $array = FALSE)
    {
        if (is_numeric($id))
        {
            // user preset

            $post = get_post($id);

            if (! $post)
            {
                return FALSE;
            }

            $params = $array ? (array)json_decode($post->post_content) : $post->post_content;
        }
        else
        {
            // vendor preset

            $preset = vc_vendor_preset()->get($id);

            if (! $preset)
            {
                return FALSE;
            }

            $params = $preset['params'];
        }

        return $params;
    }

    /**
     * Delete shortcode preset
     *
     * @since 4.7
     *
     * @param int $post_id Post must be of type 'vc_settings_preset'
     *
     * @return bool
     */
    public static function deleteSettingsPreset($post_id)
    {
        $post = get_post($post_id);

        if (! $post || 'vc_settings_preset' !== $post->post_type)
        {
            return FALSE;
        }

        return (bool)wp_delete_post($post_id, TRUE);
    }

    /**
     * Return rendered popup menu
     *
     * @since 4.7
     *
     * @param string $shortcode_name
     *
     * @return string
     */
    public static function getRenderedSettingsPresetPopup($shortcode_name)
    {
        $list_vendor_presets = self::listVendorSettingsPresets($shortcode_name);
        $list_presets = self::listSettingsPresets($shortcode_name);

        $default_id = self::getDefaultSettingsPresetId($shortcode_name);

        if (! $default_id)
        {
            $default_id = vc_vendor_preset()->getDefaultId($shortcode_name);
        }

        ob_start();
        vc_include_template(
            apply_filters('vc_render_settings_preset_popup', 'editors/partials/settings_presets_popup.tpl.php'),
            array(
                'list_presets' => array($list_presets, $list_vendor_presets),
                'default_id'   => $default_id,
            )
        );

        $html = ob_get_clean();

        return $html;
    }

    /**
     * Get list of all vendor presets for specific shortcode
     *
     * @since 4.8
     *
     * @param string $shortcode_name
     *
     * @return array E.g. array(id1 => title1, id2 => title2, ...)
     */
    public static function listVendorSettingsPresets($shortcode_name = NULL)
    {
        $list = array();

        if (! $shortcode_name)
        {
            return $list;
        }

        $presets = vc_vendor_preset()->getAll($shortcode_name);

        foreach ($presets as $id => $preset)
        {
            $list[$id] = $preset['title'];
        }

        return $list;
    }

    /**
     * Get list of all presets for specific shortcode
     *
     * @since 4.7
     *
     * @param string $shortcode_name
     *
     * @return array E.g. array(id1 => title1, id2 => title2, ...)
     */
    public static function listSettingsPresets($shortcode_name = NULL)
    {
        $list = array();

        if (! $shortcode_name)
        {
            return $list;
        }

        $args = array(
            'post_type'      => 'vc_settings_preset',
            'orderby'        => array('post_date' => 'DESC'),
            'posts_per_page' => -1,
            'post_mime_type' => self::constructShortcodeMimeType($shortcode_name),
        );

        $posts = get_posts($args);
        foreach ($posts as $post)
        {
            $list[$post->ID] = $post->post_title;
        }

        return $list;
    }
}
