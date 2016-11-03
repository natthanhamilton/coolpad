<?php

/**
 * This class takes care of all form related functionality
 *
 * Do not interact with this class directly, use `mc4wp_form` functions tagged with @access public instead.
 *
 * @class  MC4WP_Form_Manager
 * @ignore
 * @access private
 */
class MC4WP_Form_Manager {
	/**
	 * @var MC4WP_Form_Output_Manager
	 */
	protected $output_manager;
	/**
	 * @var MC4WP_Form_Listener
	 */
	protected $listener;
	/**
	 * @var MC4WP_Form_Tags
	 */
	protected $tags;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->output_manager = new MC4WP_Form_Output_Manager();
		$this->tags           = new MC4WP_Form_Tags();
	}

	/**
	 * Hook!
	 */
	public function add_hooks() {
		add_action('init', [$this, 'initialize']);
		// forms
		add_action('wp_enqueue_scripts', [$this, 'init_asset_manager'], 1);
		add_action('template_redirect', ['MC4WP_Form_Previewer', 'init']);
		// widget
		add_action('widgets_init', [$this, 'register_widget']);
		$this->output_manager->add_hooks();
		$this->tags->add_hooks();
	}

	/**
	 * Initialize
	 */
	public function initialize() {
		$this->register_post_type();
		$this->init_form_listener();
	}

	/**
	 * Register post type "mc4wp-form"
	 */
	public function register_post_type() {
		// register post type
		register_post_type('mc4wp-form', [
			                               'labels' => [
				                               'name'          => 'MailChimp Sign-up Forms',
				                               'singular_name' => 'Sign-up Form',
				                               'add_new_item'  => 'Add New Form',
				                               'edit_item'     => 'Edit Form',
				                               'new_item'      => 'New Form',
				                               'all_items'     => 'All Forms',
				                               'view_item'     => NULL
			                               ],
			                               'public' => FALSE
		                               ]
		);
	}

	/**
	 * Initialise the form listener
	 *
	 * @hooked `init`
	 */
	public function init_form_listener() {
		$request        = $this->get_request();
		$this->listener = new MC4WP_Form_Listener();
		$this->listener->listen($request);
	}

	/**
	 * @return MC4WP_Request
	 */
	private function get_request() {
		return mc4wp('request');
	}

	/**
	 * Initialise asset manager
	 *
	 * @hooked `template_redirect`
	 */
	public function init_asset_manager() {
		$assets = new MC4WP_Form_Asset_Manager();
		$assets->hook();
	}

	/**
	 * Register our Form widget
	 */
	public function register_widget() {
		register_widget('MC4WP_Form_Widget');
	}

	/**
	 * @param       $form_id
	 * @param array $config
	 * @param bool  $echo
	 *
	 * @return string
	 */
	public function output_form($form_id, $config = [], $echo = TRUE) {
		return $this->output_manager->output_form($form_id, $config, $echo);
	}

	/**
	 * Gets the currently submitted form
	 *
	 * @return MC4WP_Form|null
	 */
	public function get_submitted_form() {
		if ($this->listener->submitted_form instanceof MC4WP_Form) {
			return $this->listener->submitted_form;
		}

		return NULL;
	}

	/**
	 * Return all tags
	 *
	 * @return array
	 */
	public function get_tags() {
		return $this->tags->get();
	}
}
