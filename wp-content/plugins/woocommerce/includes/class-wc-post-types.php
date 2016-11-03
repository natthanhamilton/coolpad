<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @class     WC_Post_types
 * @version   2.5.0
 * @package   WooCommerce/Classes/Products
 * @category  Class
 * @author    WooThemes
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * WC_Post_types Class.
 */
class WC_Post_types {
	/**
	 * Hook in methods.
	 */
	public static function init() {
		add_action('init', [__CLASS__, 'register_taxonomies'], 5);
		add_action('init', [__CLASS__, 'register_post_types'], 5);
		add_action('init', [__CLASS__, 'register_post_status'], 9);
		add_action('init', [__CLASS__, 'support_jetpack_omnisearch']);
		add_filter('rest_api_allowed_post_types', [__CLASS__, 'rest_api_allowed_post_types']);
	}

	/**
	 * Register core taxonomies.
	 */
	public static function register_taxonomies() {
		if (taxonomy_exists('product_type')) {
			return;
		}
		do_action('woocommerce_register_taxonomy');
		$permalinks = get_option('woocommerce_permalinks');
		register_taxonomy('product_type',
		                  apply_filters('woocommerce_taxonomy_objects_product_type', ['product']),
		                  apply_filters('woocommerce_taxonomy_args_product_type', [
			                  'hierarchical'      => FALSE,
			                  'show_ui'           => FALSE,
			                  'show_in_nav_menus' => FALSE,
			                  'query_var'         => is_admin(),
			                  'rewrite'           => FALSE,
			                  'public'            => FALSE
		                  ])
		);
		register_taxonomy('product_cat',
		                  apply_filters('woocommerce_taxonomy_objects_product_cat', ['product']),
		                  apply_filters('woocommerce_taxonomy_args_product_cat', [
			                  'hierarchical'          => TRUE,
			                  'update_count_callback' => '_wc_term_recount',
			                  'label'                 => __('Product Categories', 'woocommerce'),
			                  'labels'                => [
				                  'name'              => __('Product Categories', 'woocommerce'),
				                  'singular_name'     => __('Product Category', 'woocommerce'),
				                  'menu_name'         => _x('Categories', 'Admin menu name', 'woocommerce'),
				                  'search_items'      => __('Search Product Categories', 'woocommerce'),
				                  'all_items'         => __('All Product Categories', 'woocommerce'),
				                  'parent_item'       => __('Parent Product Category', 'woocommerce'),
				                  'parent_item_colon' => __('Parent Product Category:', 'woocommerce'),
				                  'edit_item'         => __('Edit Product Category', 'woocommerce'),
				                  'update_item'       => __('Update Product Category', 'woocommerce'),
				                  'add_new_item'      => __('Add New Product Category', 'woocommerce'),
				                  'new_item_name'     => __('New Product Category Name', 'woocommerce'),
				                  'not_found'         => __('No Product Category found', 'woocommerce'),
			                  ],
			                  'show_ui'               => TRUE,
			                  'query_var'             => TRUE,
			                  'capabilities'          => [
				                  'manage_terms' => 'manage_product_terms',
				                  'edit_terms'   => 'edit_product_terms',
				                  'delete_terms' => 'delete_product_terms',
				                  'assign_terms' => 'assign_product_terms',
			                  ],
			                  'rewrite'               => [
				                  'slug'         => empty($permalinks['category_base']) ? _x('product-category', 'slug',
				                                                                             'woocommerce')
					                  : $permalinks['category_base'],
				                  'with_front'   => FALSE,
				                  'hierarchical' => TRUE,
			                  ],
		                  ])
		);
		register_taxonomy('product_tag',
		                  apply_filters('woocommerce_taxonomy_objects_product_tag', ['product']),
		                  apply_filters('woocommerce_taxonomy_args_product_tag', [
			                  'hierarchical'          => FALSE,
			                  'update_count_callback' => '_wc_term_recount',
			                  'label'                 => __('Product Tags', 'woocommerce'),
			                  'labels'                => [
				                  'name'                       => __('Product Tags', 'woocommerce'),
				                  'singular_name'              => __('Product Tag', 'woocommerce'),
				                  'menu_name'                  => _x('Tags', 'Admin menu name', 'woocommerce'),
				                  'search_items'               => __('Search Product Tags', 'woocommerce'),
				                  'all_items'                  => __('All Product Tags', 'woocommerce'),
				                  'edit_item'                  => __('Edit Product Tag', 'woocommerce'),
				                  'update_item'                => __('Update Product Tag', 'woocommerce'),
				                  'add_new_item'               => __('Add New Product Tag', 'woocommerce'),
				                  'new_item_name'              => __('New Product Tag Name', 'woocommerce'),
				                  'popular_items'              => __('Popular Product Tags', 'woocommerce'),
				                  'separate_items_with_commas' => __('Separate Product Tags with commas',
				                                                     'woocommerce'),
				                  'add_or_remove_items'        => __('Add or remove Product Tags', 'woocommerce'),
				                  'choose_from_most_used'      => __('Choose from the most used Product tags',
				                                                     'woocommerce'),
				                  'not_found'                  => __('No Product Tags found', 'woocommerce'),
			                  ],
			                  'show_ui'               => TRUE,
			                  'query_var'             => TRUE,
			                  'capabilities'          => [
				                  'manage_terms' => 'manage_product_terms',
				                  'edit_terms'   => 'edit_product_terms',
				                  'delete_terms' => 'delete_product_terms',
				                  'assign_terms' => 'assign_product_terms',
			                  ],
			                  'rewrite'               => [
				                  'slug'       => empty($permalinks['tag_base']) ? _x('product-tag', 'slug',
				                                                                      'woocommerce')
					                  : $permalinks['tag_base'],
				                  'with_front' => FALSE
			                  ],
		                  ])
		);
		register_taxonomy('product_shipping_class',
		                  apply_filters('woocommerce_taxonomy_objects_product_shipping_class',
		                                ['product', 'product_variation']),
		                  apply_filters('woocommerce_taxonomy_args_product_shipping_class', [
			                  'hierarchical'          => FALSE,
			                  'update_count_callback' => '_update_post_term_count',
			                  'label'                 => __('Shipping Classes', 'woocommerce'),
			                  'labels'                => [
				                  'name'              => __('Shipping Classes', 'woocommerce'),
				                  'singular_name'     => __('Shipping Class', 'woocommerce'),
				                  'menu_name'         => _x('Shipping Classes', 'Admin menu name', 'woocommerce'),
				                  'search_items'      => __('Search Shipping Classes', 'woocommerce'),
				                  'all_items'         => __('All Shipping Classes', 'woocommerce'),
				                  'parent_item'       => __('Parent Shipping Class', 'woocommerce'),
				                  'parent_item_colon' => __('Parent Shipping Class:', 'woocommerce'),
				                  'edit_item'         => __('Edit Shipping Class', 'woocommerce'),
				                  'update_item'       => __('Update Shipping Class', 'woocommerce'),
				                  'add_new_item'      => __('Add New Shipping Class', 'woocommerce'),
				                  'new_item_name'     => __('New Shipping Class Name', 'woocommerce')
			                  ],
			                  'show_ui'               => FALSE,
			                  'show_in_quick_edit'    => FALSE,
			                  'show_in_nav_menus'     => FALSE,
			                  'query_var'             => is_admin(),
			                  'capabilities'          => [
				                  'manage_terms' => 'manage_product_terms',
				                  'edit_terms'   => 'edit_product_terms',
				                  'delete_terms' => 'delete_product_terms',
				                  'assign_terms' => 'assign_product_terms',
			                  ],
			                  'rewrite'               => FALSE,
		                  ])
		);
		global $wc_product_attributes;
		$wc_product_attributes = [];
		if ($attribute_taxonomies = wc_get_attribute_taxonomies()) {
			foreach ($attribute_taxonomies as $tax) {
				if ($name = wc_attribute_taxonomy_name($tax->attribute_name)) {
					$tax->attribute_public          = absint(isset($tax->attribute_public) ? $tax->attribute_public
						                                         : 1);
					$label                          = !empty($tax->attribute_label) ? $tax->attribute_label
						: $tax->attribute_name;
					$wc_product_attributes[ $name ] = $tax;
					$taxonomy_data                  = [
						'hierarchical'          => TRUE,
						'update_count_callback' => '_update_post_term_count',
						'labels'                => [
							'name'              => $label,
							'singular_name'     => $label,
							'search_items'      => sprintf(__('Search %s', 'woocommerce'), $label),
							'all_items'         => sprintf(__('All %s', 'woocommerce'), $label),
							'parent_item'       => sprintf(__('Parent %s', 'woocommerce'), $label),
							'parent_item_colon' => sprintf(__('Parent %s:', 'woocommerce'), $label),
							'edit_item'         => sprintf(__('Edit %s', 'woocommerce'), $label),
							'update_item'       => sprintf(__('Update %s', 'woocommerce'), $label),
							'add_new_item'      => sprintf(__('Add New %s', 'woocommerce'), $label),
							'new_item_name'     => sprintf(__('New %s', 'woocommerce'), $label),
							'not_found'         => sprintf(__('No &quot;%s&quot; found', 'woocommerce'), $label),
						],
						'show_ui'               => TRUE,
						'show_in_quick_edit'    => FALSE,
						'show_in_menu'          => FALSE,
						'show_in_nav_menus'     => FALSE,
						'meta_box_cb'           => FALSE,
						'query_var'             => 1 === $tax->attribute_public,
						'rewrite'               => FALSE,
						'sort'                  => FALSE,
						'public'                => 1 === $tax->attribute_public,
						'show_in_nav_menus'     => 1 === $tax->attribute_public && apply_filters('woocommerce_attribute_show_in_nav_menus',
						                                                                         FALSE, $name),
						'capabilities'          => [
							'manage_terms' => 'manage_product_terms',
							'edit_terms'   => 'edit_product_terms',
							'delete_terms' => 'delete_product_terms',
							'assign_terms' => 'assign_product_terms',
						]
					];
					if (1 === $tax->attribute_public) {
						$taxonomy_data['rewrite'] = [
							'slug'         => empty($permalinks['attribute_base']) ? ''
								: trailingslashit($permalinks['attribute_base']) . sanitize_title($tax->attribute_name),
							'with_front'   => FALSE,
							'hierarchical' => TRUE
						];
					}
					register_taxonomy($name, apply_filters("woocommerce_taxonomy_objects_{$name}", ['product']),
					                  apply_filters("woocommerce_taxonomy_args_{$name}", $taxonomy_data));
				}
			}
		}
		do_action('woocommerce_after_register_taxonomy');
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if (post_type_exists('product')) {
			return;
		}
		do_action('woocommerce_register_post_type');
		$permalinks        = get_option('woocommerce_permalinks');
		$product_permalink = empty($permalinks['product_base']) ? _x('product', 'slug', 'woocommerce')
			: $permalinks['product_base'];
		register_post_type('product',
		                   apply_filters('woocommerce_register_post_type_product',
		                                 [
			                                 'labels'              => [
				                                 'name'                  => __('Products', 'woocommerce'),
				                                 'singular_name'         => __('Product', 'woocommerce'),
				                                 'menu_name'             => _x('Products', 'Admin menu name',
				                                                               'woocommerce'),
				                                 'add_new'               => __('Add Product', 'woocommerce'),
				                                 'add_new_item'          => __('Add New Product', 'woocommerce'),
				                                 'edit'                  => __('Edit', 'woocommerce'),
				                                 'edit_item'             => __('Edit Product', 'woocommerce'),
				                                 'new_item'              => __('New Product', 'woocommerce'),
				                                 'view'                  => __('View Product', 'woocommerce'),
				                                 'view_item'             => __('View Product', 'woocommerce'),
				                                 'search_items'          => __('Search Products', 'woocommerce'),
				                                 'not_found'             => __('No Products found', 'woocommerce'),
				                                 'not_found_in_trash'    => __('No Products found in trash',
				                                                               'woocommerce'),
				                                 'parent'                => __('Parent Product', 'woocommerce'),
				                                 'featured_image'        => __('Product Image', 'woocommerce'),
				                                 'set_featured_image'    => __('Set product image', 'woocommerce'),
				                                 'remove_featured_image' => __('Remove product image', 'woocommerce'),
				                                 'use_featured_image'    => __('Use as product image', 'woocommerce'),
				                                 'insert_into_item'      => __('Insert into product', 'woocommerce'),
				                                 'uploaded_to_this_item' => __('Uploaded to this product',
				                                                               'woocommerce'),
				                                 'filter_items_list'     => __('Filter products', 'woocommerce'),
				                                 'items_list_navigation' => __('Products navigation', 'woocommerce'),
				                                 'items_list'            => __('Products list', 'woocommerce'),
			                                 ],
			                                 'description'         => __('This is where you can add new products to your store.',
			                                                             'woocommerce'),
			                                 'public'              => TRUE,
			                                 'show_ui'             => TRUE,
			                                 'capability_type'     => 'product',
			                                 'map_meta_cap'        => TRUE,
			                                 'publicly_queryable'  => TRUE,
			                                 'exclude_from_search' => FALSE,
			                                 'hierarchical'        => FALSE, // Hierarchical causes memory issues - WP loads all records!
			                                 'rewrite'             => $product_permalink
				                                 ? ['slug' => untrailingslashit($product_permalink), 'with_front' => FALSE, 'feeds' => TRUE]
				                                 : FALSE,
			                                 'query_var'           => TRUE,
			                                 'supports'            => ['title', 'editor', 'excerpt', 'thumbnail', 'comments', 'custom-fields', 'page-attributes', 'publicize', 'wpcom-markdown'],
			                                 'has_archive'         => ($shop_page_id
				                                 = wc_get_page_id('shop')) && get_post($shop_page_id)
				                                 ? get_page_uri($shop_page_id) : 'shop',
			                                 'show_in_nav_menus'   => TRUE
		                                 ]
		                   )
		);
		register_post_type('product_variation',
		                   apply_filters('woocommerce_register_post_type_product_variation',
		                                 [
			                                 'label'           => __('Variations', 'woocommerce'),
			                                 'public'          => FALSE,
			                                 'hierarchical'    => FALSE,
			                                 'supports'        => FALSE,
			                                 'capability_type' => 'product'
		                                 ]
		                   )
		);
		wc_register_order_type(
			'shop_order',
			apply_filters('woocommerce_register_post_type_shop_order',
			              [
				              'labels'              => [
					              'name'                  => __('Orders', 'woocommerce'),
					              'singular_name'         => _x('Order', 'shop_order post type singular name',
					                                            'woocommerce'),
					              'add_new'               => __('Add Order', 'woocommerce'),
					              'add_new_item'          => __('Add New Order', 'woocommerce'),
					              'edit'                  => __('Edit', 'woocommerce'),
					              'edit_item'             => __('Edit Order', 'woocommerce'),
					              'new_item'              => __('New Order', 'woocommerce'),
					              'view'                  => __('View Order', 'woocommerce'),
					              'view_item'             => __('View Order', 'woocommerce'),
					              'search_items'          => __('Search Orders', 'woocommerce'),
					              'not_found'             => __('No Orders found', 'woocommerce'),
					              'not_found_in_trash'    => __('No Orders found in trash', 'woocommerce'),
					              'parent'                => __('Parent Orders', 'woocommerce'),
					              'menu_name'             => _x('Orders', 'Admin menu name', 'woocommerce'),
					              'filter_items_list'     => __('Filter orders', 'woocommerce'),
					              'items_list_navigation' => __('Orders navigation', 'woocommerce'),
					              'items_list'            => __('Orders list', 'woocommerce'),
				              ],
				              'description'         => __('This is where store orders are stored.', 'woocommerce'),
				              'public'              => FALSE,
				              'show_ui'             => TRUE,
				              'capability_type'     => 'shop_order',
				              'map_meta_cap'        => TRUE,
				              'publicly_queryable'  => FALSE,
				              'exclude_from_search' => TRUE,
				              'show_in_menu'        => current_user_can('manage_woocommerce') ? 'woocommerce' : TRUE,
				              'hierarchical'        => FALSE,
				              'show_in_nav_menus'   => FALSE,
				              'rewrite'             => FALSE,
				              'query_var'           => FALSE,
				              'supports'            => ['title', 'comments', 'custom-fields'],
				              'has_archive'         => FALSE,
			              ]
			)
		);
		wc_register_order_type(
			'shop_order_refund',
			apply_filters('woocommerce_register_post_type_shop_order_refund',
			              [
				              'label'                            => __('Refunds', 'woocommerce'),
				              'capability_type'                  => 'shop_order',
				              'public'                           => FALSE,
				              'hierarchical'                     => FALSE,
				              'supports'                         => FALSE,
				              'exclude_from_orders_screen'       => FALSE,
				              'add_order_meta_boxes'             => FALSE,
				              'exclude_from_order_count'         => TRUE,
				              'exclude_from_order_views'         => FALSE,
				              'exclude_from_order_reports'       => FALSE,
				              'exclude_from_order_sales_reports' => TRUE,
				              'class_name'                       => 'WC_Order_Refund'
			              ]
			)
		);
		if ('yes' == get_option('woocommerce_enable_coupons')) {
			register_post_type('shop_coupon',
			                   apply_filters('woocommerce_register_post_type_shop_coupon',
			                                 [
				                                 'labels'              => [
					                                 'name'                  => __('Coupons', 'woocommerce'),
					                                 'singular_name'         => __('Coupon', 'woocommerce'),
					                                 'menu_name'             => _x('Coupons', 'Admin menu name',
					                                                               'woocommerce'),
					                                 'add_new'               => __('Add Coupon', 'woocommerce'),
					                                 'add_new_item'          => __('Add New Coupon', 'woocommerce'),
					                                 'edit'                  => __('Edit', 'woocommerce'),
					                                 'edit_item'             => __('Edit Coupon', 'woocommerce'),
					                                 'new_item'              => __('New Coupon', 'woocommerce'),
					                                 'view'                  => __('View Coupons', 'woocommerce'),
					                                 'view_item'             => __('View Coupon', 'woocommerce'),
					                                 'search_items'          => __('Search Coupons', 'woocommerce'),
					                                 'not_found'             => __('No Coupons found', 'woocommerce'),
					                                 'not_found_in_trash'    => __('No Coupons found in trash',
					                                                               'woocommerce'),
					                                 'parent'                => __('Parent Coupon', 'woocommerce'),
					                                 'filter_items_list'     => __('Filter coupons', 'woocommerce'),
					                                 'items_list_navigation' => __('Coupons navigation', 'woocommerce'),
					                                 'items_list'            => __('Coupons list', 'woocommerce'),
				                                 ],
				                                 'description'         => __('This is where you can add new coupons that customers can use in your store.',
				                                                             'woocommerce'),
				                                 'public'              => FALSE,
				                                 'show_ui'             => TRUE,
				                                 'capability_type'     => 'shop_coupon',
				                                 'map_meta_cap'        => TRUE,
				                                 'publicly_queryable'  => FALSE,
				                                 'exclude_from_search' => TRUE,
				                                 'show_in_menu'        => current_user_can('manage_woocommerce')
					                                 ? 'woocommerce' : TRUE,
				                                 'hierarchical'        => FALSE,
				                                 'rewrite'             => FALSE,
				                                 'query_var'           => FALSE,
				                                 'supports'            => ['title'],
				                                 'show_in_nav_menus'   => FALSE,
				                                 'show_in_admin_bar'   => TRUE
			                                 ]
			                   )
			);
		}
		register_post_type('shop_webhook',
		                   apply_filters('woocommerce_register_post_type_shop_webhook',
		                                 [
			                                 'labels'              => [
				                                 'name'               => __('Webhooks', 'woocommerce'),
				                                 'singular_name'      => __('Webhook', 'woocommerce'),
				                                 'menu_name'          => _x('Webhooks', 'Admin menu name',
				                                                            'woocommerce'),
				                                 'add_new'            => __('Add Webhook', 'woocommerce'),
				                                 'add_new_item'       => __('Add New Webhook', 'woocommerce'),
				                                 'edit'               => __('Edit', 'woocommerce'),
				                                 'edit_item'          => __('Edit Webhook', 'woocommerce'),
				                                 'new_item'           => __('New Webhook', 'woocommerce'),
				                                 'view'               => __('View Webhooks', 'woocommerce'),
				                                 'view_item'          => __('View Webhook', 'woocommerce'),
				                                 'search_items'       => __('Search Webhooks', 'woocommerce'),
				                                 'not_found'          => __('No Webhooks found', 'woocommerce'),
				                                 'not_found_in_trash' => __('No Webhooks found in trash',
				                                                            'woocommerce'),
				                                 'parent'             => __('Parent Webhook', 'woocommerce')
			                                 ],
			                                 'public'              => FALSE,
			                                 'show_ui'             => TRUE,
			                                 'capability_type'     => 'shop_webhook',
			                                 'map_meta_cap'        => TRUE,
			                                 'publicly_queryable'  => FALSE,
			                                 'exclude_from_search' => TRUE,
			                                 'show_in_menu'        => FALSE,
			                                 'hierarchical'        => FALSE,
			                                 'rewrite'             => FALSE,
			                                 'query_var'           => FALSE,
			                                 'supports'            => FALSE,
			                                 'show_in_nav_menus'   => FALSE,
			                                 'show_in_admin_bar'   => FALSE
		                                 ]
		                   )
		);
	}

	/**
	 * Register our custom post statuses, used for order status.
	 */
	public static function register_post_status() {
		$order_statuses = apply_filters('woocommerce_register_shop_order_post_statuses',
		                                [
			                                'wc-pending'    => [
				                                'label'                     => _x('Pending Payment', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Pending Payment <span class="count">(%s)</span>',
				                                                                       'Pending Payment <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-processing' => [
				                                'label'                     => _x('Processing', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Processing <span class="count">(%s)</span>',
				                                                                       'Processing <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-on-hold'    => [
				                                'label'                     => _x('On Hold', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('On Hold <span class="count">(%s)</span>',
				                                                                       'On Hold <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-completed'  => [
				                                'label'                     => _x('Completed', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Completed <span class="count">(%s)</span>',
				                                                                       'Completed <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-cancelled'  => [
				                                'label'                     => _x('Cancelled', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Cancelled <span class="count">(%s)</span>',
				                                                                       'Cancelled <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-refunded'   => [
				                                'label'                     => _x('Refunded', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Refunded <span class="count">(%s)</span>',
				                                                                       'Refunded <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
			                                'wc-failed'     => [
				                                'label'                     => _x('Failed', 'Order status',
				                                                                  'woocommerce'),
				                                'public'                    => FALSE,
				                                'exclude_from_search'       => FALSE,
				                                'show_in_admin_all_list'    => TRUE,
				                                'show_in_admin_status_list' => TRUE,
				                                'label_count'               => _n_noop('Failed <span class="count">(%s)</span>',
				                                                                       'Failed <span class="count">(%s)</span>',
				                                                                       'woocommerce')
			                                ],
		                                ]
		);
		foreach ($order_statuses as $order_status => $values) {
			register_post_status($order_status, $values);
		}
	}

	/**
	 * Add Product Support to Jetpack Omnisearch.
	 */
	public static function support_jetpack_omnisearch() {
		if (class_exists('Jetpack_Omnisearch_Posts')) {
			new Jetpack_Omnisearch_Posts('product');
		}
	}

	/**
	 * Added product for Jetpack related posts.
	 *
	 * @param  array $post_types
	 *
	 * @return array
	 */
	public static function rest_api_allowed_post_types($post_types) {
		$post_types[] = 'product';

		return $post_types;
	}
}

WC_Post_types::init();
