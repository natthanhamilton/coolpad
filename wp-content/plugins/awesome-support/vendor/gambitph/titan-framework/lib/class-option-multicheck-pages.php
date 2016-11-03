<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TitanFrameworkOptionMulticheckPages extends TitanFrameworkOptionMulticheck {
    private static $allPages;
    public         $defaultSecondarySettings
        = [
            'options' => [],
        ];

    /*
     * Display for options and meta
     */
    public function display() {
        // Remember the pages so as not to perform any more lookups
        if (!isset(self::$allPages)) {
            self::$allPages = get_pages();
        }
        $this->settings['options'] = [];
        foreach (self::$allPages as $page) {
            $title = $page->post_title;
            if (empty($title)) {
                $title = sprintf(__('Untitled %s', TF_I18NDOMAIN), '(ID #' . $page->ID . ')');
            }
            $this->settings['options'][ $page->ID ] = $title;
        }
        parent::display();
    }

    /*
     * Display for theme customizer
     */
    public function registerCustomizerControl($wp_customize, $section, $priority = 1) {
        // Remember the pages so as not to perform any more lookups
        if (!isset(self::$allPages)) {
            self::$allPages = get_pages();
        }
        $this->settings['options'] = [];
        foreach (self::$allPages as $page) {
            $title = $page->post_title;
            if (empty($title)) {
                $title = sprintf(__('Untitled %s', TF_I18NDOMAIN), '(ID #' . $page->ID . ')');
            }
            $this->settings['options'][ $page->ID ] = $title;
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
