<?php
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function st_customizer($wp_customize) {
    /**
     * Adds textarea support to the theme customizer
     */
    class St_Customize_Textarea_Control extends WP_Customize_Control {
        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <textarea rows="5"
                          style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
            </label>
            <?php
        }
    }

    // Add logo to Site Title & Tagline Section
    $wp_customize->add_setting('st_site_logo', ['default' => get_template_directory_uri() . '/images/logo.png']);
    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'img-upload',
            [
                'label'    => 'Site Logo',
                'section'  => 'title_tagline',
                'settings' => 'st_site_logo']
        )
    );
    // Add site coypright
    $wp_customize->add_setting('st_copyright', [
        'default' => 'Copyright © A Swish Theme.',
    ]);
    $wp_customize->add_control(new St_Customize_Textarea_Control($wp_customize, 'st_copyright', [
        'label'    => 'Site Copyright',
        'section'  => 'title_tagline',
        'settings' => 'st_copyright',
    ]));
    // Add new styling section
    $wp_customize->add_section(
        'st_styling',
        [
            'title'       => 'Styling',
            'description' => 'Change the look of the theme.',
            'priority'    => 35,
        ]
    );
    // Add link color option
    $wp_customize->add_setting('st_styling_linkcolor', ['default' => '#E36F3C']);
    $wp_customize->add_control(new WP_Customize_Color_Control(
                                   $wp_customize,
                                   'st_styling_linkcolor', [
                                       'label'    => 'Link Color',
                                       'section'  => 'st_styling',
                                       'settings' => 'st_styling_linkcolor',]
                               ));
    // Add theme color option
    $wp_customize->add_setting('st_styling_themecolor', ['default' => '#86b854']);
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'st_styling_themecolor', [
                                                                               'label'    => 'Theme Color',
                                                                               'section'  => 'st_styling',
                                                                               'settings' => 'st_styling_themecolor',]
                               ));
    // Add footer widget layout option
    $wp_customize->add_setting('st_style_footerwidgets', ['default' => '3col']);
    $wp_customize->add_control('st_style_footerwidgets',
                               [
                                   'type'    => 'select',
                                   'label'   => 'Footer Widget Layout',
                                   'section' => 'st_styling',
                                   'choices' => [
                                       'off'  => 'Off',
                                       '2col' => 'Two Columns',
                                       '3col' => 'Three Columns',
                                       '4col' => 'Four Columns',
                                   ],
                               ]
    );
}

add_action('customize_register', 'st_customizer');

