<?php
/**
 * Adds and controls pointers for contextual help/tutorials
 *
 * @author   WooThemes
 * @category Admin
 * @package  WooCommerce/Admin
 * @version  2.4.0
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * WC_Admin_Pointers Class.
 */
class WC_Admin_Pointers {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'setup_pointers_for_screen']);
	}

	/**
	 * Setup pointers for screen.
	 */
	public function setup_pointers_for_screen() {
		if (!$screen = get_current_screen()) {
			return;
		}
		switch ($screen->id) {
			case 'product' :
				$this->create_product_tutorial();
				break;
		}
	}

	/**
	 * Pointers for creating a product.
	 */
	public function create_product_tutorial() {
		if (!isset($_GET['tutorial']) || !current_user_can('manage_options')) {
			return;
		}
		// These pointers will chain - they will not be shown at once.
		$pointers = [
			'pointers' => [
				'title'          => [
					'target'       => "#title",
					'next'         => 'content',
					'next_trigger' => [
						'target' => '#title',
						'event'  => 'input'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Product Name', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Give your new product a name here. This is a required field and will be what your customers will see in your store.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'top',
							'align' => 'left'
						]
					]
				],
				'content'        => [
					'target'       => "#wp-content-editor-container",
					'next'         => 'product-type',
					'next_trigger' => [],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Product Description', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('This is your products main body of content. Here you should describe your product in detail.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'product-type'   => [
					'target'       => "#product-type",
					'next'         => 'virtual',
					'next_trigger' => [
						'target' => "#product-type",
						'event'  => 'change blur click'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Choose Product Type', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Choose a type for this product. Simple is suitable for most physical goods and services (we recommend setting up a simple product for now).',
						                                 'woocommerce') . '</p>' .
						              '<p>' . esc_html__('Variable is for more complex products such as t-shirts with multiple sizes.',
						                                 'woocommerce') . '</p>' .
						              '<p>' . esc_html__('Grouped products are for grouping several simple products into one.',
						                                 'woocommerce') . '</p>' .
						              '<p>' . esc_html__('Finally, external products are for linking off-site.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'virtual'        => [
					'target'       => "#_virtual",
					'next'         => 'downloadable',
					'next_trigger' => [
						'target' => "#_virtual",
						'event'  => 'change'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Virtual Products', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Check the "Virtual" box if this is a non-physical item, for example a service, which does not need shipping.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'downloadable'   => [
					'target'       => "#_downloadable",
					'next'         => 'regular_price',
					'next_trigger' => [
						'target' => "#_downloadable",
						'event'  => 'change'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Downloadable Products', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('If purchasing this product gives a customer access to a downloadable file, e.g. software, check this box.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'regular_price'  => [
					'target'       => "#_regular_price",
					'next'         => 'postexcerpt',
					'next_trigger' => [
						'target' => "#_regular_price",
						'event'  => 'input'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Prices', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Next you\'ll need to give your product a price.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'postexcerpt'    => [
					'target'       => "#postexcerpt",
					'next'         => 'postimagediv',
					'next_trigger' => [
						'target' => "#postexcerpt",
						'event'  => 'input'
					],
					'options'      => [
						'content'  => '<h3>' . esc_html__('Product Short Description', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Add a quick summary for your product here. This will appear on the product page under the product name.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'bottom',
							'align' => 'middle'
						]
					]
				],
				'postimagediv'   => [
					'target'  => "#postimagediv",
					'next'    => 'product_tag',
					'options' => [
						'content'  => '<h3>' . esc_html__('Product Images', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Upload or assign an image to your product here. This image will be shown in your store\'s catalog.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'right',
							'align' => 'middle'
						]
					]
				],
				'product_tag'    => [
					'target'  => "#tagsdiv-product_tag",
					'next'    => 'product_catdiv',
					'options' => [
						'content'  => '<h3>' . esc_html__('Product Tags', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('You can optionally "tag" your products here. Tags as a method of labeling your products to make them easier for customers to find.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'right',
							'align' => 'middle'
						]
					]
				],
				'product_catdiv' => [
					'target'  => "#product_catdiv",
					'next'    => 'submitdiv',
					'options' => [
						'content'  => '<h3>' . esc_html__('Product Categories', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('Optionally assign categories to your products to make them easier to browse through and find in your store.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'right',
							'align' => 'middle'
						]
					]
				],
				'submitdiv'      => [
					'target'  => "#submitdiv",
					'next'    => '',
					'options' => [
						'content'  => '<h3>' . esc_html__('Publish Your Product!', 'woocommerce') . '</h3>' .
						              '<p>' . esc_html__('When you are finished editing your product, hit the "Publish" button to publish your product to your store.',
						                                 'woocommerce') . '</p>',
						'position' => [
							'edge'  => 'right',
							'align' => 'middle'
						]
					]
				]
			]
		];
		$this->enqueue_pointers($pointers);
	}

	/**
	 * Enqueue pointers and add script to page.
	 *
	 * @param array $pointers
	 */
	public function enqueue_pointers($pointers) {
		$pointers = wp_json_encode($pointers);
		wp_enqueue_style('wp-pointer');
		wp_enqueue_script('wp-pointer');
		wc_enqueue_js("
			jQuery( function( $ ) {
				var wc_pointers = {$pointers};

				setTimeout( init_wc_pointers, 800 );

				function init_wc_pointers() {
					$.each( wc_pointers.pointers, function( i ) {
						show_wc_pointer( i );
						return false;
					});
				}

				function show_wc_pointer( id ) {
					var pointer = wc_pointers.pointers[ id ];
					var options = $.extend( pointer.options, {
						close: function() {
							if ( pointer.next ) {
								show_wc_pointer( pointer.next );
							}
						}
					} );
					var this_pointer = $( pointer.target ).pointer( options );
					this_pointer.pointer( 'open' );

					if ( pointer.next_trigger ) {
						$( pointer.next_trigger.target ).on( pointer.next_trigger.event, function() {
							setTimeout( function() { this_pointer.pointer( 'close' ); }, 400 );
						});
					}
				}
			});
		");
	}
}

new WC_Admin_Pointers();
