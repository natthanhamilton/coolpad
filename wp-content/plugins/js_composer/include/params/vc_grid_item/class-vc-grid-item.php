<?php
if (! defined('ABSPATH'))
{
    die('-1');
}

/**
 * Class Vc_Grid_Item to build grid item.
 */
class Vc_Grid_Item {
    protected static $templates_added = FALSE;
    protected static $predefined_templates = FALSE;
    protected static $custom_fields_meta_data = FALSE;
    protected $template = '';
    protected $html_template = FALSE;
    protected $post = FALSE;
    protected $grid_atts = array();
    protected $is_end = FALSE;
    protected $shortcodes = FALSE;
    protected $found_variables = FALSE;
    protected $template_id = FALSE;

    /**
     * Used by filter vc_shortcode_set_template_vc_icon to set custom template for vc_icon shortcode.
     *
     * @param $template
     *
     * @return string
     */
    public function addVcIconShortcodesTemplates($template)
    {
        $file = vc_path_dir('TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_icon.php');
        if (is_file($file))
        {
            return $file;
        }

        return $template;
    }

    /**
     * Used by filter vc_shortcode_set_template_vc_button2 to set custom template for vc_button2 shortcode.
     *
     * @param $template
     *
     * @return string
     */
    public function addVcButton2ShortcodesTemplates($template)
    {
        $file = vc_path_dir('TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_button2.php');
        if (is_file($file))
        {
            return $file;
        }

        return $template;
    }

    /**
     * Used by filter vc_shortcode_set_template_vc_single_image to set custom template for vc_single_image shortcode.
     *
     * @param $template
     *
     * @return string
     */
    public function addVcSingleImageShortcodesTemplates($template)
    {
        $file = vc_path_dir('TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_single_image.php');
        if (is_file($file))
        {
            return $file;
        }

        return $template;
    }

    /**
     * Used by filter vc_shortcode_set_template_vc_custom_heading to set custom template for vc_custom_heading
     * shortcode.
     *
     * @param $template
     *
     * @return string
     */
    public function addVcCustomHeadingShortcodesTemplates($template)
    {
        $file = vc_path_dir('TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_custom_heading.php');
        if (is_file($file))
        {
            return $file;
        }

        return $template;
    }

    /**
     * Used by filter vc_shortcode_set_template_vc_button2 to set custom template for vc_button2 shortcode.
     *
     * @param $template
     *
     * @return string
     */
    public function addVcBtnShortcodesTemplates($template)
    {
        $file = vc_path_dir('TEMPLATES_DIR', 'params/vc_grid_item/shortcodes/vc_btn.php');
        if (is_file($file))
        {
            return $file;
        }

        return $template;
    }

    /**
     * Set template which should grid used when vc_grid_item param value is rendered.
     *
     * @param $id
     *
     * @return bool
     */
    public function setTemplateById($id)
    {
        require_once vc_path_dir('PARAMS_DIR', 'vc_grid_item/templates.php');
        if (0 === strlen($id))
        {
            return FALSE;
        }
        if (preg_match('/^\d+$/', $id))
        {
            $post = get_post((int)$id);
            $post && $this->setTemplate($post->post_content, $post->ID);

            return TRUE;
        }
        elseif (FALSE !== ($predefined_template = $this->predefinedTemplate($id)))
        {
            $this->setTemplate($predefined_template['template'], $id);

            return TRUE;
        }

        return FALSE;
    }

    /**
     * Setter for template attribute.
     *
     * @param $template
     * @param $template_id
     */
    public function setTemplate($template, $template_id)
    {
        $this->template = $template;
        $this->template_id = $template_id;
        $this->parseTemplate($template);
    }

    /**
     * Generates html with template's variables for rendering new project.
     *
     * @param $template
     */
    public function parseTemplate($template)
    {
        $this->mapShortcodes();
        WPBMap::addAllMappedShortcodes();
        $attr = ' width="' . $this->gridAttribute('element_width', 12) . '"'
            . ' is_end="' . ('true' === $this->isEnd() ? 'true' : '') . '"';
        $template = preg_replace('/(\[(\[?)vc_gitem\b)/', '$1' . $attr, $template);
        $this->html_template .= do_shortcode($template);
    }

    /**
     * Map shortcodes for vc_grid_item param type.
     */
    public function mapShortcodes()
    {
        // @kludge
        // TODO: refactor with with new way of roles for shortcodes.
        // NEW ROLES like post_type for shortcode and access policies.
        $shortcodes = $this->shortcodes();
        foreach ($shortcodes as $shortcode_settings)
        {
            vc_map($shortcode_settings);
        }
    }

    /**
     * Get shortcodes to build vc grid item templates.
     *
     * @return bool|mixed|void
     */
    public function shortcodes()
    {
        if (FALSE === $this->shortcodes)
        {
            $this->shortcodes = include vc_path_dir('PARAMS_DIR', 'vc_grid_item/shortcodes.php');
            $this->shortcodes = apply_filters('vc_grid_item_shortcodes', $this->shortcodes);
        }
        add_filter('vc_shortcode_set_template_vc_icon', array($this, 'addVcIconShortcodesTemplates'));
        add_filter('vc_shortcode_set_template_vc_button2', array($this, 'addVcButton2ShortcodesTemplates'));
        add_filter('vc_shortcode_set_template_vc_single_image', array(
            $this,
            'addVcSingleImageShortcodesTemplates',
        ));
        add_filter('vc_shortcode_set_template_vc_custom_heading', array(
            $this,
            'addVcCustomHeadingShortcodesTemplates',
        ));
        add_filter('vc_shortcode_set_template_vc_btn', array($this, 'addVcBtnShortcodesTemplates'));

        return $this->shortcodes;
    }

    /**
     * Setter for Grid shortcode attributes.
     *
     * @param $name
     * @param string $default
     *
     * @return string
     */
    public function gridAttribute($name, $default = '')
    {
        return isset($this->grid_atts[$name]) ? $this->grid_atts[$name] : $default;
    }

    /**
     * Checks is the end.
     * @return bool
     */
    public function isEnd()
    {
        return $this->is_end;
    }

    /**
     * @param $id - Predefined templates id
     *
     * @return array|bool
     */
    public static function predefinedTemplate($id)
    {
        $predefined_templates = self::predefinedTemplates();
        if (isset($predefined_templates[$id]['template']))
        {
            return $predefined_templates[$id];
        }

        return FALSE;
    }

    /**
     * Get list of predefined templates.
     *
     * @return bool|mixed
     */
    public static function predefinedTemplates()
    {
        if (FALSE === self::$predefined_templates)
        {
            self::$predefined_templates = apply_filters('vc_grid_item_predefined_templates',
                include vc_path_dir('PARAMS_DIR', 'vc_grid_item/templates.php'));
        }

        return self::$predefined_templates;
    }

    /**
     * Getter for template attribute.
     * @return string
     */
    function template()
    {
        return $this->template;
    }

    /**
     * Add custom css from shortcodes that were mapped for vc grid item.
     * @return string
     */
    public function addShortcodesCustomCss()
    {
        $output = $shortcodes_custom_css = '';
        $id = $this->template_id;
        if (preg_match('/^\d+$/', $id))
        {
            $shortcodes_custom_css = get_post_meta($id, '_wpb_shortcodes_custom_css', TRUE);
        }
        elseif (FALSE !== ($predefined_template = $this->predefinedTemplate($id)))
        {
            $shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss($predefined_template['template']);
        }
        if (! empty($shortcodes_custom_css))
        {
            $shortcodes_custom_css = strip_tags($shortcodes_custom_css);
            $output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
            $output .= $shortcodes_custom_css;
            $output .= '</style>';
        }

        return $output;
    }

    /**
     * Render item by replacing template variables for exact post.
     *
     * @param WP_Post $post
     *
     * @return mixed
     */
    function renderItem(WP_Post $post)
    {
        $pattern = array();
        $replacement = array();
        $this->addAttributesFilters();
        foreach ($this->getTemplateVariables() as $var)
        {
            $pattern[] = '/' . preg_quote($var[0], '/') . '/';
            $replacement[] = preg_replace('/\\$/', '\\\$', $this->attribute($var[1], $post, isset($var[3]) ? trim($var[3]) : ''));
        }

        return preg_replace($pattern, $replacement, do_shortcode($this->html_template));
    }

    /**
     * Adds filters to build templates variables values.
     */
    public function addAttributesFilters()
    {
        require_once vc_path_dir('PARAMS_DIR', 'vc_grid_item/attributes.php');
    }

    /**
     * Get default variables.
     *
     * @return array|bool
     */
    public function getTemplateVariables()
    {
        if (! is_array($this->found_variables))
        {
            preg_match_all($this->templateVariablesRegex(), $this->html_template, $this->found_variables, PREG_SET_ORDER);
        }

        return $this->found_variables;
    }

    /**
     * Regexp for variables.
     * @return string
     */
    public function templateVariablesRegex()
    {
        return '/\{\{' . '\{?' . '\s*' . '([^\}\:]+)(\:([^\}]+))?' . '\s*' . '\}\}' . '\}?/';
    }

    /**
     * Get attribute value for WP_post object.
     *
     * @param $name
     * @param $post
     * @param string $data
     *
     * @return mixed|void
     */
    public function attribute($name, $post, $data = '')
    {
        $data = html_entity_decode($data);
        return apply_filters('vc_gitem_template_attribute_' . trim($name),
            (isset($post->$name) ? $post->$name : ''), array(
                'post' => $post,
                'data' => $data,
            ));
    }

    /**
     * Getter for Grid shortcode attributes.
     *
     * @param $grid_atts
     */
    public function setGridAttributes($grid_atts)
    {
        $this->grid_atts = $grid_atts;
    }

    /**
     * Set that this is last items in the grid. Used for load more button and lazy loading.
     *
     * @param bool $is_end
     */
    public function setIsEnd($is_end = TRUE)
    {
        $this->is_end = $is_end;
    }

    /**
     * Set elements templates.
     */
    protected function setShortcodes()
    {
        foreach ($this->shortcodes() as $tag => $settings)
        {
            add_shortcode($tag, array($this, vc_camel_case($tag . '_shortcode')));
        }
    }
    // }}
}