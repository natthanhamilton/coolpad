<?php
require_once(get_theme_root() . '/assets/global-functions.php');


add_filter( 'gadwp_backenditem_uri', 'gadwp_uri_correction', 10, 1 );
add_filter( 'gadwp_frontenditem_uri', 'gadwp_uri_correction', 10, 1 );

function gadwp_uri_correction( $uri ){
    return 'store.coolpad.us' . $uri;
}



add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}

// Remove users able to see the admin bar
add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar()
{
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

/*
 * Add custom fields to woocommerce registration
 */
add_action('woocommerce_register_form_start', 'w4dev_woocommerce_register_form');
function w4dev_woocommerce_register_form()
{
    $fields = array(
        'first_name' => 'First name',
        'last_name' => 'Last name'
    );

    /*
     * PARTNER SITE SPECIFIC DATA
     */
    if (get_current_blog_id() == 4)
        $fields['company'] = 'Company';

    foreach ($fields as $k => $v) {
        ?>
        <p class="form-row form-row-wide">
            <label for="<?php echo $k; ?>"><?php echo $v; ?> <span class="required">*</span></label>
            <input type="text" class="input-text" name="<?php echo $k; ?>" id="<?php echo $k; ?>" value="<?php
            if (!empty($_POST[$k])) echo esc_attr($_POST[$k]); ?>"/>
        </p>
    <?php
    }
    /*
     * Have to custom build select menu
     */
    if (get_current_blog_id() == 4) {
        $fields['mdm'] = 'Who Is Your MDM?';
        $MDMs = [
            "Samuel Senker",
            "Roger Chen",
            "Christopher Salazer",
            "Jason Garrett",
            "Greg Sorensen",
            "Favian Nava",
            "CT Green",
            "Maria Madsen"
        ]
        ?>
        <p class="form-row form-row-wide">
            <label for="mdm"><?= $fields['mdm'] ?> <span class="required">*</span></label>
            <select id="mdm" name="mdm">
                <?php foreach ($MDMs as $mdm) echo '<option value="' . $mdm . '">' . $mdm . '</option>'; ?>
            </select>
        </p>
        <?php
    }
}

add_filter('woocommerce_new_customer_data', 'w4dev_woocommerce_new_customer_data');
function w4dev_woocommerce_new_customer_data($data)
{
    /**
     * generate username from first/last name field input
     * only if username field is inactive
     **/
    if ('no' !== get_option('woocommerce_registration_generate_username') || !empty($username)) {
        $username = sanitize_user(wc_clean($_POST['first_name']));
        if (username_exists($username)) {
            $username .= sanitize_user(wc_clean($_POST['last_name']));
        }

        // Ensure username is unique
        $append = 1;
        $o_username = $username;

        while (username_exists($username)) {
            $username = $o_username . $append;
            $append++;
        }
        $data['user_login'] = $username;
    }

    /**
     * wordpress will automatically insert this information's into database,
     * we just need to include it here
     **/
    $data['first_name'] = wc_clean($_POST['first_name']);
    $data['last_name'] = wc_clean($_POST['last_name']);

    return $data;
}
function woo_remove_product_tabs( $tabs ) {

    if (isset($tabs['additional_information'])) unset( $tabs['additional_information'] );;
    if (isset($tabs['ux_custom_tab'])) $tabs['ux_custom_tab']['priority'] = 10;

    return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );