<?php
if (! defined('ABSPATH'))
{
    die('-1');
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $el_class
 * @var $content - shortcode content
 * Shortcode class
 * @var $this WPBakeryShortCode_VC_Wp_Text
 */
$el_class = '';
$output = '';
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
$atts['filter'] = TRUE; //Hack to make sure that <p> added
extract($atts);

$el_class = $this->getExtraClass($el_class);

$output = '<div class="vc_wp_text wpb_content_element' . esc_attr($el_class) . '">';
$type = 'WP_Widget_Text';
$args = array();
if (strlen($content) > 0)
{
    $atts['text'] = $content;
}
global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget
if (is_object($wp_widget_factory) && isset($wp_widget_factory->widgets, $wp_widget_factory->widgets[$type]))
{
    ob_start();
    the_widget($type, $atts, $args);
    $output .= ob_get_clean();

    $output .= '</div>';

    echo $output;
}
else
{
    echo $this->debugComment('Widget ' . esc_attr($type) . 'Not found in : vc_wp_text');
}
