<?php
/**
 * REST API Taxes controller
 *
 * Handles requests to the /taxes endpoint.
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
 * REST API Taxes controller class.
 *
 * @package WooCommerce/API
 * @extends WC_REST_Controller
 */
class WC_REST_Taxes_Controller extends WC_REST_Controller {
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
	protected $rest_base = 'taxes';

	/**
	 * Register the routes for taxes.
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
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [$this, 'update_item'],
				'permission_callback' => [$this, 'update_item_permissions_check'],
				'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::EDITABLE),
			],
			[
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => [$this, 'delete_item'],
				'permission_callback' => [$this, 'delete_item_permissions_check'],
				'args'                => [
					'force' => [
						'default'     => FALSE,
						'description' => __('Required to be true, as resource does not support trashing.',
						                    'woocommerce'),
					],
				],
			],
			'schema' => [$this, 'get_public_item_schema'],
		]);
		register_rest_route($this->namespace, '/' . $this->rest_base . '/batch', [
			[
				'methods'             => WP_REST_Server::EDITABLE,
				'callback'            => [$this, 'batch_items'],
				'permission_callback' => [$this, 'batch_items_permissions_check'],
				'args'                => $this->get_endpoint_args_for_item_schema(WP_REST_Server::EDITABLE),
			],
			'schema' => [$this, 'get_public_batch_schema'],
		]);
	}

	/**
	 * Get the query params for collections.
	 *
	 * @return array
	 */
	public function get_collection_params() {
		$params = parent::get_collection_params();
		$params['context']['default'] = 'view';
		$params['exclude'] = [
			'description'       => __('Ensure result set excludes specific ids.', 'woocommerce'),
			'type'              => 'array',
			'default'           => [],
			'sanitize_callback' => 'wp_parse_id_list',
		];
		$params['include'] = [
			'description'       => __('Limit result set to specific ids.', 'woocommerce'),
			'type'              => 'array',
			'default'           => [],
			'sanitize_callback' => 'wp_parse_id_list',
		];
		$params['offset']  = [
			'description'       => __('Offset the result set by a specific number of items.', 'woocommerce'),
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'validate_callback' => 'rest_validate_request_arg',
		];
		$params['order']   = [
			'default'           => 'asc',
			'description'       => __('Order sort attribute ascending or descending.', 'woocommerce'),
			'enum'              => ['asc', 'desc'],
			'sanitize_callback' => 'sanitize_key',
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		];
		$params['orderby'] = [
			'default'           => 'order',
			'description'       => __('Sort collection by object attribute.', 'woocommerce'),
			'enum'              => [
				'id',
				'order',
			],
			'sanitize_callback' => 'sanitize_key',
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		];
		$params['class']   = [
			'description'       => __('Sort by tax class.', 'woocommerce'),
			'enum'              => array_merge(['standard'], array_map('sanitize_title', WC_Tax::get_tax_classes())),
			'sanitize_callback' => 'sanitize_title',
			'type'              => 'string',
			'validate_callback' => 'rest_validate_request_arg',
		];

		return $params;
	}

	/**
	 * Check whether a given request has permission to read taxes.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_items_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'read')) {
			return new WP_Error('woocommerce_rest_cannot_view', __('Sorry, you cannot list resources.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Check if a given request has access create taxes.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return boolean
	 */
	public function create_item_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'create')) {
			return new WP_Error('woocommerce_rest_cannot_create',
			                    __('Sorry, you are not allowed to create resources.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Check if a given request has access to read a tax.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|boolean
	 */
	public function get_item_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'read')) {
			return new WP_Error('woocommerce_rest_cannot_view',
			                    __('Sorry, you cannot view this resource.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Check if a given request has access update a tax.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return boolean
	 */
	public function update_item_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'edit')) {
			return new WP_Error('woocommerce_rest_cannot_edit',
			                    __('Sorry, you are not allowed to edit this resource.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Check if a given request has access delete a tax.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return boolean
	 */
	public function delete_item_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'delete')) {
			return new WP_Error('woocommerce_rest_cannot_delete',
			                    __('Sorry, you are not allowed to delete this resource.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}

	/**
	 * Get all taxes.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items($request) {
		global $wpdb;
		$prepared_args            = [];
		$prepared_args['exclude'] = $request['exclude'];
		$prepared_args['include'] = $request['include'];
		$prepared_args['order']   = $request['order'];
		$prepared_args['number']  = $request['per_page'];
		if (!empty($request['offset'])) {
			$prepared_args['offset'] = $request['offset'];
		} else {
			$prepared_args['offset'] = ($request['page'] - 1) * $prepared_args['number'];
		}
		$orderby_possibles        = [
			'id'    => 'tax_rate_id',
			'order' => 'tax_rate_order',
		];
		$prepared_args['orderby'] = $orderby_possibles[ $request['orderby'] ];
		$prepared_args['class']   = $request['class'];
		/**
		 * Filter arguments, before passing to $wpdb->get_results(), when querying taxes via the REST API.
		 *
		 * @param array           $prepared_args Array of arguments for $wpdb->get_results().
		 * @param WP_REST_Request $request       The current request.
		 */
		$prepared_args = apply_filters('woocommerce_rest_tax_query', $prepared_args, $request);
		$query
			= "
			SELECT *
			FROM {$wpdb->prefix}woocommerce_tax_rates
			WHERE 1 = 1
		";
		// Filter by tax class.
		if (!empty($prepared_args['class'])) {
			$class = 'standard' !== $prepared_args['class'] ? sanitize_title($prepared_args['class']) : '';
			$query .= " AND tax_rate_class = '$class'";
		}
		// Order tax rates.
		$order_by = sprintf(' ORDER BY %s', sanitize_key($prepared_args['orderby']));
		// Pagination.
		$pagination = sprintf(' LIMIT %d, %d', $prepared_args['offset'], $prepared_args['number']);
		// Query taxes.
		$results = $wpdb->get_results($query . $order_by . $pagination);
		$taxes = [];
		foreach ($results as $tax) {
			$data    = $this->prepare_item_for_response($tax, $request);
			$taxes[] = $this->prepare_response_for_collection($data);
		}
		$response = rest_ensure_response($taxes);
		// Store pagation values for headers then unset for count query.
		$per_page = (int)$prepared_args['number'];
		$page     = ceil((((int)$prepared_args['offset']) / $per_page) + 1);
		// Query only for ids.
		$wpdb->get_results(str_replace('SELECT *', 'SELECT tax_rate_id', $query));
		// Calcule totals.
		$total_taxes = (int)$wpdb->num_rows;
		$response->header('X-WP-Total', (int)$total_taxes);
		$max_pages = ceil($total_taxes / $per_page);
		$response->header('X-WP-TotalPages', (int)$max_pages);
		$base = add_query_arg($request->get_query_params(),
		                      rest_url(sprintf('/%s/%s', $this->namespace, $this->rest_base)));
		if ($page > 1) {
			$prev_page = $page - 1;
			if ($prev_page > $max_pages) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg('page', $prev_page, $base);
			$response->link_header('prev', $prev_link);
		}
		if ($max_pages > $page) {
			$next_page = $page + 1;
			$next_link = add_query_arg('page', $next_page, $base);
			$response->link_header('next', $next_link);
		}

		return $response;
	}

	/**
	 * Prepare a single tax output for response.
	 *
	 * @param stdClass        $tax     Tax object.
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return WP_REST_Response $response Response data.
	 */
	public function prepare_item_for_response($tax, $request) {
		global $wpdb;
		$id   = (int)$tax->tax_rate_id;
		$data = [
			'id'       => $id,
			'country'  => $tax->tax_rate_country,
			'state'    => $tax->tax_rate_state,
			'postcode' => '',
			'city'     => '',
			'rate'     => $tax->tax_rate,
			'name'     => $tax->tax_rate_name,
			'priority' => (int)$tax->tax_rate_priority,
			'compound' => (bool)$tax->tax_rate_compound,
			'shipping' => (bool)$tax->tax_rate_shipping,
			'order'    => (int)$tax->tax_rate_order,
			'class'    => $tax->tax_rate_class ? $tax->tax_rate_class : 'standard',
		];
		// Get locales from a tax rate.
		$locales = $wpdb->get_results($wpdb->prepare("
			SELECT location_code, location_type
			FROM {$wpdb->prefix}woocommerce_tax_rate_locations
			WHERE tax_rate_id = %d
		", $id));
		if (!is_wp_error($tax) && !is_null($tax)) {
			foreach ($locales as $locale) {
				$data[ $locale->location_type ] = $locale->location_code;
			}
		}
		$context = !empty($request['context']) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object($data, $request);
		$data    = $this->filter_response_by_context($data, $context);
		// Wrap the data in a response object.
		$response = rest_ensure_response($data);
		$response->add_links($this->prepare_links($tax));

		/**
		 * Filter tax object returned from the REST API.
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param stdClass         $tax      Tax object used to create response.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters('woocommerce_rest_prepare_tax', $response, $tax, $request);
	}

	/**
	 * Prepare links for the request.
	 *
	 * @param stdClass $tax Tax object.
	 *
	 * @return array Links for the given tax.
	 */
	protected function prepare_links($tax) {
		$links = [
			'self'       => [
				'href' => rest_url(sprintf('/%s/%s/%d', $this->namespace, $this->rest_base, $tax->tax_rate_id)),
			],
			'collection' => [
				'href' => rest_url(sprintf('/%s/%s', $this->namespace, $this->rest_base)),
			],
		];

		return $links;
	}

	/**
	 * Create a single tax.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_item($request) {
		if (!empty($request['id'])) {
			return new WP_Error('woocommerce_rest_tax_exists', __('Cannot create existing resource.', 'woocommerce'),
			                    ['status' => 400]);
		}
		$tax = $this->create_or_update_tax($request);
		$this->update_additional_fields_for_object($tax, $request);
		/**
		 * Fires after a tax is created or updated via the REST API.
		 *
		 * @param stdClass        $tax      Data used to create the tax.
		 * @param WP_REST_Request $request  Request object.
		 * @param boolean         $creating True when creating tax, false when updating tax.
		 */
		do_action('woocommerce_rest_insert_tax', $tax, $request, TRUE);
		$request->set_param('context', 'edit');
		$response = $this->prepare_item_for_response($tax, $request);
		$response = rest_ensure_response($response);
		$response->set_status(201);
		$response->header('Location',
		                  rest_url(sprintf('/%s/%s/%d', $this->namespace, $this->rest_base, $tax->tax_rate_id)));

		return $response;
	}

	/**
	 * Take tax data from the request and return the updated or newly created rate.
	 *
	 * @todo Replace with CRUD in 2.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @param stdClass|null   $current Existing tax object.
	 *
	 * @return stdClass
	 */
	protected function create_or_update_tax($request, $current = NULL) {
		$id     = absint(isset($request['id']) ? $request['id'] : 0);
		$data   = [];
		$fields = [
			'tax_rate_country',
			'tax_rate_state',
			'tax_rate',
			'tax_rate_name',
			'tax_rate_priority',
			'tax_rate_compound',
			'tax_rate_shipping',
			'tax_rate_order',
			'tax_rate_class',
		];
		foreach ($fields as $field) {
			// Keys via API differ from the stored names returned by _get_tax_rate.
			$key = 'tax_rate' === $field ? 'rate' : str_replace('tax_rate_', '', $field);
			// Remove data that was not posted.
			if (!isset($request[ $key ])) {
				continue;
			}
			// Test new data against current data.
			if ($current && $current->$field === $request[ $key ]) {
				continue;
			}
			// Add to data array.
			switch ($key) {
				case 'tax_rate_priority' :
				case 'tax_rate_compound' :
				case 'tax_rate_shipping' :
				case 'tax_rate_order' :
					$data[ $field ] = absint($request[ $key ]);
					break;
				case 'tax_rate_class' :
					$data[ $field ] = 'standard' !== $request['tax_rate_class'] ? $request['tax_rate_class'] : '';
					break;
				default :
					$data[ $field ] = wc_clean($request[ $key ]);
					break;
			}
		}
		if ($id) {
			WC_Tax::_update_tax_rate($id, $data);
		} else {
			$id = WC_Tax::_insert_tax_rate($data);
		}
		// Add locales.
		if (!empty($request['postcode'])) {
			WC_Tax::_update_tax_rate_postcodes($id, wc_clean($request['postcode']));
		}
		if (!empty($request['city'])) {
			WC_Tax::_update_tax_rate_cities($id, wc_clean($request['city']));
		}

		return WC_Tax::_get_tax_rate($id, OBJECT);
	}

	/**
	 * Get a single tax.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_item($request) {
		$id      = (int)$request['id'];
		$tax_obj = WC_Tax::_get_tax_rate($id, OBJECT);
		if (empty($id) || empty($tax_obj)) {
			return new WP_Error('woocommerce_rest_invalid_id', __('Invalid resource id.', 'woocommerce'),
			                    ['status' => 404]);
		}
		$tax      = $this->prepare_item_for_response($tax_obj, $request);
		$response = rest_ensure_response($tax);

		return $response;
	}

	/**
	 * Update a single tax.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function update_item($request) {
		$id      = (int)$request['id'];
		$tax_obj = WC_Tax::_get_tax_rate($id, OBJECT);
		if (empty($id) || empty($tax_obj)) {
			return new WP_Error('woocommerce_rest_invalid_id', __('Invalid resource id.', 'woocommerce'),
			                    ['status' => 404]);
		}
		$tax = $this->create_or_update_tax($request, $tax_obj);
		$this->update_additional_fields_for_object($tax, $request);
		/**
		 * Fires after a tax is created or updated via the REST API.
		 *
		 * @param stdClass        $tax      Data used to create the tax.
		 * @param WP_REST_Request $request  Request object.
		 * @param boolean         $creating True when creating tax, false when updating tax.
		 */
		do_action('woocommerce_rest_insert_tax', $tax, $request, FALSE);
		$request->set_param('context', 'edit');
		$response = $this->prepare_item_for_response($tax, $request);
		$response = rest_ensure_response($response);

		return $response;
	}

	/**
	 * Delete a single tax.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function delete_item($request) {
		global $wpdb;
		$id    = (int)$request['id'];
		$force = isset($request['force']) ? (bool)$request['force'] : FALSE;
		// We don't support trashing for this type, error out.
		if (!$force) {
			return new WP_Error('woocommerce_rest_trash_not_supported',
			                    __('Taxes do not support trashing.', 'woocommerce'), ['status' => 501]);
		}
		$tax = WC_Tax::_get_tax_rate($id, OBJECT);
		if (empty($id) || empty($tax)) {
			return new WP_Error('woocommerce_rest_invalid_id', __('Invalid resource id.', 'woocommerce'),
			                    ['status' => 400]);
		}
		$request->set_param('context', 'edit');
		$response = $this->prepare_item_for_response($tax, $request);
		WC_Tax::_delete_tax_rate($id);
		if (0 === $wpdb->rows_affected) {
			return new WP_Error('woocommerce_rest_cannot_delete', __('The resource cannot be deleted.', 'woocommerce'),
			                    ['status' => 500]);
		}
		/**
		 * Fires after a tax is deleted via the REST API.
		 *
		 * @param stdClass         $tax      The tax data.
		 * @param WP_REST_Response $response The response returned from the API.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
		do_action('woocommerce_rest_delete_tax', $tax, $response, $request);

		return $response;
	}

	/**
	 * Get the Taxes schema, conforming to JSON Schema.
	 *
	 * @return array
	 */
	public function get_item_schema() {
		$schema = [
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'tax',
			'type'       => 'object',
			'properties' => [
				'id'       => [
					'description' => __('Unique identifier for the resource.', 'woocommerce'),
					'type'        => 'integer',
					'context'     => ['view', 'edit'],
					'readonly'    => TRUE,
				],
				'country'  => [
					'description' => __('Country ISO 3166 code.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'state'    => [
					'description' => __('State code.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'postcode' => [
					'description' => __('Postcode/ZIP.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'city'     => [
					'description' => __('City name.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'rate'     => [
					'description' => __('Tax rate.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'name'     => [
					'description' => __('Tax rate name.', 'woocommerce'),
					'type'        => 'string',
					'context'     => ['view', 'edit'],
				],
				'priority' => [
					'description' => __('Tax priority.', 'woocommerce'),
					'type'        => 'integer',
					'default'     => 1,
					'context'     => ['view', 'edit'],
				],
				'compound' => [
					'description' => __('Whether or not this is a compound rate.', 'woocommerce'),
					'type'        => 'boolean',
					'default'     => FALSE,
					'context'     => ['view', 'edit'],
				],
				'shipping' => [
					'description' => __('Whether or not this tax rate also gets applied to shipping.', 'woocommerce'),
					'type'        => 'boolean',
					'default'     => TRUE,
					'context'     => ['view', 'edit'],
				],
				'order'    => [
					'description' => __('Indicates the order that will appear in queries.', 'woocommerce'),
					'type'        => 'integer',
					'context'     => ['view', 'edit'],
				],
				'class'    => [
					'description' => __('Tax class.', 'woocommerce'),
					'type'        => 'string',
					'default'     => 'standard',
					'enum'        => array_merge(['standard'], array_map('sanitize_title', WC_Tax::get_tax_classes())),
					'context'     => ['view', 'edit'],
				],
			],
		];

		return $this->add_additional_fields_schema($schema);
	}

	/**
	 * Check if a given request has access batch create, update and delete items.
	 *
	 * @param  WP_REST_Request $request Full details about the request.
	 *
	 * @return boolean
	 */
	public function batch_items_permissions_check($request) {
		if (!wc_rest_check_manager_permissions('settings', 'batch')) {
			return new WP_Error('woocommerce_rest_cannot_batch',
			                    __('Sorry, you are not allowed to manipule this resource.', 'woocommerce'),
			                    ['status' => rest_authorization_required_code()]);
		}

		return TRUE;
	}
}
