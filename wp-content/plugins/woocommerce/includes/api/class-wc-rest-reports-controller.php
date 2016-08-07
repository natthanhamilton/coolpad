<?php
/**
 * REST API Reports controller
 *
 * Handles requests to the reports endpoint.
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
 * REST API Reports controller class.
 *
 * @package WooCommerce/API
 * @extends WC_REST_Controller
 */
class WC_REST_Reports_Controller extends WC_REST_Controller {
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
	protected $rest_base = 'reports';

	/**
	 * Register the routes for reports.
	 */
	public function register_routes() {
		register_rest_route($this->namespace, '/' . $this->rest_base, [
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [$this, 'get_items'],
				'permission_callback' => [$this, 'get_items_permissions_check'],
				'args'                => $this->get_collection_params(),
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
		return [
			'context' => $this->get_context_param(['default' => 'view']),
		];
	}

	/**
	 * Check whether a given request has permission to read reports.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('reports', 'read')) {
			return new WP_Error('woocommerce_rest_cannot_view', __('Sorry, you cannot list resources.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Get all reports.
	 *
	 * @param WP_REST_Request $request
	 *
	 * @return array|WP_Error
	 */
	public function get_items($request) {
		$data    = [];
		$reports = [
			[
				'slug'        => 'sales',
				'description' => __('List of sales reports.', 'woocommerce'),
			],
			[
				'slug'        => 'top_sellers',
				'description' => __('List of top sellers products.', 'woocommerce'),
			],
		];
		foreach ($reports as $report) {
			$item   = $this->prepare_item_for_response((object)$report, $request);
			$data[] = $this->prepare_response_for_collection($item);
		}

		return rest_ensure_response($data);
	}

	/**
	 * Prepare a report object for serialization.
	 *
	 * @param stdClass        $report  Report data.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response($report, $request) {
		$data = [
			'slug'        => $report->slug,
			'description' => $report->description,
		];
		$context = !empty($request['context']) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object($data, $request);
		$data    = $this->filter_response_by_context($data, $context);
		// Wrap the data in a response object.
		$response = rest_ensure_response($data);
		$response->add_links([
			                     'self'       => [
				                     'href' => rest_url(sprintf('/%s/%s/%s', $this->namespace, $this->rest_base,
				                                                $report->slug)),
			                     ],
			                     'collection' => [
				                     'href' => rest_url(sprintf('%s/%s', $this->namespace, $this->rest_base)),
			                     ],
		                     ]);

		/**
		 * Filter a report returned from the API.
		 *
		 * Allows modification of the report data right before it is returned.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param object           $report   The original report object.
		 * @param WP_REST_Request  $request  Request used to generate the response.
		 */
		return apply_filters('woocommerce_rest_prepare_report', $response, $report, $request);
	}

	/**
	 * Get the Report's schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'report',
			'type'       => 'object',
			'properties' => [
				'slug'        => [
					'description' => __('An alphanumeric identifier for the resource.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view'],
					'readonly'    => TRUE,
				],
				'description' => [
					'description' => __('A human-readable description of the resource.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view'],
					'readonly'    => TRUE,
				],
			],
		];

		return $this->add_additional_fields_schema($schema);
	}
}
