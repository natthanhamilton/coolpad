<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Local Pickup Shipping Method.
 *
 * This class is here for backwards commpatility for methods existing before zones existed.
 *
 * @deprecated     2.6.0
 * @version        2.3.0
 * @package        WooCommerce/Classes/Shipping
 * @author         WooThemes
 */
class WC_Shipping_Legacy_Local_Pickup extends WC_Shipping_Method {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id           = 'legacy_local_pickup';
		$this->method_title = __('Local Pickup (Legacy)', 'woocommerce');
		$this->method_description
		                    = sprintf(__('<strong>This method is deprecated in 2.6.0 and will be removed in future versions - we recommend disabling it and instead setting up a new rate within your <a href="%s">Shipping Zones</a>.</strong>',
		                                 'woocommerce'), admin_url('admin.php?page=wc-settings&tab=shipping'));
		$this->init();
	}

	/**
	 * init function.
	 */
	public function init() {
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables
		$this->enabled      = $this->get_option('enabled');
		$this->title        = $this->get_option('title');
		$this->codes        = $this->get_option('codes');
		$this->availability = $this->get_option('availability');
		$this->countries    = $this->get_option('countries');
		// Actions
		add_action('woocommerce_update_options_shipping_' . $this->id, [$this, 'process_admin_options']);
	}

	/**
	 * init_form_fields function.
	 */
	public function init_form_fields() {
		$this->form_fields = [
			'enabled'      => [
				'title'   => __('Enable', 'woocommerce'),
				'type'    => 'checkbox',
				'label'   => __('Once disabled, this legacy method will no longer be available.', 'woocommerce'),
				'default' => 'no'
			],
			'title'        => [
				'title'       => __('Title', 'woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
				'default'     => __('Local Pickup', 'woocommerce'),
				'desc_tip'    => TRUE,
			],
			'codes'        => [
				'title'       => __('Allowed ZIP/Post Codes', 'woocommerce'),
				'type'        => 'text',
				'desc_tip'    => __('What ZIP/post codes are available for local pickup?', 'woocommerce'),
				'default'     => '',
				'description' => __('Separate codes with a comma. Accepts wildcards, e.g. <code>P*</code> will match a postcode of PE30. Also accepts a pattern, e.g. <code>NG1___</code> would match NG1 1AA but not NG10 1AA',
				                    'woocommerce'),
				'placeholder' => 'e.g. 12345, 56789'
			],
			'availability' => [
				'title'   => __('Method availability', 'woocommerce'),
				'type'    => 'select',
				'default' => 'all',
				'class'   => 'availability wc-enhanced-select',
				'options' => [
					'all'      => __('All allowed countries', 'woocommerce'),
					'specific' => __('Specific Countries', 'woocommerce')
				]
			],
			'countries'    => [
				'title'             => __('Specific Countries', 'woocommerce'),
				'type'              => 'multiselect',
				'class'             => 'wc-enhanced-select',
				'css'               => 'width: 450px;',
				'default'           => '',
				'options'           => WC()->countries->get_shipping_countries(),
				'custom_attributes' => [
					'data-placeholder' => __('Select some countries', 'woocommerce')
				]
			]
		];
	}

	/**
	 * See if the method is available.
	 *
	 * @param array $package
	 *
	 * @return bool
	 */
	public function is_available($package) {
		$is_available = "yes" === $this->enabled;
		if ($is_available && $this->get_valid_postcodes()) {
			$is_available = $this->is_valid_postcode($package['destination']['postcode'],
			                                         $package['destination']['country']);
		}
		if ($is_available) {
			if ($this->availability === 'specific') {
				$ship_to_countries = $this->countries;
			} else {
				$ship_to_countries = array_keys(WC()->countries->get_shipping_countries());
			}
			if (is_array($ship_to_countries) && !in_array($package['destination']['country'], $ship_to_countries)) {
				$is_available = FALSE;
			}
		}

		return apply_filters('woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package);
	}

	/**
	 * Get postcodes for this method.
	 *
	 * @return array
	 */
	public function get_valid_postcodes() {
		$codes = [];
		if ($this->codes != '') {
			foreach (explode(',', $this->codes) as $code) {
				$codes[] = strtoupper(trim($code));
			}
		}

		return $codes;
	}

	/**
	 * See if a given postcode matches valid postcodes.
	 *
	 * @param  string postcode
	 * @param  string country code
	 *
	 * @return boolean
	 */
	public function is_valid_postcode($postcode, $country) {
		$codes              = $this->get_valid_postcodes();
		$postcode           = $this->clean($postcode);
		$formatted_postcode = wc_format_postcode($postcode, $country);
		if (in_array($postcode, $codes) || in_array($formatted_postcode, $codes)) {
			return TRUE;
		}
		// Pattern matching
		foreach ($codes as $c) {
			$pattern = '/^' . str_replace('_', '[0-9a-zA-Z]', preg_quote($c)) . '$/i';
			if (preg_match($pattern, $postcode)) {
				return TRUE;
			}
		}
		// Wildcard search
		$wildcard_postcode = $formatted_postcode . '*';
		$postcode_length   = strlen($formatted_postcode);
		for ($i = 0; $i < $postcode_length; $i++) {
			if (in_array($wildcard_postcode, $codes)) {
				return TRUE;
			}
			$wildcard_postcode = substr($wildcard_postcode, 0, -2) . '*';
		}

		return FALSE;
	}

	/**
	 * clean function.
	 *
	 * @access public
	 *
	 * @param mixed $code
	 *
	 * @return string
	 */
	public function clean($code) {
		return str_replace('-', '', sanitize_title($code)) . (strstr($code, '*') ? '*' : '');
	}

	/**
	 * Process and redirect if disabled.
	 */
	public function process_admin_options() {
		parent::process_admin_options();
		if ('no' === $this->settings['enabled']) {
			wp_redirect(admin_url('admin.php?page=wc-settings&tab=shipping&section=options'));
			exit;
		}
	}

	/**
	 * Return the name of the option in the WP DB.
	 *
	 * @since 2.6.0
	 * @return string
	 */
	public function get_option_key() {
		return $this->plugin_id . 'local_pickup' . '_settings';
	}

	/**
	 * calculate_shipping function.
	 */
	public function calculate_shipping($package = []) {
		$rate = [
			'id'      => $this->id,
			'label'   => $this->title,
			'package' => $package,
		];
		$this->add_rate($rate);
	}
}
