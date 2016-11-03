<?php

/**
 * Class MC4WP_Admin_Messages
 *
 * @ignore
 * @since 3.0
 */
class MC4WP_Admin_Messages {
	/**
	 * @var array
	 */
	protected $bag;
	/**
	 * @var bool
	 */
	protected $dirty = FALSE;

	/**
	 * Add hooks
	 */
	public function add_hooks() {
		add_action('admin_notices', [$this, 'show']);
		register_shutdown_function([$this, 'save']);
	}

	/**
	 * Flash a message (shows on next pageload)
	 *
	 * @param        $message
	 * @param string $type
	 */
	public function flash($message, $type = 'success') {
		$this->load();
		$this->bag[] = [
			'text' => $message,
			'type' => $type
		];
		$this->dirty = TRUE;
	}

	// empty flash bag
	private function load() {
		if (is_null($this->bag)) {
			$this->bag = get_option('mc4wp_flash_messages', []);
		}
	}

	/**
	 * Show queued flash messages
	 */
	public function show() {
		$this->load();
		foreach ($this->bag as $message) {
			echo sprintf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', $message['type'],
			             $message['text']);
		}
		$this->reset();
	}

	private function reset() {
		$this->bag   = [];
		$this->dirty = TRUE;
	}

	/**
	 * Save queued messages
	 *
	 * @hooked `shutdown`
	 */
	public function save() {
		if ($this->dirty) {
			update_option('mc4wp_flash_messages', $this->bag, FALSE);
		}
	}
}