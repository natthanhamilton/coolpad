<?php
defined('ABSPATH') or exit;

/**
 * Class MC4WP_Custom_Integration
 *
 * @ignore
 */
class MC4WP_Custom_Integration extends MC4WP_Integration {
	/**
	 * @var string
	 */
	public $name = "Custom";
	/**
	 * @var string
	 */
	public $description = "Integrate with custom third-party forms.";
	/**
	 * @var string
	 */
	protected $checkbox_name = 'mc4wp-subscribe';

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action('init', [$this, 'listen'], 90);
	}

	/**
	 * @return bool
	 */
	public function is_installed() {
		return TRUE;
	}

	/**
	 * @return array
	 */
	public function get_ui_elements() {
		return ['lists', 'double_optin', 'update_existing', 'send_welcome', 'replace_interests'];
	}

	/**
	 * Maybe fire a general subscription request
	 */
	public function listen() {
		if (!$this->checkbox_was_checked()) {
			return FALSE;
		}
		$data = $this->get_data();
		// don't run for CF7 or Events Manager requests
		// (since they use the same "mc4wp-subscribe" trigger)
		$disable_triggers = [
			'_wpcf7' => '',
			'action' => 'booking_add'
		];
		foreach ($disable_triggers as $trigger => $trigger_value) {
			if (isset($data[ $trigger ])) {
				$value = $data[ $trigger ];
				// do nothing if trigger value is optional
				// or if trigger value matches
				if (empty($trigger_value) || $value === $trigger_value) {
					return FALSE;
				}
			}
		}

		// run!
		return $this->process();
	}

	/**
	 * Process custom form
	 *
	 * @return bool|string
	 */
	public function process() {
		$parser = new MC4WP_Field_Guesser($this->get_data());
		$data   = $parser->combine(['guessed', 'namespaced']);
		// do nothing if no email was found
		if (empty($data['EMAIL'])) {
			return FALSE;
		}

		return $this->subscribe($data['EMAIL'], $data);
	}
}