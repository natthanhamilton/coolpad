<?php
/**
 * REST API Reports controller
 *
 * Handles requests to the reports/top_sellers endpoint.
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
 * REST API Report Top Sellers controller class.
 *
 * @package WooCommerce/API
 * @extends WC_REST_Report_Sales_Controller
 */
class WC_REST_Report_Top_Sellers_Controller extends WC_REST_Report_Sales_Controller {
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
	protected $rest_base = 'reports/top_sellers';

	/**
	 * Get sales reports.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array|WP_Error
	 */
	public function get_items($request) {
		// Set date filtering.
		$filter = [
			'period'   => $request['period'],
			'date_min' => $request['date_min'],
			'date_max' => $request['date_max'],
		];
		$this->setup_report($filter);
		$report_data = $this->report->get_order_report_data([
			                                                    'data'         => [
				                                                    '_product_id' => [
					                                                    'type'            => 'order_item_meta',
					                                                    'order_item_type' => 'line_item',
					                                                    'function'        => '',
					                                                    'name'            => 'product_id',
				                                                    ],
				                                                    '_qty'        => [
					                                                    'type'            => 'order_item_meta',
					                                                    'order_item_type' => 'line_item',
					                                                    'function'        => 'SUM',
					                                                    'name'            => 'order_item_qty',
				                                                    ]
			                                                    ],
			                                                    'order_by'     => 'order_item_qty DESC',
			                                                    'group_by'     => 'product_id',
			                                                    'limit'        => isset($filter['limit'])
				                                                    ? absint($filter['limit']) : 12,
			                                                    'query_type'   => 'get_results',
			                                                    'filter_range' => TRUE,
		                                                    ]);
		$top_sellers = [];
		foreach ($report_data as $item) {
			$product = wc_get_product($item->product_id);
			if ($product) {
				$top_sellers[] = [
					'name'       => $product->get_title(),
					'product_id' => (int)$item->product_id,
					'quantity'   => wc_stock_amount($item->order_item_qty),
				];
			}
		}
		$data = [];
		foreach ($top_sellers as $top_seller) {
			$item   = $this->prepare_item_for_response((object)$top_seller, $request);
			$data[] = $this->prepare_response_for_collection($item);
		}

		return rest_ensure_response($data);
	}

	/**
	 * Prepare a report sales object for serialization.
	 *
	 * @param stdClass        $top_seller
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response($top_seller, $request) {
		$data = [
			'name'       => $top_seller->name,
			'product_id' => $top_seller->product_id,
			'quantity'   => $top_seller->quantity,
		];
		$context = !empty($request['context']) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object($data, $request);
		$data    = $this->filter_response_by_context($data, $context);
		// Wrap the data in a response object.
		$response = rest_ensure_response($data);
		$response->add_links([
			                     'about'   => [
				                     'href' => rest_url(sprintf('%s/reports', $this->namespace)),
			                     ],
			                     'product' => [
				                     'href' => rest_url(sprintf('/%s/products/%s', $this->namespace,
				                                                $top_seller->product_id)),
			                     ],
		                     ]);

		/**
		 * Filter a report top sellers returned from the API.
		 *
		 * Allows modification of the report top sellers data right before it is returned.
		 *
		 * @param WP_REST_Response $response   The response object.
		 * @param stdClass         $top_seller The original report object.
		 * @param WP_REST_Request  $request    Request used to generate the response.
		 */
		return apply_filters('woocommerce_rest_prepare_report_top_sellers', $response, $top_seller, $request);
	}

	/**
	 * Get the Report's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'top_sellers_report',
			'type'       => 'object',
			'properties' => [
				'name'       => [
					'description' => __('Product name.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view'],
					'readonly'    => TRUE,
				],
				'product_id' => [
					'description' => __('Product ID.', 'woocommerce'),
					'type'        => 'integer',
					'context'     => ['view'],
					'readonly'    => TRUE,
				],
				'quantity'   => [
					'description' => __('Total number of purchases.', 'woocommerce'),
					'type'        => 'integer',
					'context'     => ['view'],
					'readonly'    => TRUE,
				],
			],
		];

		return $this->add_additional_fields_schema($schema);
	}
}
