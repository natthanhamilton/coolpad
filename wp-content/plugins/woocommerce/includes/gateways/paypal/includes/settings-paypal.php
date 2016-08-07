<?php
if (!defined('ABSPATH')) {
	exit;
}
/**
 * Settings for PayPal Gateway.
 */
return [
	'enabled'          => [
		'title'   => __('Enable/Disable', 'woocommerce'),
		'type'    => 'checkbox',
		'label'   => __('Enable PayPal standard', 'woocommerce'),
		'default' => 'yes'
	],
	'title'            => [
		'title'       => __('Title', 'woocommerce'),
		'type'        => 'text',
		'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
		'default'     => __('PayPal', 'woocommerce'),
		'desc_tip'    => TRUE,
	],
	'description'      => [
		'title'       => __('Description', 'woocommerce'),
		'type'        => 'text',
		'desc_tip'    => TRUE,
		'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
		'default'     => __('Pay via PayPal; you can pay with your credit card if you don\'t have a PayPal account.',
		                    'woocommerce')
	],
	'email'            => [
		'title'       => __('PayPal Email', 'woocommerce'),
		'type'        => 'email',
		'description' => __('Please enter your PayPal email address; this is needed in order to take payment.',
		                    'woocommerce'),
		'default'     => get_option('admin_email'),
		'desc_tip'    => TRUE,
		'placeholder' => 'you@youremail.com'
	],
	'testmode'         => [
		'title'       => __('PayPal Sandbox', 'woocommerce'),
		'type'        => 'checkbox',
		'label'       => __('Enable PayPal sandbox', 'woocommerce'),
		'default'     => 'no',
		'description' => sprintf(__('PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>.',
		                            'woocommerce'), 'https://developer.paypal.com/'),
	],
	'debug'            => [
		'title'       => __('Debug Log', 'woocommerce'),
		'type'        => 'checkbox',
		'label'       => __('Enable logging', 'woocommerce'),
		'default'     => 'no',
		'description' => sprintf(__('Log PayPal events, such as IPN requests, inside <code>%s</code>', 'woocommerce'),
		                         wc_get_log_file_path('paypal'))
	],
	'advanced'         => [
		'title'       => __('Advanced options', 'woocommerce'),
		'type'        => 'title',
		'description' => '',
	],
	'receiver_email'   => [
		'title'       => __('Receiver Email', 'woocommerce'),
		'type'        => 'email',
		'description' => __('If your main PayPal email differs from the PayPal email entered above, input your main receiver email for your PayPal account here. This is used to validate IPN requests.',
		                    'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => 'you@youremail.com'
	],
	'identity_token'   => [
		'title'       => __('PayPal Identity Token', 'woocommerce'),
		'type'        => 'text',
		'description' => __('Optionally enable "Payment Data Transfer" (Profile > Profile and Settings > My Selling Tools > Website Preferences) and then copy your identity token here. This will allow payments to be verified without the need for PayPal IPN.',
		                    'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => ''
	],
	'invoice_prefix'   => [
		'title'       => __('Invoice Prefix', 'woocommerce'),
		'type'        => 'text',
		'description' => __('Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.',
		                    'woocommerce'),
		'default'     => 'WC-',
		'desc_tip'    => TRUE,
	],
	'send_shipping'    => [
		'title'       => __('Shipping Details', 'woocommerce'),
		'type'        => 'checkbox',
		'label'       => __('Send shipping details to PayPal instead of billing.', 'woocommerce'),
		'description' => __('PayPal allows us to send one address. If you are using PayPal for shipping labels you may prefer to send the shipping address rather than billing.',
		                    'woocommerce'),
		'default'     => 'no'
	],
	'address_override' => [
		'title'       => __('Address Override', 'woocommerce'),
		'type'        => 'checkbox',
		'label'       => __('Enable "address_override" to prevent address information from being changed.',
		                    'woocommerce'),
		'description' => __('PayPal verifies addresses therefore this setting can cause errors (we recommend keeping it disabled).',
		                    'woocommerce'),
		'default'     => 'no'
	],
	'paymentaction'    => [
		'title'       => __('Payment Action', 'woocommerce'),
		'type'        => 'select',
		'class'       => 'wc-enhanced-select',
		'description' => __('Choose whether you wish to capture funds immediately or authorize payment only.',
		                    'woocommerce'),
		'default'     => 'sale',
		'desc_tip'    => TRUE,
		'options'     => [
			'sale'          => __('Capture', 'woocommerce'),
			'authorization' => __('Authorize', 'woocommerce')
		]
	],
	'page_style'       => [
		'title'       => __('Page Style', 'woocommerce'),
		'type'        => 'text',
		'description' => __('Optionally enter the name of the page style you wish to use. These are defined within your PayPal account.',
		                    'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => __('Optional', 'woocommerce')
	],
	'api_details'      => [
		'title'       => __('API Credentials', 'woocommerce'),
		'type'        => 'title',
		'description' => sprintf(__('Enter your PayPal API credentials to process refunds via PayPal. Learn how to access your PayPal API Credentials %shere%s.',
		                            'woocommerce'),
		                         '<a href="https://developer.paypal.com/webapps/developer/docs/classic/api/apiCredentials/#creating-an-api-signature">',
		                         '</a>'),
	],
	'api_username'     => [
		'title'       => __('API Username', 'woocommerce'),
		'type'        => 'text',
		'description' => __('Get your API credentials from PayPal.', 'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => __('Optional', 'woocommerce')
	],
	'api_password'     => [
		'title'       => __('API Password', 'woocommerce'),
		'type'        => 'password',
		'description' => __('Get your API credentials from PayPal.', 'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => __('Optional', 'woocommerce')
	],
	'api_signature'    => [
		'title'       => __('API Signature', 'woocommerce'),
		'type'        => 'text',
		'description' => __('Get your API credentials from PayPal.', 'woocommerce'),
		'default'     => '',
		'desc_tip'    => TRUE,
		'placeholder' => __('Optional', 'woocommerce')
	],
];
