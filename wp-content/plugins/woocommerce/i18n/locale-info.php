<?php
return [
	'AU' => [
		'currency_code'  => 'AUD',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'AU',
					'state'    => '',
					'rate'     => '10.0000',
					'name'     => 'GST',
					'shipping' => TRUE
				]
			]
		]
	],
	'BD' => [
		'currency_code'  => 'BDT',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'in',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'BD',
					'state'    => '',
					'rate'     => '15.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'BE' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'left',
		'thousand_sep'   => ' ',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'BE',
					'state'    => '',
					'rate'     => '20.0000',
					'name'     => 'BTW',
					'shipping' => TRUE
				]
			]
		]
	],
	'BR' => [
		'currency_code'  => 'BRL',
		'currency_pos'   => 'left',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => []
	],
	'CA' => [
		'currency_code'  => 'CAD',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'BC' => [
				[
					'country'  => 'CA',
					'state'    => 'BC',
					'rate'     => '7.0000',
					'name'     => _x('PST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => FALSE,
					'priority' => 2
				]
			],
			'SK' => [
				[
					'country'  => 'CA',
					'state'    => 'SK',
					'rate'     => '5.0000',
					'name'     => _x('PST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => FALSE,
					'priority' => 2
				]
			],
			'MB' => [
				[
					'country'  => 'CA',
					'state'    => 'MB',
					'rate'     => '8.0000',
					'name'     => _x('PST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => FALSE,
					'priority' => 2
				]
			],
			'QC' => [
				[
					'country'  => 'CA',
					'state'    => 'QC',
					'rate'     => '9.975',
					'name'     => _x('QST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => FALSE,
					'priority' => 2
				]
			],
			'*'  => [
				[
					'country'  => 'CA',
					'state'    => 'ON',
					'rate'     => '13.0000',
					'name'     => _x('HST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'NL',
					'rate'     => '13.0000',
					'name'     => _x('HST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'NB',
					'rate'     => '13.0000',
					'name'     => _x('HST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'PE',
					'rate'     => '14.0000',
					'name'     => _x('HST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'NS',
					'rate'     => '15.0000',
					'name'     => _x('HST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'AB',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'BC',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'NT',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'NU',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'YT',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'SK',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'MB',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				],
				[
					'country'  => 'CA',
					'state'    => 'QC',
					'rate'     => '5.0000',
					'name'     => _x('GST', 'Canadian Tax Rates', 'woocommerce'),
					'shipping' => TRUE
				]
			]
		]
	],
	'DE' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'left',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'DE',
					'state'    => '',
					'rate'     => '19.0000',
					'name'     => 'Mwst.',
					'shipping' => TRUE
				]
			]
		]
	],
	'ES' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'right',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'ES',
					'state'    => '',
					'rate'     => '21.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'FR' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'right',
		'thousand_sep'   => ' ',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'FR',
					'state'    => '',
					'rate'     => '20.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'GB' => [
		'currency_code'  => 'GBP',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'GB',
					'state'    => '',
					'rate'     => '20.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'HU' => [
		'currency_code'  => 'HUF',
		'currency_pos'   => 'right_space',
		'thousand_sep'   => ' ',
		'decimal_sep'    => ',',
		'num_decimals'   => 0,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'HU',
					'state'    => '',
					'rate'     => '27.0000',
					'name'     => 'ÁFA',
					'shipping' => TRUE
				]
			]
		]
	],
	'IT' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'right',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'IT',
					'state'    => '',
					'rate'     => '22.0000',
					'name'     => 'IVA',
					'shipping' => TRUE
				]
			]
		]
	],
	'JP' => [
		'currency_code'  => 'JPY',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 0,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'JP',
					'state'    => '',
					'rate'     => '8.0000',
					'name'     => __('Consumption tax', 'woocommerce'),
					'shipping' => TRUE
				]
			]
		]
	],
	'NL' => [
		'currency_code'  => 'EUR',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'NL',
					'state'    => '',
					'rate'     => '21.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'NO' => [
		'currency_code'  => 'Kr',
		'currency_pos'   => 'left_space',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'NO',
					'state'    => '',
					'rate'     => '25.0000',
					'name'     => 'MVA',
					'shipping' => TRUE
				]
			]
		]
	],
	'NP' => [
		'currency_code'  => 'NPR',
		'currency_pos'   => 'left_space',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'NP',
					'state'    => '',
					'rate'     => '13.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'PL' => [
		'currency_code'  => 'PLN',
		'currency_pos'   => 'right',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'PL',
					'state'    => '',
					'rate'     => '23.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'TH' => [
		'currency_code'  => 'THB',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'TH',
					'state'    => '',
					'rate'     => '7.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	],
	'TR' => [
		'currency_code'  => 'TRY',
		'currency_pos'   => 'left_space',
		'thousand_sep'   => '.',
		'decimal_sep'    => ',',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'TR',
					'state'    => '',
					'rate'     => '18.0000',
					'name'     => 'KDV',
					'shipping' => TRUE
				]
			]
		]
	],
	'US' => [
		'currency_code'  => 'USD',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'lbs',
		'dimension_unit' => 'in',
		'tax_rates'      => [
			'AL' => [
				[
					'country'  => 'US',
					'state'    => 'AL',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'AZ' => [
				[
					'country'  => 'US',
					'state'    => 'AZ',
					'rate'     => '5.6000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'AR' => [
				[
					'country'  => 'US',
					'state'    => 'AR',
					'rate'     => '6.5000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'CA' => [
				[
					'country'  => 'US',
					'state'    => 'CA',
					'rate'     => '7.5000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'CO' => [
				[
					'country'  => 'US',
					'state'    => 'CO',
					'rate'     => '2.9000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'CT' => [
				[
					'country'  => 'US',
					'state'    => 'CT',
					'rate'     => '6.3500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'DC' => [
				[
					'country'  => 'US',
					'state'    => 'DC',
					'rate'     => '5.7500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'FL' => [
				[
					'country'  => 'US',
					'state'    => 'FL',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'GA' => [
				[
					'country'  => 'US',
					'state'    => 'GA',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'GU' => [
				[
					'country'  => 'US',
					'state'    => 'GU',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'HI' => [
				[
					'country'  => 'US',
					'state'    => 'HI',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'ID' => [
				[
					'country'  => 'US',
					'state'    => 'ID',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'IL' => [
				[
					'country'  => 'US',
					'state'    => 'IL',
					'rate'     => '6.2500',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'IN' => [
				[
					'country'  => 'US',
					'state'    => 'IN',
					'rate'     => '7.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'IA' => [
				[
					'country'  => 'US',
					'state'    => 'IA',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'KS' => [
				[
					'country'  => 'US',
					'state'    => 'KS',
					'rate'     => '6.1500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'KY' => [
				[
					'country'  => 'US',
					'state'    => 'KY',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'LA' => [
				[
					'country'  => 'US',
					'state'    => 'LA',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'ME' => [
				[
					'country'  => 'US',
					'state'    => 'ME',
					'rate'     => '5.5000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'MD' => [
				[
					'country'  => 'US',
					'state'    => 'MD',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'MA' => [
				[
					'country'  => 'US',
					'state'    => 'MA',
					'rate'     => '6.2500',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'MI' => [
				[
					'country'  => 'US',
					'state'    => 'MI',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'MN' => [
				[
					'country'  => 'US',
					'state'    => 'MN',
					'rate'     => '6.8750',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'MS' => [
				[
					'country'  => 'US',
					'state'    => 'MS',
					'rate'     => '7.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'MO' => [
				[
					'country'  => 'US',
					'state'    => 'MO',
					'rate'     => '4.225',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'NE' => [
				[
					'country'  => 'US',
					'state'    => 'NE',
					'rate'     => '5.5000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'NV' => [
				[
					'country'  => 'US',
					'state'    => 'NV',
					'rate'     => '6.8500',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'NJ' => [
				[
					'country'  => 'US',
					'state'    => 'NJ',
					'rate'     => '7.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'NM' => [
				[
					'country'  => 'US',
					'state'    => 'NM',
					'rate'     => '5.1250',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'NY' => [
				[
					'country'  => 'US',
					'state'    => 'NY',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'NC' => [
				[
					'country'  => 'US',
					'state'    => 'NC',
					'rate'     => '4.7500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'ND' => [
				[
					'country'  => 'US',
					'state'    => 'ND',
					'rate'     => '5.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'OH' => [
				[
					'country'  => 'US',
					'state'    => 'OH',
					'rate'     => '5.7500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'OK' => [
				[
					'country'  => 'US',
					'state'    => 'OK',
					'rate'     => '4.5000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'PA' => [
				[
					'country'  => 'US',
					'state'    => 'PA',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'PR' => [
				[
					'country'  => 'US',
					'state'    => 'PR',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'RI' => [
				[
					'country'  => 'US',
					'state'    => 'RI',
					'rate'     => '7.0000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'SC' => [
				[
					'country'  => 'US',
					'state'    => 'SC',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'SD' => [
				[
					'country'  => 'US',
					'state'    => 'SD',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'TN' => [
				[
					'country'  => 'US',
					'state'    => 'TN',
					'rate'     => '7.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'TX' => [
				[
					'country'  => 'US',
					'state'    => 'TX',
					'rate'     => '6.2500',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'UT' => [
				[
					'country'  => 'US',
					'state'    => 'UT',
					'rate'     => '5.9500',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'VT' => [
				[
					'country'  => 'US',
					'state'    => 'VT',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'VA' => [
				[
					'country'  => 'US',
					'state'    => 'VA',
					'rate'     => '5.3000',
					'name'     => 'State Tax',
					'shipping' => FALSE
				]
			],
			'WA' => [
				[
					'country'  => 'US',
					'state'    => 'WA',
					'rate'     => '6.5000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'WV' => [
				[
					'country'  => 'US',
					'state'    => 'WV',
					'rate'     => '6.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'WI' => [
				[
					'country'  => 'US',
					'state'    => 'WI',
					'rate'     => '5.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			],
			'WY' => [
				[
					'country'  => 'US',
					'state'    => 'WY',
					'rate'     => '4.0000',
					'name'     => 'State Tax',
					'shipping' => TRUE
				]
			]
		]
	],
	'ZA' => [
		'currency_code'  => 'ZAR',
		'currency_pos'   => 'left',
		'thousand_sep'   => ',',
		'decimal_sep'    => '.',
		'num_decimals'   => 2,
		'weight_unit'    => 'kg',
		'dimension_unit' => 'cm',
		'tax_rates'      => [
			'' => [
				[
					'country'  => 'ZA',
					'state'    => '',
					'rate'     => '14.0000',
					'name'     => 'VAT',
					'shipping' => TRUE
				]
			]
		]
	]
];
