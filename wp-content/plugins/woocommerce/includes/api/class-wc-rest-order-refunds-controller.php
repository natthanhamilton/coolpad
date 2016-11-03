<?php
/**
 * REST API Order Refunds controller
 *
 * Handles requests to the /orders/<order_id>/refunds endpoint.
 *
 * @author   WooThemes
 * @category API
 * @package  WooCommerce/API
 * @since    2.6.0
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * REST API Order Refunds controller class.
 *
 * @package WooCommerce/API
 * @extends WC_REST_Posts_Controller
 */
class WC_REST_Order_Refunds_Controller extends WC_REST_Posts_Controller {
	/**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'wc/v1';
	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'orders/(?P<order_id>[\d]+)/refunds';
	/**
	 * Post type.
	 *
	 * @var string
	 */
	protected $post_type = 'shop_order_refund';

	/**
	 * Order refunds actions.
	 */
	public function __construct() {
		add_filter("woocommerce_rest_{$this->post_type}_trashable", '__return_false');
		add_filter("woocommerce_rest_{$this->post_type}_query", [$this, 'query_args'], 10, 2);
	}

	/**
	 * Register the routes for order refunds.
	 */
	public function register_routes() {
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [$this, 'get_items'],
				'permission_callback' => [$this, 'get_items_permissions_check'],
				'args'                => $this->get_collection_params(),
			],
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => [$this, 'create_item'],
				'permission_callback' => [$this, 'create_item_permissions_check'],
				'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::CREATABLE),
			],
			'schema' => [$this, 'get_public_item_schema'],
		]);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [$this, 'get_item'],
				'permission_callback' => [$this, 'get_item_permissions_check'],
				'args'                => [
					'context' => $this->get_context_param(['default' => 'view']),
				],
			],
			[
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => [$this, 'delete_item'],
				'permission_callback' => [$this, 'delete_item_permissions_check'],
				'args'                => [
					'force'    => [
						'default'     => FALSE,
						'description' => __('Required to be true, as resource does not support trashing.',
						                    'woocommerce'),
					],
					'reassign' => [],
				],
			],
			'schema' => [$this, 'get_public_item_schema'],
		]);
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params       = parent::get_collection_params();
		$params['dp'] = [
			'default'           => 2,
			'description'       => __('Number of decimal points to use in each resource.', 'woocommerce'),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		];

		return $params;
	}

	/**
	 * Query args.
	 *
	 * @param array           $args
	 * @param WP_REST_Request $request
	 *
	 * @return array
	 */
	public function query_args($args, $request) {
		// Set post_status.
		$args['post_status'] = 'any';

		return $args;
	}	/**
	 * Prepare a single order refund output for response.
	 *
	 * @param WP_Post         $post    Post object.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response $data
	 */
	public function prepare_item_for_response($post, $request) {
		global $wpdb;
		$order = wc_get_order((int)$request['order_id']);
		if (!$order) {
			return new WP_Error('woocommerce_rest_invalid_order_id', __('Invalid order ID.', 'woocommerce'), 404);
		}
		$refund = wc_get_order($post);
		if (!$refund || intval($refund->post->post_parent) !== intval($order->id)) {
			return new WP_Error('woocommerce_rest_invalid_order_refund_id',
			                    __('Invalid order refund ID.', 'woocommerce'), 404);
		}
		$dp   = $request['dp'];
		$data = [
			'id'           => $refund->id,
			'date_created' => wc_rest_prepare_date_response($refund->date),
			'amount'       => wc_format_decimal($refund->get_refund_amount(), $dp),
			'reason'       => $refund->get_refund_reason(),
			'line_items'   => [],
		];
		// Add line items.
		foreach ($refund->get_items() as $item_id => $item) {
			$product      = $refund->get_product_from_item($item);
			$product_id   = 0;
			$variation_id = 0;
			$product_sku  = NULL;
			// Check if the product exists.
			if (is_object($product)) {
				$product_id   = $product->id;
				$variation_id = $product->variation_id;
				$product_sku  = $product->get_sku();
			}
			$meta       = new WC_Order_Item_Meta($item, $product);
			$item_meta  = [];
			$hideprefix = 'true' === $request['all_item_meta'] ? NULL : '_';
			foreach ($meta->get_formatted($hideprefix) as $meta_key => $formatted_meta) {
				$item_meta[] = [
					'key'   => $formatted_meta['key'],
					'label' => $formatted_meta['label'],
					'value' => $formatted_meta['value'],
				];
			}
			$line_item       = [
				'id'           => $item_id,
				'name'         => $item['name'],
				'sku'          => $product_sku,
				'product_id'   => (int)$product_id,
				'variation_id' => (int)$variation_id,
				'quantity'     => wc_stock_amount($item['qty']),
				'tax_class'    => !empty($item['tax_class']) ? $item['tax_class'] : '',
				'price'        => wc_format_decimal($refund->get_item_total($item, FALSE, FALSE), $dp),
				'subtotal'     => wc_format_decimal($refund->get_line_subtotal($item, FALSE, FALSE), $dp),
				'subtotal_tax' => wc_format_decimal($item['line_subtotal_tax'], $dp),
				'total'        => wc_format_decimal($refund->get_line_total($item, FALSE, FALSE), $dp),
				'total_tax'    => wc_format_decimal($item['line_tax'], $dp),
				'taxes'        => [],
				'meta'         => $item_meta,
			];
			$item_line_taxes = maybe_unserialize($item['line_tax_data']);
			if (isset($item_line_taxes['total'])) {
				$line_tax = [];
				foreach ($item_line_taxes['total'] as $tax_rate_id => $tax) {
					$line_tax[ $tax_rate_id ] = [
						'id'       => $tax_rate_id,
						'total'    => $tax,
						'subtotal' => '',
					];
				}
				foreach ($item_line_taxes['subtotal'] as $tax_rate_id => $tax) {
					$line_tax[ $tax_rate_id ]['subtotal'] = $tax;
				}
				$line_item['taxes'] = array_values($line_tax);
			}
			$data['line_items'][] = $line_item;
		}
		$context = !empty($request['context']) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object($data, $request);
		$data    = $this->filter_response_by_context($data, $context);
		// Wrap the data in a response object.
		$response = rest_ensure_response($data);
		$response->add_links($this->prepare_links($refund));

		/**
		 * Filter the data for a response.
		 *
		 * The dynamic portion of the hook name, $this->post_type, refers to post_type of the post being
		 * prepared for the response.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param WP_Post          $post     Post object.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters("woocommerce_rest_prepare_{$this->post_type}", $response, $post, $request);
	}



	/**
	 * Prepare links for the request.
	 *
	 * @param WC_Order_Refund $refund Comment object.
	 *
	 * @return array Links for the given order refund.
	 */
	protected function prepare_links($refund) {
		$order_id = $refund->post->post_parent;
		$base     = str_replace('(?P<order_id>[\d]+)', $order_id, $this->rest_base);
		$links    = [
			'self'       => [
				'href' => rest_url(sprintf('/%s/%s/%d', $this->namespace, $base, $refund->id)),
			],
			'collection' => [
				'href' => rest_url(sprintf('/%s/%s', $this->namespace, $base)),
			],
			'up'         => [
				'href' => rest_url(sprintf('/%s/orders/%d', $this->namespace, $order_id)),
			],
		];

		return $links;
	}

	/**
	 * Create a single item.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item($request) {
		if (!empty($request['id'])) {
			return new WP_Error("woocommerce_rest_{$this->post_type}_exists",
			                    sprintf(__('Cannot create existing %s.', 'woocommerce'), $this->post_type),
			                    ['status' => 400]);
		}
		$order_data = get_post((int)$request['order_id']);
		if (empty($order_data)) {
			return new WP_Error('woocommerce_rest_invalid_order', __('Order is invalid', 'woocommerce'), 400);
		}
		if (0 > $request['amount']) {
			return new WP_Error('woocommerce_rest_invalid_order_refund',
			                    __('Refund amount must be greater than zero.', 'woocommerce'), 400);
		}
		$api_refund = is_bool($request['api_refund']) ? $request['api_refund'] : TRUE;
		$data       = [
			'order_id'   => $order_data->ID,
			'amount'     => $request['amount'],
			'line_items' => $request['line_items'],
		];
		// Create the refund.
		$refund = wc_create_refund($data);
		if (!$refund) {
			return new WP_Error('woocommerce_rest_cannot_create_order_refund',
			                    __('Cannot create order refund, please try again.', 'woocommerce'), 500);
		}
		// Refund via API.
		if ($api_refund) {
			if (WC()->payment_gateways()) {
				$payment_gateways = WC()->payment_gateways->payment_gateways();
			}
			$order = wc_get_order($order_data);
			if (isset($payment_gateways[ $order->payment_method ]) && $payment_gateways[ $order->payment_method ]->supports('refunds')) {
				$result = $payment_gateways[ $order->payment_method ]->process_refund($order_id,
				                                                                      $refund->get_refund_amount(),
				                                                                      $refund->get_refund_reason());
				if (is_wp_error($result)) {
					return $result;
				} elseif (!$result) {
					return new WP_Error('woocommerce_rest_create_order_refund_api_failed',
					                    __('An error occurred while attempting to create the refund using the payment gateway API.',
					                       'woocommerce'), 500);
				}
			}
		}
		$post = get_post($refund->id);
		$this->update_additional_fields_for_object($post, $request);
		/**
		 * Fires after a single item is created or updated via the REST API.
		 *
		 * @param object          $post     Inserted object (not a WP_Post object).
		 * @param WP_REST_Request $request  Request object.
		 * @param boolean         $creating True when creating item, false when updating.
		 */
		do_action("woocommerce_rest_insert_{$this->post_type}", $post, $request, TRUE);
		$request->set_param('context', 'edit');
		$response = $this->prepare_item_for_response($post, $request);
		$response = rest_ensure_response($response);
		$response->set_status(201);
		$response->header('Location', rest_url(sprintf('/%s/%s/%d', $this->namespace, $this->rest_base, $post->ID)));

		return $response;
	}

	/**
	 * Get the Order's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => $this->post_type,
			'type'       => 'object',
			'properties' => [
				'id'           => [
					'description' => __('Unique identifier for the resource.', 'woocommerce'),
					'type'        => 'integer',
					'context'     => ['view', 'edit'],
					'readonly'    => TRUE,
				],
				'date_created' => [
					'description' => __("The date the order refund was created, in the site's timezone.",
					                    'woocommerce'),
					'type'        => 'date-time',
					'context'     => ['view', 'edit'],
					'readonly'    => TRUE,
				],
				'amount'       => [
					'description' => __('Refund amount.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'reason'       => [
					'description' => __('Reason for refund.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'line_items'   => [
					'description' => __('Line items data.', 'woocommerce'),
					'type'        => 'array',
					'context'     => ['view', 'edit'],
					'properties'  => [
						'id'           => [
							'description' => __('Item ID.', 'woocommerce'),
							'type'        => 'integer',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
						],
						'name'         => [
							'description' => __('Product name.', 'woocommerce'),
							'type'        => 'integer',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
						],
						'sku'          => [
							'description' => __('Product SKU.', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
						],
						'product_id'   => [
							'description' => __('Product ID.', 'woocommerce'),
							'type'        => 'integer',
							'context'     => ['view', 'edit'],
						],
						'variation_id' => [
							'description' => __('Variation ID, if applicable.', 'woocommerce'),
							'type'        => 'integer',
							'context'     => ['view', 'edit'],
						],
						'quantity'     => [
							'description' => __('Quantity ordered.', 'woocommerce'),
							'type'        => 'integer',
							'context'     => ['view', 'edit'],
						],
						'tax_class'    => [
							'description' => __('Tax class of product.', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
						],
						'price'        => [
							'description' => __('Product price.', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
						],
						'subtotal'     => [
							'description' => __('Line subtotal (before discounts).', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
						],
						'subtotal_tax' => [
							'description' => __('Line subtotal tax (before discounts).', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
						],
						'total'        => [
							'description' => __('Line total (after discounts).', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
						],
						'total_tax'    => [
							'description' => __('Line total tax (after discounts).', 'woocommerce'),
							'type'        => 'string',
							'context'     => ['view', 'edit'],
						],
						'taxes'        => [
							'description' => __('Line taxes.', 'woocommerce'),
							'type'        => 'array',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
							'properties'  => [
								'id'       => [
									'description' => __('Tax rate ID.', 'woocommerce'),
									'type'        => 'integer',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
								'total'    => [
									'description' => __('Tax total.', 'woocommerce'),
									'type'        => 'string',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
								'subtotal' => [
									'description' => __('Tax subtotal.', 'woocommerce'),
									'type'        => 'string',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
							],
						],
						'meta'         => [
							'description' => __('Line item meta data.', 'woocommerce'),
							'type'        => 'array',
							'context'     => ['view', 'edit'],
							'readonly'    => TRUE,
							'properties'  => [
								'key'   => [
									'description' => __('Meta key.', 'woocommerce'),
									'type'        => 'string',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
								'label' => [
									'description' => __('Meta label.', 'woocommerce'),
									'type'        => 'string',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
								'value' => [
									'description' => __('Meta value.', 'woocommerce'),
									'type'        => 'string',
									'context'     => ['view', 'edit'],
									'readonly'    => TRUE,
								],
							],
						],
					],
				],
			],
		];

		return $this->add_additional_fields_schema($schema);
	}
}
