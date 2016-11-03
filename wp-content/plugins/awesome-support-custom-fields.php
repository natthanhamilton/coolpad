<?php
/**
 * @package   Awesome Support/Custom Fields
 * @author    ThemeAvenue <web@themeavenue.net>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 *
 * @wordpress-plugin
 * Plugin Name:       Awesome Support: My Custom Fields
 * Plugin URI:        http://getawesomesupport.com
 * Description:       Adds custom fields to the Awesome Support ticket submission form.
 * Version:           0.1.0
 * Author:            ThemeAvenue
 * Author URI:        http://themeavenue.net
 * Text Domain:       wpas
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
add_action('plugins_loaded', 'wpas_user_custom_fields');
/**
 * Register all custom fields after the plugin is safely loaded.
 */
function wpas_user_custom_fields() {
    if (function_exists('wpas_add_custom_field')) {
        wpas_add_custom_field('phone_number', ['title' => 'Phone Number', 'required' => TRUE]);
        wpas_add_custom_field('imei', ['title' => 'Product IMEI Number', 'required' => TRUE]);
        wpas_add_custom_taxonomy('carriers',
                                 ['title' => 'Carriers', 'label' => 'Carrier', 'label_plural' => 'Carriers', 'taxo_std' => TRUE]);
    }
    /* Do NOT write anything after this line */
}