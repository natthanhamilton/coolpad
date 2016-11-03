<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class TitanFrameworkOptionRadio extends TitanFrameworkOption {
    public $defaultSecondarySettings
        = [
            'options' => [],
        ];

    /*
     * Display for options and meta
     */
    public function display() {
        $this->echoOptionHeader(TRUE);
        echo '<fieldset>';
        foreach ($this->settings['options'] as $value => $label) {
            printf('<label for="%s"><input id="%s" type="radio" name="%s" value="%s" %s/> %s</label><br>',
                   $this->getID() . $value,
                   $this->getID() . $value,
                   $this->getID(),
                   esc_attr($value),
                   checked($this->getValue(), $value, FALSE),
                   $label
            );
        }
        echo '</fieldset>';
        $this->echoOptionFooter(FALSE);
    }

    /*
     * Display for theme customizer
     */
    public function registerCustomizerControl($wp_customize, $section, $priority = 1) {
        $wp_customize->add_control(new TitanFrameworkCustomizeControl($wp_customize, $this->getID(), [
            'label'       => $this->settings['name'],
            'section'     => $section->settings['id'],
            'settings'    => $this->getID(),
            'choices'     => $this->settings['options'],
            'type'        => 'radio',
            'description' => $this->settings['desc'],
            'priority'    => $priority,
        ]));
    }
}
