<?php

/**
 * Class MC4WP_Form_Tags
 *
 * @access private
 * @ignore
 */
class MC4WP_Form_Tags {
	/**
	 * @var MC4WP_Dynamic_Content_Tags
	 */
	protected $tags;
	/**
	 * @var MC4WP_Form
	 */
	protected $form;
	/**
	 * @var MC4WP_Form_Element
	 */
	protected $form_element;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->tags = new MC4WP_Dynamic_Content_Tags('form');
	}

	public function add_hooks() {
		add_filter('mc4wp_dynamic_content_tags_form', [$this, 'register']);
		add_filter('mc4wp_form_response_html', [$this, 'replace'], 10, 2);
		add_filter('mc4wp_form_content', [$this, 'replace'], 10, 3);
		add_filter('mc4wp_form_redirect_url', [$this, 'replace_in_url'], 10, 2);
	}

	/**
	 * @return array
	 */
	public function get() {
		return $this->tags->all();
	}

	/**
	 * @param array $tags
	 *
	 * @return array
	 */
	public function register(array $tags) {
		/**
		 * @var MC4WP_Request
		 */
		$request = mc4wp('request');
		$tags['response'] = [
			'description' => __('Replaced with the form response (error or success messages).', 'mailchimp-for-wp'),
			'callback'    => [$this, 'get_form_response']
		];
		$tags['data'] = [
			'description' => sprintf(__("Data from the URL or a submitted form.", 'mailchimp-for-wp')),
			'callback'    => [$this, 'get_data'],
			'example'     => "data key='UTM_SOURCE' default='Default Source'"
		];
		$tags['subscriber_count'] = [
			'description' => __('Replaced with the number of subscribers on the selected list(s)', 'mailchimp-for-wp'),
			'callback'    => [$this, 'get_subscriber_count']
		];
		$tags['email'] = [
			'description' => __('The email address of the current visitor (if known).', 'mailchimp-for-wp'),
			'callback'    => [$this, 'get_email'],
		];
		$tags['current_url'] = [
			'description' => __('The URL of the page.', 'mailchimp-for-wp'),
			'callback'    => 'mc4wp_get_current_url',
		];
		$tags['current_path'] = [
			'description' => __('The path of the page.', 'mailchimp-for-wp'),
			'callback'    => [$request, 'get_url'],
		];
		$tags['date'] = [
			'description' => sprintf(__('The current date. Example: %s.', 'mailchimp-for-wp'),
			                         '<strong>' . date('Y/m/d') . '</strong>'),
			'replacement' => date('Y/m/d')
		];
		$tags['time'] = [
			'description' => sprintf(__('The current time. Example: %s.', 'mailchimp-for-wp'),
			                         '<strong>' . date('H:i:s') . '</strong>'),
			'replacement' => date('H:i:s')
		];
		$tags['language'] = [
			'description' => sprintf(__('The site\'s language. Example: %s.', 'mailchimp-for-wp'),
			                         '<strong>' . get_locale() . '</strong>'),
			'callback'    => 'get_locale',
		];
		$tags['ip'] = [
			'description' => sprintf(__('The visitor\'s IP address. Example: %s.', 'mailchimp-for-wp'),
			                         '<strong>' . mc4wp('request')->get_client_ip() . '</strong>'),
			'callback'    => [$request, 'get_client_ip']
		];
		$tags['user'] = [
			'description' => sprintf(__("The property of the currently logged-in user.", 'mailchimp-for-wp')),
			'callback'    => [$this, 'get_user_property'],
			'example'     => "user property='user_email'"
		];
		$tags['post'] = [
			'description' => sprintf(__("Property of the current page or post.", 'mailchimp-for-wp')),
			'callback'    => [$this, 'get_post_property'],
			'example'     => "post property='ID'"
		];

		return $tags;
	}

	/**
	 * Replaces the registered tags in the given string
	 *
	 * @hooked `mc4wp_form_message_html`
	 * @hooked `mc4wp_form_content`
	 *
	 * @param string             $string
	 * @param MC4WP_Form         $form
	 * @param MC4WP_Form_Element $element
	 *
	 * @return string
	 */
	public function replace($string, MC4WP_Form $form, MC4WP_Form_Element $element = NULL) {
		$this->form         = $form;
		$this->form_element = $element;
		$string             = $this->tags->replace($string);

		return $string;
	}

	/**
	 * @hooked `mc4wp_form_redirect_url`
	 *
	 * @param            $string
	 * @param MC4WP_Form $form
	 *
	 * @return string
	 */
	public function replace_in_url($string, MC4WP_Form $form) {
		$this->form = $form;
		$string     = $this->tags->replace_in_url($string);

		return $string;
	}

	/**
	 * Returns the number of subscribers on the selected lists (for the form context)
	 *
	 * @return int
	 */
	public function get_subscriber_count() {
		$mailchimp = new MC4WP_MailChimp();
		$count     = $mailchimp->get_subscriber_count($this->form->get_lists());

		return number_format($count);
	}

	/**
	 * Returns the form response
	 *
	 * @return string
	 */
	public function get_form_response() {
		if ($this->form_element instanceof MC4WP_Form_Element) {
			return $this->form_element->get_response_html();
		}

		return '';
	}

	/**
	 *
	 * @param $args
	 *
	 * @return string
	 */
	public function get_data($args = []) {
		if (empty($args['key'])) {
			return '';
		}
		$default = isset($args['default']) ? $args['default'] : '';
		/**
		 * @var MC4WP_Request $request
		 */
		$request = mc4wp('request');

		return esc_html($request->params->get($args['key'], $default));
	}

	/*
	 * Get property of currently logged-in user
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_user_property($args = []) {
		$property = empty($args['property']) ? 'user_email' : $args['property'];
		$user     = wp_get_current_user();
		if ($user instanceof WP_User) {
			return $user->{$property};
		}

		return '';
	}

	/*
	 * Get property of viewed post
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function get_post_property($args = []) {
		$property = empty($args['property']) ? 'ID' : $args['property'];
		global $post;
		if ($post instanceof WP_Post) {
			return $post->{$property};
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function get_email() {
		// first, try request
		$request = mc4wp('request');
		$email   = $request->params->get('EMAIL', '');
		if ($email) {
			return $email;
		}
		// then , try logged-in user
		if (is_user_logged_in()) {
			$user = wp_get_current_user();

			return $user->user_email;
		}

		// TODO: Read from cookie? Or add $_COOKIE support to {data} tag?
		return '';
	}
}