<?php

/**
 * WP eCommerce Integration.
 *
 * This class will, if WP eCommerce is enabled, synchronize the WP eCommerce products
 * with the product taxonomy of Awesome Support and make the management
 * of products completely transparent.
 *
 * @package   Awesome Support/Integrations/eCommerce
 * @author    Julien Liabeuf <julien@liabeuf.fr>
 * @license   GPL-2.0+
 * @link      http://themeavenue.net
 * @copyright 2014 ThemeAvenue
 *
 */
final class WPAS_eCommerce_Integration {
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = NULL;
	/**
	 * Slug of the plugin products are synced with
	 *
	 * @var string
	 */
	public $plugin;
	/**
	 * Message to display when a taxonomy is locked
	 *
	 * @since 3.3
	 * @var string
	 */
	public $locked_msg = '';
	/**
	 * Whether or not the synchronization is enabled for this e-commerce plugin
	 *
	 * @since 3.3
	 * @var bool
	 */
	public $synced = FALSE;
	/**
	 * The list of plugins that we integrate with
	 *
	 * @since 3.3
	 * @var array
	 */
	protected $plugins = [];

	protected function __construct() {
		// Set our default integrations
		$this->plugins = [
			'edd'         => [
				'file'            => 'easy-digital-downloads.php',
				'class'           => 'Easy_Digital_Downloads',
				'post_type'       => 'download',
				'append'          => TRUE,
				'locked_taxo_msg' => sprintf(__('You cannot edit this term from here because it is linked to an EDD product. <a href="%s">Please edit the product directly</a>.',
				                                'awesome-support'),
				                             add_query_arg('post_type', 'download', admin_url('edit.php'))),
			],
			'woocommerce' => [
				'file'            => 'woocommerce.php',
				'class'           => 'WC_Integration',
				'post_type'       => 'product',
				'append'          => TRUE,
				'locked_taxo_msg' => sprintf(__('You cannot edit this term from here because it is linked to a WooCommerce product. <a href="%s">Please edit the product directly</a>.',
				                                'awesome-support'),
				                             add_query_arg('post_type', 'product', admin_url('edit.php'))),
			],
			'exchange'    => [
				'file'            => 'init.php',
				'class'           => 'IT_Exchange',
				'post_type'       => 'it_exchange_prod',
				'append'          => TRUE,
				'locked_taxo_msg' => sprintf(__('You cannot edit this term from here because it is linked to an Exchange product. <a href="%s">Please edit the product directly</a>.',
				                                'awesome-support'),
				                             add_query_arg('post_type', 'it_exchange_prod', admin_url('edit.php'))),
			],
			'jigoshop'    => [
				'file'            => 'jigoshop.php',
				'class'           => 'Jigoshop_Base',
				'post_type'       => 'product',
				'append'          => TRUE,
				'locked_taxo_msg' => sprintf(__('You cannot edit this term from here because it is linked to a Jigoshop product. <a href="%s">Please edit the product directly</a>.',
				                                'awesome-support'),
				                             add_query_arg('post_type', 'product', admin_url('edit.php'))),
			],
			'wpecommerce' => [
				'file'            => 'wp-shopping-cart.php',
				'class'           => 'WP_eCommerce',
				'post_type'       => 'wpsc-product',
				'append'          => TRUE,
				'locked_taxo_msg' => sprintf(__('You cannot edit this term from here because it is linked to a WP eCommerce product. <a href="%s">Please edit the product directly</a>.',
				                                'awesome-support'),
				                             add_query_arg('post_type', 'wpsc-product', admin_url('edit.php'))),
			],
		];
		$this->init();
	}

	/**
	 * Instantiate the integration process
	 *
	 * @since 3.3
	 * @return void
	 */
	protected function init() {
		$sync = apply_filters('wpas_ecommerce_integrations', TRUE);
		// Check if e-commerce products sync is enabled
		if (TRUE === $sync) {
			add_action('plugins_loaded', [$this, 'find_plugin']);
			add_action('init', [$this, 'init_sync'], 11);
			add_filter('wpas_taxonomy_locked_msg', [$this, 'locked_message']);
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     3.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if (NULL == self::$instance) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Find if there is an e-commerce plugin that we integrate with
	 *
	 * Because we only integrate with one plugin at the time, we stop searching after finding the first compatible
	 * plugin.
	 *
	 * @since 3.3
	 * @return void
	 */
	public function find_plugin() {
		foreach ($this->plugins as $slug => $plugin) {
			if (empty($plugin['class'])) {
				continue;
			}
			if (!class_exists($plugin['class'])) {
				continue;
			}
			$this->register($slug, $plugin);
			// We only want one integration
			break;
		}
	}

	/**
	 * Register the plugin found
	 *
	 * @since 3.3
	 *
	 * @param string $slug   E-commerce plugin slug
	 * @param array  $plugin E-commerce plugin integration settings
	 *
	 * @return void
	 */
	protected function register($slug, $plugin) {
		$this->plugin = $slug;
		$current      = (bool)wpas_get_option('support_products_' . $slug, TRUE);
		$plugin       = wp_parse_args($plugin, $this->integration_defaults());
		// Check if the plugin has sync enabled
		if (TRUE === $current) {
			$this->synced     = TRUE;
			$this->locked_msg = wp_kses_post($plugin['locked_taxo_msg']);
		}
	}

	/**
	 * Get the integration default settings
	 *
	 * @since 3.3
	 * @return array
	 */
	protected function integration_defaults() {
		$defaults = [
			'file'            => '',
			'class'           => '',
			'locked_taxo_msg' => '',
			'post_type'       => '',
			'taxonomy'        => 'product',
			'append'          => FALSE
		];

		return $defaults;
	}

	/**
	 * Maybe initiate the product sync class
	 *
	 * @since 3.3
	 * @return bool
	 */
	public function init_sync() {
		if (is_null($this->plugin) || !isset($this->plugins[ $this->plugin ]) || FALSE === $this->synced) {
			return FALSE;
		}
		$plugin = wp_parse_args($this->plugins[ $this->plugin ], $this->integration_defaults());
		// Instantiate the product sync class
		WPAS()->products_sync = new WPAS_Product_Sync($plugin['post_type'], $plugin['taxonomy'], $plugin['append']);

		return TRUE;
	}

	/**
	 * Register a new e-commerce integration
	 *
	 * @since 3.3
	 *
	 * @param array $plugin Plugin data
	 *
	 * @return void
	 */
	public function add_plugin($plugin) {
		if (isset($plugin['slug']) && isset($plugin['file']) && isset($plugin['post_type'])) {
			$this->plugins['slug'] = $plugin;
		}
	}

	/**
	 * Remove an existing integration
	 *
	 * @since 3.3
	 *
	 * @param string $slug Slug of the e-commerce plugin to remove
	 *
	 * @return void
	 */
	public function remove_plugin($slug) {
		if (array_key_exists($slug, $this->plugins)) {
			unset($this->plugins[ $slug ]);
		}
	}

	/**
	 * Get the list of registered e-commerce plugins
	 *
	 * @since 3.3
	 * @return array
	 */
	public function get_plugins() {
		return $this->plugins;
	}

	/**
	 * Change the default message displayed when trying to edit a locked product
	 *
	 * @since 3.3
	 *
	 * @param string $message Original message
	 *
	 * @return string
	 */
	public function locked_message($message) {
		if (empty($this->locked_msg)) {
			return $message;
		}

		return $this->locked_msg;
	}
}

/**
 * Instantiate the e-commerce integration class
 *
 * @since 3.3
 */
WPAS_eCommerce_Integration::get_instance();