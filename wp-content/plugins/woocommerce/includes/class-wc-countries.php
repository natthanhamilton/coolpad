<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * WooCommerce countries
 *
 * The WooCommerce countries class stores country/state data.
 *
 * @class       WC_Countries
 * @version     2.3.0
 * @package     WooCommerce/Classes
 * @category    Class
 * @author      WooThemes
 */
class WC_Countries {
	/** @var array Array of locales */
	public $locale;
	/** @var array Array of address formats for locales */
	public $address_formats;

	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @param  mixed $key
	 *
	 * @return mixed
	 */
	public function __get($key) {
		if ('countries' == $key) {
			return $this->get_countries();
		} elseif ('states' == $key) {
			return $this->get_states();
		}
	}

	/**
	 * Get all countries.
	 *
	 * @return array
	 */
	public function get_countries() {
		if (empty($this->countries)) {
			$this->countries = apply_filters('woocommerce_countries',
				include(WC()->plugin_path() . '/i18n/countries.php'));
			if (apply_filters('woocommerce_sort_countries', TRUE)) {
				asort($this->countries);
			}
		}

		return $this->countries;
	}

	/**
	 * Get the states for a country.
	 *
	 * @param  string $cc country code
	 *
	 * @return array of states
	 */
	public function get_states($cc = NULL) {
		if (empty($this->states)) {
			$this->load_country_states();
		}
		if (!is_null($cc)) {
			return isset($this->states[ $cc ]) ? $this->states[ $cc ] : FALSE;
		} else {
			return $this->states;
		}
	}

	/**
	 * Load the states.
	 */
	public function load_country_states() {
		global $states;
		// States set to array() are blank i.e. the country has no use for the state field.
		$states = [
			'AF' => [],
			'AT' => [],
			'AX' => [],
			'BE' => [],
			'BI' => [],
			'CZ' => [],
			'DE' => [],
			'DK' => [],
			'EE' => [],
			'FI' => [],
			'FR' => [],
			'IS' => [],
			'IL' => [],
			'KR' => [],
			'NL' => [],
			'NO' => [],
			'PL' => [],
			'PT' => [],
			'SG' => [],
			'SK' => [],
			'SI' => [],
			'LK' => [],
			'SE' => [],
			'VN' => [],
		];
		// Load only the state files the shop owner wants/needs.
		$allowed = array_merge($this->get_allowed_countries(), $this->get_shipping_countries());
		if (!empty($allowed)) {
			foreach ($allowed as $code => $country) {
				if (!isset($states[ $code ]) && file_exists(WC()->plugin_path() . '/i18n/states/' . $code . '.php')) {
					include(WC()->plugin_path() . '/i18n/states/' . $code . '.php');
				}
			}
		}
		$this->states = apply_filters('woocommerce_states', $states);
	}

	/**
	 * Get the allowed countries for the store.
	 *
	 * @return array
	 */
	public function get_allowed_countries() {
		if ('all' === get_option('woocommerce_allowed_countries')) {
			return $this->countries;
		}
		if ('all_except' === get_option('woocommerce_allowed_countries')) {
			$except_countries = get_option('woocommerce_all_except_countries', []);
			if (!$except_countries) {
				return $this->countries;
			} else {
				$all_except_countries = $this->countries;
				foreach ($except_countries as $country) {
					unset($all_except_countries[ $country ]);
				}

				return apply_filters('woocommerce_countries_allowed_countries', $all_except_countries);
			}
		}
		$countries = [];
		$raw_countries = get_option('woocommerce_specific_allowed_countries', []);
		if ($raw_countries) {
			foreach ($raw_countries as $country) {
				$countries[ $country ] = $this->countries[ $country ];
			}
		}

		return apply_filters('woocommerce_countries_allowed_countries', $countries);
	}

	/**
	 * Get the countries you ship to.
	 *
	 * @return array
	 */
	public function get_shipping_countries() {
		if ('' === get_option('woocommerce_ship_to_countries')) {
			return $this->get_allowed_countries();
		}
		if ('all' === get_option('woocommerce_ship_to_countries')) {
			return $this->countries;
		}
		$countries = [];
		$raw_countries = get_option('woocommerce_specific_ship_to_countries');
		if ($raw_countries) {
			foreach ($raw_countries as $country) {
				$countries[ $country ] = $this->countries[ $country ];
			}
		}

		return apply_filters('woocommerce_countries_shipping_countries', $countries);
	}

	/**
	 * Get continent code for a country code.
	 *
	 * @since 2.6.0
	 *
	 * @param string $cc string
	 *
	 * @return string
	 */
	public function get_continent_code_for_country($cc) {
		$cc                 = trim(strtoupper($cc));
		$continents         = $this->get_continents();
		$continents_and_ccs = wp_list_pluck($continents, 'countries');
		foreach ($continents_and_ccs as $continent_code => $countries) {
			if (FALSE !== array_search($cc, $countries)) {
				return $continent_code;
			}
		}

		return '';
	}

	/**
	 * Get all continents.
	 *
	 * @return array
	 */
	public function get_continents() {
		if (empty($this->continents)) {
			$this->continents = apply_filters('woocommerce_continents',
				include(WC()->plugin_path() . '/i18n/continents.php'));
		}

		return $this->continents;
	}

	/**
	 * Get the base state for the store.
	 *
	 * @return string
	 */
	public function get_base_state() {
		$default = wc_get_base_location();

		return apply_filters('woocommerce_countries_base_state', $default['state']);
	}

	/**
	 * Get the base city for the store.
	 *
	 * @return string
	 */
	public function get_base_city() {
		return apply_filters('woocommerce_countries_base_city', '');
	}

	/**
	 * Get the base postcode for the store.
	 *
	 * @return string
	 */
	public function get_base_postcode() {
		return apply_filters('woocommerce_countries_base_postcode', '');
	}

	/**
	 * Get shipping country states.
	 *
	 * @return array
	 */
	public function get_shipping_country_states() {
		if (get_option('woocommerce_ship_to_countries') == '') {
			return $this->get_allowed_country_states();
		}
		if (get_option('woocommerce_ship_to_countries') !== 'specific') {
			return $this->states;
		}
		$states = [];
		$raw_countries = get_option('woocommerce_specific_ship_to_countries');
		if ($raw_countries) {
			foreach ($raw_countries as $country) {
				if (!empty($this->states[ $country ])) {
					$states[ $country ] = $this->states[ $country ];
				}
			}
		}

		return apply_filters('woocommerce_countries_shipping_country_states', $states);
	}

	/**
	 * Get allowed country states.
	 *
	 * @return array
	 */
	public function get_allowed_country_states() {
		if (get_option('woocommerce_allowed_countries') !== 'specific') {
			return $this->states;
		}
		$states = [];
		$raw_countries = get_option('woocommerce_specific_allowed_countries');
		if ($raw_countries) {
			foreach ($raw_countries as $country) {
				if (isset($this->states[ $country ])) {
					$states[ $country ] = $this->states[ $country ];
				}
			}
		}

		return apply_filters('woocommerce_countries_allowed_country_states', $states);
	}

	/**
	 * Gets the correct string for shipping - either 'to the' or 'to'
	 *
	 * @return string
	 */
	public function shipping_to_prefix($country_code = '') {
		$country_code = $country_code ? $country_code : WC()->customer->get_shipping_country();
		$countries    = ['GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF'];
		$return       = in_array($country_code, $countries) ? __('to the', 'woocommerce') : __('to', 'woocommerce');

		return apply_filters('woocommerce_countries_shipping_to_prefix', $return, $country_code);
	}

	/**
	 * Prefix certain countries with 'the'
	 *
	 * @return string
	 */
	public function estimated_for_prefix($country_code = '') {
		$country_code = $country_code ? $country_code : $this->get_base_country();
		$countries    = ['GB', 'US', 'AE', 'CZ', 'DO', 'NL', 'PH', 'USAF'];
		$return       = in_array($country_code, $countries) ? __('the', 'woocommerce') . ' ' : '';

		return apply_filters('woocommerce_countries_estimated_for_prefix', $return, $country_code);
	}

	/**
	 * Get the base country for the store.
	 *
	 * @return string
	 */
	public function get_base_country() {
		$default = wc_get_base_location();

		return apply_filters('woocommerce_countries_base_country', $default['country']);
	}

	/**
	 * Correctly name tax in some countries VAT on the frontend.
	 *
	 * @return string
	 */
	public function tax_or_vat() {
		$return = in_array($this->get_base_country(), $this->get_european_union_countries('eu_vat')) ? __('VAT',
		                                                                                                  'woocommerce')
			: __('Tax', 'woocommerce');

		return apply_filters('woocommerce_countries_tax_or_vat', $return);
	}

	/**
	 * Gets an array of countries in the EU.
	 *
	 * MC (monaco) and IM (isle of man, part of UK) also use VAT.
	 *
	 * @param  $type Type of countries to retrieve. Blank for EU member countries. eu_vat for EU VAT countries.
	 *
	 * @return string[]
	 */
	public function get_european_union_countries($type = '') {
		$countries
			= ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HU', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK'];
		if ('eu_vat' === $type) {
			$countries[] = 'MC';
			$countries[] = 'IM';
		}

		return $countries;
	}

	/**
	 * Include the Inc Tax label.
	 *
	 * @return string
	 */
	public function inc_tax_or_vat() {
		$return = in_array($this->get_base_country(), $this->get_european_union_countries('eu_vat')) ? __('(incl. VAT)',
		                                                                                                  'woocommerce')
			: __('(incl. tax)', 'woocommerce');

		return apply_filters('woocommerce_countries_inc_tax_or_vat', $return);
	}

	/**
	 * Include the Ex Tax label.
	 *
	 * @return string
	 */
	public function ex_tax_or_vat() {
		$return = in_array($this->get_base_country(), $this->get_european_union_countries('eu_vat')) ? __('(ex. VAT)',
		                                                                                                  'woocommerce')
			: __('(ex. tax)', 'woocommerce');

		return apply_filters('woocommerce_countries_ex_tax_or_vat', $return);
	}

	/**
	 * Outputs the list of countries and states for use in dropdown boxes.
	 *
	 * @param string $selected_country (default: '')
	 * @param string $selected_state   (default: '')
	 * @param bool   $escape           (default: false)
	 * @param bool   $escape           (default: false)
	 */
	public function country_dropdown_options($selected_country = '', $selected_state = '', $escape = FALSE) {
		if ($this->countries) {
			foreach ($this->countries as $key => $value) :
				if ($states = $this->get_states($key)) :
					echo '<optgroup label="' . esc_attr($value) . '">';
					foreach ($states as $state_key => $state_value) :
						echo '<option value="' . esc_attr($key) . ':' . $state_key . '"';
						if ($selected_country == $key && $selected_state == $state_key) {
							echo ' selected="selected"';
						}
						echo '>' . $value . ' &mdash; ' . ($escape ? esc_js($state_value) : $state_value) . '</option>';
					endforeach;
					echo '</optgroup>';
				else :
					echo '<option';
					if ($selected_country == $key && $selected_state == '*') {
						echo ' selected="selected"';
					}
					echo ' value="' . esc_attr($key) . '">' . ($escape ? esc_js($value) : $value) . '</option>';
				endif;
			endforeach;
		}
	}

	/**
	 * Get country address format.
	 *
	 * @param  array $args (default: array())
	 *
	 * @return string address
	 */
	public function get_formatted_address($args = []) {
		$default_args = [
			'first_name' => '',
			'last_name'  => '',
			'company'    => '',
			'address_1'  => '',
			'address_2'  => '',
			'city'       => '',
			'state'      => '',
			'postcode'   => '',
			'country'    => ''
		];
		$args = array_map('trim', wp_parse_args($args, $default_args));
		extract($args);
		// Get all formats
		$formats = $this->get_address_formats();
		// Get format for the address' country
		$format = ($country && isset($formats[ $country ])) ? $formats[ $country ] : $formats['default'];
		// Handle full country name
		$full_country = (isset($this->countries[ $country ])) ? $this->countries[ $country ] : $country;
		// Country is not needed if the same as base
		if ($country == $this->get_base_country() && !apply_filters('woocommerce_formatted_address_force_country_display',
		                                                            FALSE)
		) {
			$format = str_replace('{country}', '', $format);
		}
		// Handle full state name
		$full_state = ($country && $state && isset($this->states[ $country ][ $state ]))
			? $this->states[ $country ][ $state ] : $state;
		// Substitute address parts into the string
		$replace = array_map('esc_html', apply_filters('woocommerce_formatted_address_replacements', [
			'{first_name}'       => $first_name,
			'{last_name}'        => $last_name,
			'{name}'             => $first_name . ' ' . $last_name,
			'{company}'          => $company,
			'{address_1}'        => $address_1,
			'{address_2}'        => $address_2,
			'{city}'             => $city,
			'{state}'            => $full_state,
			'{postcode}'         => $postcode,
			'{country}'          => $full_country,
			'{first_name_upper}' => strtoupper($first_name),
			'{last_name_upper}'  => strtoupper($last_name),
			'{name_upper}'       => strtoupper($first_name . ' ' . $last_name),
			'{company_upper}'    => strtoupper($company),
			'{address_1_upper}'  => strtoupper($address_1),
			'{address_2_upper}'  => strtoupper($address_2),
			'{city_upper}'       => strtoupper($city),
			'{state_upper}'      => strtoupper($full_state),
			'{state_code}'       => strtoupper($state),
			'{postcode_upper}'   => strtoupper($postcode),
			'{country_upper}'    => strtoupper($full_country),
		], $args));
		$formatted_address = str_replace(array_keys($replace), $replace, $format);
		// Clean up white space
		$formatted_address = preg_replace('/  +/', ' ', trim($formatted_address));
		$formatted_address = preg_replace('/\n\n+/', "\n", $formatted_address);
		// Break newlines apart and remove empty lines/trim commas and white space
		$formatted_address = array_filter(array_map([$this, 'trim_formatted_address_line'],
		                                            explode("\n", $formatted_address)));
		// Add html breaks
		$formatted_address = implode('<br/>', $formatted_address);

		// We're done!
		return $formatted_address;
	}

	/**
	 * Get country address formats.
	 *
	 * @return array
	 */
	public function get_address_formats() {
		if (empty($this->address_formats)) {
			// Common formats
			$postcode_before_city = "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";
			// Define address formats
			$this->address_formats = apply_filters('woocommerce_localisation_address_formats', [
				'default' => "{name}\n{company}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}",
				'AU'      => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'AT'      => $postcode_before_city,
				'BE'      => $postcode_before_city,
				'CA'      => "{company}\n{name}\n{address_1}\n{address_2}\n{city} {state} {postcode}\n{country}",
				'CH'      => $postcode_before_city,
				'CL'      => "{company}\n{name}\n{address_1}\n{address_2}\n{state}\n{postcode} {city}\n{country}",
				'CN'      => "{country} {postcode}\n{state}, {city}, {address_2}, {address_1}\n{company}\n{name}",
				'CZ'      => $postcode_before_city,
				'DE'      => $postcode_before_city,
				'EE'      => $postcode_before_city,
				'FI'      => $postcode_before_city,
				'DK'      => $postcode_before_city,
				'FR'      => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode} {city_upper}\n{country}",
				'HK'      => "{company}\n{first_name} {last_name_upper}\n{address_1}\n{address_2}\n{city_upper}\n{state_upper}\n{country}",
				'HU'      => "{name}\n{company}\n{city}\n{address_1}\n{address_2}\n{postcode}\n{country}",
				'IN'      => "{company}\n{name}\n{address_1}\n{address_2}\n{city} - {postcode}\n{state}, {country}",
				'IS'      => $postcode_before_city,
				'IT'      => "{company}\n{name}\n{address_1}\n{address_2}\n{postcode}\n{city}\n{state_upper}\n{country}",
				'JP'      => "{postcode}\n{state}{city}{address_1}\n{address_2}\n{company}\n{last_name} {first_name}\n{country}",
				'TW'      => "{company}\n{last_name} {first_name}\n{address_1}\n{address_2}\n{state}, {city} {postcode}\n{country}",
				'LI'      => $postcode_before_city,
				'NL'      => $postcode_before_city,
				'NZ'      => "{name}\n{company}\n{address_1}\n{address_2}\n{city} {postcode}\n{country}",
				'NO'      => $postcode_before_city,
				'PL'      => $postcode_before_city,
				'SK'      => $postcode_before_city,
				'SI'      => $postcode_before_city,
				'ES'      => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}",
				'SE'      => $postcode_before_city,
				'TR'      => "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city} {state}\n{country}",
				'US'      => "{name}\n{company}\n{address_1}\n{address_2}\n{city}, {state_code} {postcode}\n{country}",
				'VN'      => "{name}\n{company}\n{address_1}\n{city}\n{country}",
			]);
		}

		return $this->address_formats;
	}

	/**
	 * Get JS selectors for fields which are shown/hidden depending on the locale.
	 *
	 * @return array
	 */
	public function get_country_locale_field_selectors() {
		$locale_fields = [
			'address_1' => '#billing_address_1_field, #shipping_address_1_field',
			'address_2' => '#billing_address_2_field, #shipping_address_2_field',
			'state'     => '#billing_state_field, #shipping_state_field, #calc_shipping_state_field',
			'postcode'  => '#billing_postcode_field, #shipping_postcode_field, #calc_shipping_postcode_field',
			'city'      => '#billing_city_field, #shipping_city_field, #calc_shipping_city_field',
		];

		return apply_filters('woocommerce_country_locale_field_selectors', $locale_fields);
	}

	/**
	 * Apply locale and get address fields.
	 *
	 * @param  mixed  $country (default: '')
	 * @param  string $type    (default: 'billing_')
	 *
	 * @return array
	 */
	public function get_address_fields($country = '', $type = 'billing_') {
		if (!$country) {
			$country = $this->get_base_country();
		}
		$fields = $this->get_default_address_fields();
		$locale = $this->get_country_locale();
		if (isset($locale[ $country ])) {
			$fields = wc_array_overlay($fields, $locale[ $country ]);
		}
		// Prepend field keys
		$address_fields = [];
		foreach ($fields as $key => $value) {
			$keys                           = array_keys($fields);
			$address_fields[ $type . $key ] = $value;
			// Add email and phone after company or last
			if ('billing_' === $type && ('company' === $key || (!array_key_exists('company',
			                                                                      $fields) && $key === end($keys)))
			) {
				$address_fields['billing_email'] = [
					'label'        => __('Email Address', 'woocommerce'),
					'required'     => TRUE,
					'type'         => 'email',
					'class'        => ['form-row-first'],
					'validate'     => ['email'],
					'autocomplete' => 'email',
				];
				$address_fields['billing_phone'] = [
					'label'        => __('Phone', 'woocommerce'),
					'required'     => TRUE,
					'type'         => 'tel',
					'class'        => ['form-row-last'],
					'clear'        => TRUE,
					'validate'     => ['phone'],
					'autocomplete' => 'tel',
				];
			}
		}
		$address_fields = apply_filters('woocommerce_' . $type . 'fields', $address_fields, $country);

		return $address_fields;
	}

	/**
	 * Returns the fields we show by default. This can be filtered later on.
	 *
	 * @return array
	 */
	public function get_default_address_fields() {
		$fields = [
			'first_name' => [
				'label'        => __('First Name', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-first'],
				'autocomplete' => 'given-name',
			],
			'last_name'  => [
				'label'        => __('Last Name', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-last'],
				'clear'        => TRUE,
				'autocomplete' => 'family-name',
			],
			'company'    => [
				'label'        => __('Company Name', 'woocommerce'),
				'class'        => ['form-row-wide'],
				'autocomplete' => 'organization',
			],
			'country'    => [
				'type'         => 'country',
				'label'        => __('Country', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-wide', 'address-field', 'update_totals_on_change'],
				'autocomplete' => 'country',
			],
			'address_1'  => [
				'label'        => __('Address', 'woocommerce'),
				'placeholder'  => _x('Street address', 'placeholder', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-wide', 'address-field'],
				'autocomplete' => 'address-line1',
			],
			'address_2'  => [
				'placeholder'  => _x('Apartment, suite, unit etc. (optional)', 'placeholder', 'woocommerce'),
				'class'        => ['form-row-wide', 'address-field'],
				'required'     => FALSE,
				'autocomplete' => 'address-line2',
			],
			'city'       => [
				'label'        => __('Town / City', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-wide', 'address-field'],
				'autocomplete' => 'address-level2',
			],
			'state'      => [
				'type'         => 'state',
				'label'        => __('State / County', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-first', 'address-field'],
				'validate'     => ['state'],
				'autocomplete' => 'address-level1',
			],
			'postcode'   => [
				'label'        => __('Postcode / ZIP', 'woocommerce'),
				'required'     => TRUE,
				'class'        => ['form-row-last', 'address-field'],
				'clear'        => TRUE,
				'validate'     => ['postcode'],
				'autocomplete' => 'postal-code',
			],
		];

		return apply_filters('woocommerce_default_address_fields', $fields);
	}

	/**
	 * Get country locale settings.
	 *
	 * @return array
	 * @todo  [2.4] Check select2 4.0.0 compatibility with `placeholder` attribute and uncomment relevant lines.
	 *        https://github.com/woothemes/woocommerce/issues/7729
	 */
	public function get_country_locale() {
		if (empty($this->locale)) {
			// Locale information used by the checkout
			$this->locale = apply_filters('woocommerce_get_country_locale', [
				'AE' => [
					'postcode' => [
						'required' => FALSE,
						'hidden'   => TRUE
					],
				],
				'AF' => [
					'state' => [
						'required' => FALSE,
					],
				],
				'AT' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'AU' => [
					'city'     => [
						'label' => __('Suburb', 'woocommerce'),
					],
					'postcode' => [
						'label' => __('Postcode', 'woocommerce'),
					],
					'state'    => [
						'label' => __('State', 'woocommerce'),
					]
				],
				'AX' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE,
					],
				],
				'BD' => [
					'postcode' => [
						'required' => FALSE
					],
					'state'    => [
						'label' => __('District', 'woocommerce'),
					]
				],
				'BE' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE,
						'label'    => __('Province', 'woocommerce'),
					],
				],
				'BI' => [
					'state' => [
						'required' => FALSE,
					],
				],
				'BO' => [
					'postcode' => [
						'required' => FALSE,
						'hidden'   => TRUE
					],
				],
				'BS' => [
					'postcode' => [
						'required' => FALSE,
						'hidden'   => TRUE
					],
				],
				'CA' => [
					'state' => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'CH' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'label'    => __('Canton', 'woocommerce'),
						'required' => FALSE
					]
				],
				'CL' => [
					'city'     => [
						'required' => TRUE,
					],
					'postcode' => [
						'required' => FALSE
					],
					'state'    => [
						'label' => __('Region', 'woocommerce'),
					]
				],
				'CN' => [
					'state' => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'CO' => [
					'postcode' => [
						'required' => FALSE
					]
				],
				'CZ' => [
					'state' => [
						'required' => FALSE
					]
				],
				'DE' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'DK' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'EE' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'FI' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'FR' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'HK' => [
					'postcode' => [
						'required' => FALSE
					],
					'city'     => [
						'label' => __('Town / District', 'woocommerce'),
					],
					'state'    => [
						'label' => __('Region', 'woocommerce'),
					]
				],
				'HU' => [
					'state' => [
						'label' => __('County', 'woocommerce'),
					]
				],
				'ID' => [
					'state' => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'IE' => [
					'postcode' => [
						'required' => FALSE,
						'label'    => __('Postcode', 'woocommerce'),
					],
				],
				'IS' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'IL' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'IT' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => TRUE,
						'label'    => __('Province', 'woocommerce'),
					]
				],
				'JP' => [
					'state' => [
						'label' => __('Prefecture', 'woocommerce')
					]
				],
				'KR' => [
					'state' => [
						'required' => FALSE
					]
				],
				'NL' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE,
						'label'    => __('Province', 'woocommerce'),
					]
				],
				'NZ' => [
					'postcode' => [
						'label' => __('Postcode', 'woocommerce')
					],
					'state'    => [
						'required' => FALSE,
						'label'    => __('Region', 'woocommerce')
					]
				],
				'NO' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'NP' => [
					'state'    => [
						'label' => __('State / Zone', 'woocommerce'),
					],
					'postcode' => [
						'required' => FALSE
					]
				],
				'PL' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'PT' => [
					'state' => [
						'required' => FALSE
					]
				],
				'RO' => [
					'state' => [
						'required' => FALSE
					]
				],
				'SG' => [
					'state' => [
						'required' => FALSE
					]
				],
				'SK' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'SI' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'ES' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'LI' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'label'    => __('Municipality', 'woocommerce'),
						'required' => FALSE
					]
				],
				'LK' => [
					'state' => [
						'required' => FALSE
					]
				],
				'SE' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					]
				],
				'TR' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'US' => [
					'postcode' => [
						'label' => __('ZIP', 'woocommerce'),
					],
					'state'    => [
						'label' => __('State', 'woocommerce'),
					]
				],
				'GB' => [
					'postcode' => [
						'label' => __('Postcode', 'woocommerce'),
					],
					'state'    => [
						'label'    => __('County', 'woocommerce'),
						'required' => FALSE
					]
				],
				'VN' => [
					'postcode_before_city' => TRUE,
					'state'                => [
						'required' => FALSE
					],
					'postcode'             => [
						'required' => FALSE,
						'hidden'   => FALSE
					],
					'address_2'            => [
						'required' => FALSE,
						'hidden'   => TRUE
					]
				],
				'WS' => [
					'postcode' => [
						'required' => FALSE,
						'hidden'   => TRUE
					],
				],
				'ZA' => [
					'state' => [
						'label' => __('Province', 'woocommerce'),
					]
				],
				'ZW' => [
					'postcode' => [
						'required' => FALSE,
						'hidden'   => TRUE
					],
				],
			]);
			$this->locale = array_intersect_key($this->locale, array_merge($this->get_allowed_countries(),
			                                                               $this->get_shipping_countries()));
			// Default Locale Can be filtered to override fields in get_address_fields().
			// Countries with no specific locale will use default.
			$this->locale['default'] = apply_filters('woocommerce_get_country_locale_default',
			                                         $this->get_default_address_fields());
			// Filter default AND shop base locales to allow overides via a single function. These will be used when changing countries on the checkout
			if (!isset($this->locale[ $this->get_base_country() ])) {
				$this->locale[ $this->get_base_country() ] = $this->locale['default'];
			}
			$this->locale['default']                   = apply_filters('woocommerce_get_country_locale_base',
			                                                           $this->locale['default']);
			$this->locale[ $this->get_base_country() ] = apply_filters('woocommerce_get_country_locale_base',
			                                                           $this->locale[ $this->get_base_country() ]);
		}

		return $this->locale;
	}

	/**
	 * Trim white space and commas off a line.
	 *
	 * @param  string $line
	 *
	 * @return string
	 */
	private function trim_formatted_address_line($line) {
		return trim($line, ", ");
	}
}
