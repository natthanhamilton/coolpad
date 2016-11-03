<?php
if (!defined('ABSPATH')) {
	exit;
}
$cost_desc = __('Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.',
                'woocommerce') . '<br/><br/>' . __('Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.',
                                                   'woocommerce');
/**
 * Settings for flat rate shipping.
 */
$settings = [
	'title'      => [
		'title'       => __('Method Title', 'woocommerce'),
		'type'        => 'text',
		'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
		'default'     => __('Flat Rate', 'woocommerce'),
		'desc_tip'    => TRUE
	],
	'tax_status' => [
		'title'   => __('Tax Status', 'woocommerce'),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'taxable',
		'options' => [
			'taxable' => __('Taxable', 'woocommerce'),
			'none'    => _x('None', 'Tax status', 'woocommerce')
		]
	],
	'cost'       => [
		'title'       => __('Cost', 'woocommerce'),
		'type'        => 'text',
		'placeholder' => '',
		'description' => $cost_desc,
		'default'     => '0',
		'desc_tip'    => TRUE
	]
];
$shipping_classes = WC()->shipping->get_shipping_classes();
if (!empty($shipping_classes)) {
	$settings['class_costs'] = [
		'title'       => __('Shipping Class Costs', 'woocommerce'),
		'type'        => 'title',
		'default'     => '',
		'description' => sprintf(__('These costs can optionally be added based on the %sproduct shipping class%s.',
		                            'woocommerce'),
		                         '<a href="' . admin_url('edit-tags.php?taxonomy=product_shipping_class&post_type=product') . '">',
		                         '</a>')
	];
	foreach ($shipping_classes as $shipping_class) {
		if (!isset($shipping_class->term_id)) {
			continue;
		}
		$settings[ 'class_cost_' . $shipping_class->term_id ] = [
			'title'       => sprintf(__('"%s" Shipping Class Cost', 'woocommerce'), esc_html($shipping_class->name)),
			'type'        => 'text',
			'placeholder' => __('N/A', 'woocommerce'),
			'description' => $cost_desc,
			'default'     => $this->get_option('class_cost_' . $shipping_class->slug), // Before 2.5.0, we used slug here which caused issues with long setting names
			'desc_tip'    => TRUE
		];
	}
	$settings['no_class_cost'] = [
		'title'       => __('No Shipping Class Cost', 'woocommerce'),
		'type'        => 'text',
		'placeholder' => __('N/A', 'woocommerce'),
		'description' => $cost_desc,
		'default'     => '',
		'desc_tip'    => TRUE
	];
	$settings['type']          = [
		'title'   => __('Calculation Type', 'woocommerce'),
		'type'    => 'select',
		'class'   => 'wc-enhanced-select',
		'default' => 'class',
		'options' => [
			'class' => __('Per Class: Charge shipping for each shipping class individually', 'woocommerce'),
			'order' => __('Per Order: Charge shipping for the most expensive shipping class', 'woocommerce'),
		],
	];
}
return $settings;
