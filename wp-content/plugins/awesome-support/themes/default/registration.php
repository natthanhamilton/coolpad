<?php
/**
 * This is a built-in template file. If you need to customize it, please,
 * DO NOT modify this file directly. Instead, copy it to your theme's directory
 * and then modify the code. If you modify this file directly, your changes
 * will be overwritten during next update of the plugin.
 */
/**
 * Make the post data and the pre-form message global
 */
global $post;
$submit        = get_permalink(wpas_get_option('ticket_list'));
$registration  = wpas_get_option('allow_registrations', 'allow'); // Make sure registrations are open
$redirect_to   = get_permalink($post->ID);
$wrapper_class = 'allow' !== $registration ? 'wpas-login-only' : 'wpas-login-register';
?>

<div class="wpas <?php echo $wrapper_class; ?>">
    <?php do_action('wpas_before_login_form'); ?>

    <form class="wpas-form" id="wpas_form_login" method="post" role="form" action="<?php echo wpas_get_login_url(); ?>">
        <h3><?php _e('Log in', 'awesome-support'); ?></h3>

        <?php
        /* Registrations are not allowed. */
        if ('disallow' === $registration) {
            echo wpas_get_notification_markup('failure',
                                              __('Registrations are currently not allowed.', 'awesome-support'));
        }
        $username = new WPAS_Custom_Field('log', [
            'name' => 'log',
            'args' => [
                'required'    => TRUE,
                'field_type'  => 'text',
                'label'       => __('E-mail or username', 'awesome-support'),
                'placeholder' => __('E-mail or username', 'awesome-support'),
                'sanitize'    => 'sanitize_text_field'
            ]
        ]);
        echo $username->get_output();
        $password = new WPAS_Custom_Field('pwd', [
            'name' => 'pwd',
            'args' => [
                'required'    => TRUE,
                'field_type'  => 'password',
                'label'       => __('Password', 'awesome-support'),
                'placeholder' => __('Password', 'awesome-support'),
                'sanitize'    => 'sanitize_text_field'
            ]
        ]);
        echo $password->get_output();
        /**
         * wpas_after_login_fields hook
         */
        do_action('wpas_after_login_fields');
        $rememberme = new WPAS_Custom_Field('rememberme', [
            'name' => 'rememberme',
            'args' => [
                'required'   => TRUE,
                'field_type' => 'checkbox',
                'sanitize'   => 'sanitize_text_field',
                'options'    => ['1' => __('Remember Me', 'awesome-support')],
            ]
        ]);
        echo $rememberme->get_output();
        wpas_do_field('login', $redirect_to);
        wpas_make_button(__('Log in'), ['onsubmit' => __('Logging In...', 'awesome-support')]);
        printf('<a href="%1$s" class="wpas-forgot-password-link">%2$s</a>',
               wp_lostpassword_url(wpas_get_tickets_list_page_url()),
               esc_html__('Forgot password?', 'awesome-support')); ?>
    </form>
    <?php
    if ('allow' === $registration): ?>

        <form class="wpas-form" id="wpas_form_registration" method="post"
              action="<?php echo get_permalink($post->ID); ?>">
            <h3><?php _e('Register', 'awesome-support'); ?></h3>

            <?php
            $first_name = new WPAS_Custom_Field('first_name', [
                'name' => 'first_name',
                'args' => [
                    'required'    => TRUE,
                    'field_type'  => 'text',
                    'label'       => __('First Name', 'awesome-support'),
                    'placeholder' => __('First Name', 'awesome-support'),
                    'sanitize'    => 'sanitize_text_field'
                ]
            ]);
            echo $first_name->get_output();
            $last_name = new WPAS_Custom_Field('last_name', [
                'name' => 'last_name',
                'args' => [
                    'required'    => TRUE,
                    'field_type'  => 'text',
                    'label'       => __('Last Name', 'awesome-support'),
                    'placeholder' => __('Last Name', 'awesome-support'),
                    'sanitize'    => 'sanitize_text_field'
                ]
            ]);
            echo $last_name->get_output();
            $email = new WPAS_Custom_Field('email', [
                'name' => 'email',
                'args' => [
                    'required'    => TRUE,
                    'field_type'  => 'email',
                    'label'       => __('Email', 'awesome-support'),
                    'placeholder' => __('Email', 'awesome-support'),
                    'sanitize'    => 'sanitize_text_field'
                ]
            ]);
            echo $email->get_output();
            $pwd = new WPAS_Custom_Field('password', [
                'name' => 'password',
                'args' => [
                    'required'    => TRUE,
                    'field_type'  => 'password',
                    'label'       => __('Enter a password', 'awesome-support'),
                    'placeholder' => __('Password', 'awesome-support'),
                    'sanitize'    => 'sanitize_text_field'
                ]
            ]);
            echo $pwd->get_output();
            $showpwd = new WPAS_Custom_Field('pwdshow', [
                'name' => 'pwdshow',
                'args' => [
                    'required'   => FALSE,
                    'field_type' => 'checkbox',
                    'sanitize'   => 'sanitize_text_field',
                    'options'    => ['1' => _x('Show Password', 'Login form', 'awesome-support')],
                ]
            ]);
            echo $showpwd->get_output();
            /**
             * wpas_after_registration_fields hook
             *
             * @Awesome_Support::terms_and_conditions_checkbox()
             */
            do_action('wpas_after_registration_fields');
            wpas_do_field('register', $redirect_to);
            wp_nonce_field('register', 'user_registration', FALSE, TRUE);
            wpas_make_button(__('Create Account', 'awesome-support'),
                             ['onsubmit' => __('Creating Account...', 'awesome-support')]);
            ?>
        </form>
    <?php endif; ?>
</div>