<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TitanFrameworkOptionMulticheckCategories extends TitanFrameworkOptionMulticheck {
    public $defaultSecondarySettings
        = [
            'options'    => [],
            'orderby'    => 'name',
            'order'      => 'ASC',
            'taxonomy'   => 'category',
            'hide_empty' => FALSE,
            'show_count' => FALSE,
        ];

    /*
     * Display for options and meta
     */
    public function display() {
        $args = [
            'orderby'    => $this->settings['orderby'],
            'order'      => $this->settings['order'],
            'taxonomy'   => $this->settings['taxonomy'],
            'hide_empty' => $this->settings['hide_empty'] ? '1' : '0',
        ];
        $categories = get_categories($args);
        $this->settings['options'] = [];
        foreach ($categories as $category) {
            $this->settings['options'][ $category->term_id ] = $category->name . ($this->settings['show_count']
                    ? ' (' . $category->count . ')' : '');
        }
        parent::display();
    }

    /*
     * Display for theme customizer
     */
    public function registerCustomizerControl($wp_customize, $section, $priority = 1) {
        $args = [
            'orderby'    => $this->settings['orderby'],
            'order'      => $this->settings['order'],
            'taxonomy'   => $this->settings['taxonomy'],
            'hide_empty' => $this->settings['hide_empty'] ? '1' : '0',
        ];
        $categories = get_categories($args);
        $this->settings['options'] = [];
        foreach ($categories as $category) {
            $this->settings['options'][ $category->term_id ] = $category->name . ($this->settings['show_count']
                    ? ' (' . $category->count . ')' : '');
        }
        $wp_customize->add_control(new TitanFrameworkOptionMulticheckControl($wp_customize, $this->getID(), [
            'label'       => $this->settings['name'],
            'section'     => $section->settings['id'],
            'settings'    => $this->getID(),
            'description' => $this->settings['desc'],
            'options'     => $this->settings['options'],
            'priority'    => $priority,
        ]));
    }
}
