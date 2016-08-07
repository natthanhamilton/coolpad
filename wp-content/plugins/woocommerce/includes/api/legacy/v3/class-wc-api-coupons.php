<?php
/**
 * WooCommerce API Coupons Class
 *
 * Handles requests to the /coupons endpoint
 *
 * @author      WooThemes
 * @category    API
 * @package     WooCommerce/API
 * @since       2.1
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

class WC_API_Coupons extends WC_API_Resource {
	/** @var string $base the route base */
	protected $base = '/coupons';

	/**
	 * Register the routes for this class
	 *
	 * GET /coupons
	 * GET /coupons/count
	 * GET /coupons/<id>
	 *
	 * @since 2.1
	 *
	 * @param array $routes
	 *
	 * @return array
	 */
	public function register_routes($routes) {
		# GET/POST /coupons
		$routes[ $this->base ] = [
			[[$this, 'get_coupons'], WC_API_Server::READABLE],
			[[$this, 'create_coupon'], WC_API_Server::CREATABLE | WC_API_Server::ACCEPT_DATA],
		];
		# GET /coupons/count
		$routes[ $this->base . '/count' ] = [
			[[$this, 'get_coupons_count'], WC_API_Server::READABLE],
		];
		# GET/PUT/DELETE /coupons/<id>
		$routes[ $this->base . '/(?P<id>\d+)' ] = [
			[[$this, 'get_coupon'], WC_API_Server::READABLE],
			[[$this, 'edit_coupon'], WC_API_SERVER::EDITABLE | WC_API_SERVER::ACCEPT_DATA],
			[[$this, 'delete_coupon'], WC_API_SERVER::DELETABLE],
		];
		# GET /coupons/code/<code>, note that coupon codes can contain spaces, dashes and underscores
		$routes[ $this->base . '/code/(?P<code>\w[\w\s\-]*)' ] = [
			[[$this, 'get_coupon_by_code'], WC_API_Server::READABLE],
		];
		# POST|PUT /coupons/bulk
		$routes[ $this->base . '/bulk' ] = [
			[[$this, 'bulk'], WC_API_Server::EDITABLE | WC_API_Server::ACCEPT_DATA],
		];

		return $routes;
	}

	/**
	 * Get all coupons
	 *
	 * @since 2.1
	 *
	 * @param string $fields
	 * @param array  $filter
	 * @param int    $page
	 *
	 * @return array
	 */
	public function get_coupons($fields = NULL, $filter = [], $page = 1) {
		$filter['page'] = $page;
		$query = $this->query_coupons($filter);
		$coupons = [];
		foreach ($query->posts as $coupon_id) {
			if (!$this->is_readable($coupon_id)) {
				continue;
			}
			$coupons[] = current($this->get_coupon($coupon_id, $fields));
		}
		$this->server->add_pagination_headers($query);

		return ['coupons' => $coupons];
	}

	/**
	 * Helper method to get coupon post objects
	 *
	 * @since 2.1
	 *
	 * @param array $args request arguments for filtering query
	 *
	 * @return WP_Query
	 */
	private function query_coupons($args) {
		// set base query arguments
		$query_args = [
			'fields'      => 'ids',
			'post_type'   => 'shop_coupon',
			'post_status' => 'publish',
		];
		$query_args = $this->merge_query_args($query_args, $args);

		return new WP_Query($query_args);
	}

	/**
	 * Get the coupon for the given ID
	 *
	 * @since 2.1
	 *
	 * @param int    $id     the coupon ID
	 * @param string $fields fields to include in response
	 *
	 * @return array|WP_Error
	 */
	public function get_coupon($id, $fields = NULL) {
		global $wpdb;
		try {
			$id = $this->validate_request($id, 'shop_coupon', 'read');
			if (is_wp_error($id)) {
				return $id;
			}
			// get the coupon code
			$code
				= $wpdb->get_var($wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE id = %s AND post_type = 'shop_coupon' AND post_status = 'publish'",
				                                $id));
			if (is_null($code)) {
				throw new WC_API_Exception('woocommerce_api_invalid_coupon_id', __('Invalid coupon ID', 'woocommerce'),
				                           404);
			}
			$coupon      = new WC_Coupon($code);
			$coupon_post = get_post($coupon->id);
			$coupon_data = [
				'id'                           => $coupon->id,
				'code'                         => $coupon->code,
				'type'                         => $coupon->type,
				'created_at'                   => $this->server->format_datetime($coupon_post->post_date_gmt),
				'updated_at'                   => $this->server->format_datetime($coupon_post->post_modified_gmt),
				'amount'                       => wc_format_decimal($coupon->coupon_amount, 2),
				'individual_use'               => ('yes' === $coupon->individual_use),
				'product_ids'                  => array_map('absint', (array)$coupon->product_ids),
				'exclude_product_ids'          => array_map('absint', (array)$coupon->exclude_product_ids),
				'usage_limit'                  => (!empty($coupon->usage_limit)) ? $coupon->usage_limit : NULL,
				'usage_limit_per_user'         => (!empty($coupon->usage_limit_per_user))
					? $coupon->usage_limit_per_user : NULL,
				'limit_usage_to_x_items'       => (int)$coupon->limit_usage_to_x_items,
				'usage_count'                  => (int)$coupon->usage_count,
				'expiry_date'                  => (!empty($coupon->expiry_date))
					? $this->server->format_datetime($coupon->expiry_date) : NULL,
				'enable_free_shipping'         => $coupon->enable_free_shipping(),
				'product_category_ids'         => array_map('absint', (array)$coupon->product_categories),
				'exclude_product_category_ids' => array_map('absint', (array)$coupon->exclude_product_categories),
				'exclude_sale_items'           => $coupon->exclude_sale_items(),
				'minimum_amount'               => wc_format_decimal($coupon->minimum_amount, 2),
				'maximum_amount'               => wc_format_decimal($coupon->maximum_amount, 2),
				'customer_emails'              => $coupon->customer_email,
				'description'                  => $coupon_post->post_excerpt,
			];

			return ['coupon' => apply_filters('woocommerce_api_coupon_response', $coupon_data, $coupon, $fields,
			                                  $this->server)];
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}

	/**
	 * Get the total number of coupons
	 *
	 * @since 2.1
	 *
	 * @param array $filter
	 *
	 * @return array
	 */
	public function get_coupons_count($filter = []) {
		try {
			if (!current_user_can('read_private_shop_coupons')) {
				throw new WC_API_Exception('woocommerce_api_user_cannot_read_coupons_count',
				                           __('You do not have permission to read the coupons count', 'woocommerce'),
				                           401);
			}
			$query = $this->query_coupons($filter);

			return ['count' => (int)$query->found_posts];
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}

	/**
	 * Get the coupon for the given code
	 *
	 * @since 2.1
	 *
	 * @param string $code   the coupon code
	 * @param string $fields fields to include in response
	 *
	 * @return int|WP_Error
	 */
	public function get_coupon_by_code($code, $fields = NULL) {
		global $wpdb;
		try {
			$id
				= $wpdb->get_var($wpdb->prepare("SELECT id FROM $wpdb->posts WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1;",
				                                $code));
			if (is_null($id)) {
				throw new WC_API_Exception('woocommerce_api_invalid_coupon_code',
				                           __('Invalid coupon code', 'woocommerce'), 404);
			}

			return $this->get_coupon($id, $fields);
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}

	/**
	 * Delete a coupon
	 *
	 * @since  2.2
	 *
	 * @param int  $id    the coupon ID
	 * @param bool $force true to permanently delete coupon, false to move to trash
	 *
	 * @return array
	 */
	public function delete_coupon($id, $force = FALSE) {
		$id = $this->validate_request($id, 'shop_coupon', 'delete');
		if (is_wp_error($id)) {
			return $id;
		}
		do_action('woocommerce_api_delete_coupon', $id, $this);

		return $this->delete($id, 'shop_coupon', ('true' === $force));
	}

	/**
	 * Bulk update or insert coupons
	 * Accepts an array with coupons in the formats supported by
	 * WC_API_Coupons->create_coupon() and WC_API_Coupons->edit_coupon()
	 *
	 * @since 2.4.0
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function bulk($data) {
		try {
			if (!isset($data['coupons'])) {
				throw new WC_API_Exception('woocommerce_api_missing_coupons_data',
				                           sprintf(__('No %1$s data specified to create/edit %1$s', 'woocommerce'),
				                                   'coupons'), 400);
			}
			$data  = $data['coupons'];
			$limit = apply_filters('woocommerce_api_bulk_limit', 100, 'coupons');
			// Limit bulk operation
			if (count($data) > $limit) {
				throw new WC_API_Exception('woocommerce_api_coupons_request_entity_too_large',
				                           sprintf(__('Unable to accept more than %s items for this request',
				                                      'woocommerce'), $limit), 413);
			}
			$coupons = [];
			foreach ($data as $_coupon) {
				$coupon_id = 0;
				// Try to get the coupon ID
				if (isset($_coupon['id'])) {
					$coupon_id = intval($_coupon['id']);
				}
				// Coupon exists / edit coupon
				if ($coupon_id) {
					$edit = $this->edit_coupon($coupon_id, ['coupon' => $_coupon]);
					if (is_wp_error($edit)) {
						$coupons[] = [
							'id'    => $coupon_id,
							'error' => ['code' => $edit->get_error_code(), 'message' => $edit->get_error_message()]
						];
					} else {
						$coupons[] = $edit['coupon'];
					}
				} // Coupon don't exists / create coupon
				else {
					$new = $this->create_coupon(['coupon' => $_coupon]);
					if (is_wp_error($new)) {
						$coupons[] = [
							'id'    => $coupon_id,
							'error' => ['code' => $new->get_error_code(), 'message' => $new->get_error_message()]
						];
					} else {
						$coupons[] = $new['coupon'];
					}
				}
			}

			return ['coupons' => apply_filters('woocommerce_api_coupons_bulk_response', $coupons, $this)];
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}

	/**
	 * Edit a coupon
	 *
	 * @since 2.2
	 *
	 * @param int   $id the coupon ID
	 * @param array $data
	 *
	 * @return array
	 */
	public function edit_coupon($id, $data) {
		try {
			if (!isset($data['coupon'])) {
				throw new WC_API_Exception('woocommerce_api_missing_coupon_data',
				                           sprintf(__('No %1$s data specified to edit %1$s', 'woocommerce'), 'coupon'),
				                           400);
			}
			$data = $data['coupon'];
			$id = $this->validate_request($id, 'shop_coupon', 'edit');
			if (is_wp_error($id)) {
				return $id;
			}
			$data = apply_filters('woocommerce_api_edit_coupon_data', $data, $id, $this);
			if (isset($data['code'])) {
				global $wpdb;
				$coupon_code = apply_filters('woocommerce_coupon_code', $data['code']);
				// Check for duplicate coupon codes
				$coupon_found = $wpdb->get_var($wpdb->prepare("
					SELECT $wpdb->posts.ID
					FROM $wpdb->posts
					WHERE $wpdb->posts.post_type = 'shop_coupon'
					AND $wpdb->posts.post_status = 'publish'
					AND $wpdb->posts.post_title = '%s'
					AND $wpdb->posts.ID != %s
				 ", $coupon_code, $id));
				if ($coupon_found) {
					throw new WC_API_Exception('woocommerce_api_coupon_code_already_exists',
					                           __('The coupon code already exists', 'woocommerce'), 400);
				}
				$updated = wp_update_post(['ID' => intval($id), 'post_title' => $coupon_code]);
				if (0 === $updated) {
					throw new WC_API_Exception('woocommerce_api_cannot_update_coupon',
					                           __('Failed to update coupon', 'woocommerce'), 400);
				}
			}
			if (isset($data['description'])) {
				$updated = wp_update_post(['ID' => intval($id), 'post_excerpt' => $data['description']]);
				if (0 === $updated) {
					throw new WC_API_Exception('woocommerce_api_cannot_update_coupon',
					                           __('Failed to update coupon', 'woocommerce'), 400);
				}
			}
			if (isset($data['type'])) {
				// Validate coupon types
				if (!in_array(wc_clean($data['type']), array_keys(wc_get_coupon_types()))) {
					throw new WC_API_Exception('woocommerce_api_invalid_coupon_type',
					                           sprintf(__('Invalid coupon type - the coupon type must be any of these: %s',
					                                      'woocommerce'),
					                                   implode(', ', array_keys(wc_get_coupon_types()))), 400);
				}
				update_post_meta($id, 'discount_type', $data['type']);
			}
			if (isset($data['amount'])) {
				update_post_meta($id, 'coupon_amount', wc_format_decimal($data['amount']));
			}
			if (isset($data['individual_use'])) {
				update_post_meta($id, 'individual_use', (TRUE === $data['individual_use']) ? 'yes' : 'no');
			}
			if (isset($data['product_ids'])) {
				update_post_meta($id, 'product_ids',
				                 implode(',', array_filter(array_map('intval', $data['product_ids']))));
			}
			if (isset($data['exclude_product_ids'])) {
				update_post_meta($id, 'exclude_product_ids',
				                 implode(',', array_filter(array_map('intval', $data['exclude_product_ids']))));
			}
			if (isset($data['usage_limit'])) {
				update_post_meta($id, 'usage_limit', absint($data['usage_limit']));
			}
			if (isset($data['usage_limit_per_user'])) {
				update_post_meta($id, 'usage_limit_per_user', absint($data['usage_limit_per_user']));
			}
			if (isset($data['limit_usage_to_x_items'])) {
				update_post_meta($id, 'limit_usage_to_x_items', absint($data['limit_usage_to_x_items']));
			}
			if (isset($data['usage_count'])) {
				update_post_meta($id, 'usage_count', absint($data['usage_count']));
			}
			if (isset($data['expiry_date'])) {
				update_post_meta($id, 'expiry_date', $this->get_coupon_expiry_date(wc_clean($data['expiry_date'])));
			}
			if (isset($data['enable_free_shipping'])) {
				update_post_meta($id, 'free_shipping', (TRUE === $data['enable_free_shipping']) ? 'yes' : 'no');
			}
			if (isset($data['product_category_ids'])) {
				update_post_meta($id, 'product_categories',
				                 array_filter(array_map('intval', $data['product_category_ids'])));
			}
			if (isset($data['exclude_product_category_ids'])) {
				update_post_meta($id, 'exclude_product_categories',
				                 array_filter(array_map('intval', $data['exclude_product_category_ids'])));
			}
			if (isset($data['exclude_sale_items'])) {
				update_post_meta($id, 'exclude_sale_items', (TRUE === $data['exclude_sale_items']) ? 'yes' : 'no');
			}
			if (isset($data['minimum_amount'])) {
				update_post_meta($id, 'minimum_amount', wc_format_decimal($data['minimum_amount']));
			}
			if (isset($data['maximum_amount'])) {
				update_post_meta($id, 'maximum_amount', wc_format_decimal($data['maximum_amount']));
			}
			if (isset($data['customer_emails'])) {
				update_post_meta($id, 'customer_email',
				                 array_filter(array_map('sanitize_email', $data['customer_emails'])));
			}
			do_action('woocommerce_api_edit_coupon', $id, $data);

			return $this->get_coupon($id);
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}

	/**
	 * expiry_date format
	 *
	 * @since  2.3.0
	 *
	 * @param  string $expiry_date
	 *
	 * @return string
	 */
	protected function get_coupon_expiry_date($expiry_date) {
		if ('' != $expiry_date) {
			return date('Y-m-d', strtotime($expiry_date));
		}

		return '';
	}

	/**
	 * Create a coupon
	 *
	 * @since 2.2
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function create_coupon($data) {
		global $wpdb;
		try {
			if (!isset($data['coupon'])) {
				throw new WC_API_Exception('woocommerce_api_missing_coupon_data',
				                           sprintf(__('No %1$s data specified to create %1$s', 'woocommerce'),
				                                   'coupon'), 400);
			}
			$data = $data['coupon'];
			// Check user permission
			if (!current_user_can('publish_shop_coupons')) {
				throw new WC_API_Exception('woocommerce_api_user_cannot_create_coupon',
				                           __('You do not have permission to create coupons', 'woocommerce'), 401);
			}
			$data = apply_filters('woocommerce_api_create_coupon_data', $data, $this);
			// Check if coupon code is specified
			if (!isset($data['code'])) {
				throw new WC_API_Exception('woocommerce_api_missing_coupon_code',
				                           sprintf(__('Missing parameter %s', 'woocommerce'), 'code'), 400);
			}
			$coupon_code = apply_filters('woocommerce_coupon_code', $data['code']);
			// Check for duplicate coupon codes
			$coupon_found = $wpdb->get_var($wpdb->prepare("
				SELECT $wpdb->posts.ID
				FROM $wpdb->posts
				WHERE $wpdb->posts.post_type = 'shop_coupon'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_title = '%s'
			 ", $coupon_code));
			if ($coupon_found) {
				throw new WC_API_Exception('woocommerce_api_coupon_code_already_exists',
				                           __('The coupon code already exists', 'woocommerce'), 400);
			}
			$defaults = [
				'type'                         => 'fixed_cart',
				'amount'                       => 0,
				'individual_use'               => FALSE,
				'product_ids'                  => [],
				'exclude_product_ids'          => [],
				'usage_limit'                  => '',
				'usage_limit_per_user'         => '',
				'limit_usage_to_x_items'       => '',
				'usage_count'                  => '',
				'expiry_date'                  => '',
				'enable_free_shipping'         => FALSE,
				'product_category_ids'         => [],
				'exclude_product_category_ids' => [],
				'exclude_sale_items'           => FALSE,
				'minimum_amount'               => '',
				'maximum_amount'               => '',
				'customer_emails'              => [],
				'description'                  => ''
			];
			$coupon_data = wp_parse_args($data, $defaults);
			// Validate coupon types
			if (!in_array(wc_clean($coupon_data['type']), array_keys(wc_get_coupon_types()))) {
				throw new WC_API_Exception('woocommerce_api_invalid_coupon_type',
				                           sprintf(__('Invalid coupon type - the coupon type must be any of these: %s',
				                                      'woocommerce'), implode(', ', array_keys(wc_get_coupon_types()))),
				                           400);
			}
			$new_coupon = [
				'post_title'   => $coupon_code,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_author'  => get_current_user_id(),
				'post_type'    => 'shop_coupon',
				'post_excerpt' => $coupon_data['description']
			];
			$id = wp_insert_post($new_coupon, TRUE);
			if (is_wp_error($id)) {
				throw new WC_API_Exception('woocommerce_api_cannot_create_coupon', $id->get_error_message(), 400);
			}
			// Set coupon meta
			update_post_meta($id, 'discount_type', $coupon_data['type']);
			update_post_meta($id, 'coupon_amount', wc_format_decimal($coupon_data['amount']));
			update_post_meta($id, 'individual_use', (TRUE === $coupon_data['individual_use']) ? 'yes' : 'no');
			update_post_meta($id, 'product_ids',
			                 implode(',', array_filter(array_map('intval', $coupon_data['product_ids']))));
			update_post_meta($id, 'exclude_product_ids',
			                 implode(',', array_filter(array_map('intval', $coupon_data['exclude_product_ids']))));
			update_post_meta($id, 'usage_limit', absint($coupon_data['usage_limit']));
			update_post_meta($id, 'usage_limit_per_user', absint($coupon_data['usage_limit_per_user']));
			update_post_meta($id, 'limit_usage_to_x_items', absint($coupon_data['limit_usage_to_x_items']));
			update_post_meta($id, 'usage_count', absint($coupon_data['usage_count']));
			update_post_meta($id, 'expiry_date', $this->get_coupon_expiry_date(wc_clean($coupon_data['expiry_date'])));
			update_post_meta($id, 'free_shipping', (TRUE === $coupon_data['enable_free_shipping']) ? 'yes' : 'no');
			update_post_meta($id, 'product_categories',
			                 array_filter(array_map('intval', $coupon_data['product_category_ids'])));
			update_post_meta($id, 'exclude_product_categories',
			                 array_filter(array_map('intval', $coupon_data['exclude_product_category_ids'])));
			update_post_meta($id, 'exclude_sale_items', (TRUE === $coupon_data['exclude_sale_items']) ? 'yes' : 'no');
			update_post_meta($id, 'minimum_amount', wc_format_decimal($coupon_data['minimum_amount']));
			update_post_meta($id, 'maximum_amount', wc_format_decimal($coupon_data['maximum_amount']));
			update_post_meta($id, 'customer_email',
			                 array_filter(array_map('sanitize_email', $coupon_data['customer_emails'])));
			do_action('woocommerce_api_create_coupon', $id, $data);
			$this->server->send_status(201);

			return $this->get_coupon($id);
		} catch (WC_API_Exception $e) {
			return new WP_Error($e->getErrorCode(), $e->getMessage(), ['status' => $e->getCode()]);
		}
	}
}