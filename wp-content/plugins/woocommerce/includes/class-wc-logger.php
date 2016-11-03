<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Allows log files to be written to for debugging purposes
 *
 * @class          WC_Logger
 * @version        1.6.4
 * @package        WooCommerce/Classes
 * @category       Class
 * @author         WooThemes
 */
class WC_Logger {
	/**
	 * Stores open file _handles.
	 *
	 * @var array
	 * @access private
	 */
	private $_handles;

	/**
	 * Constructor for the logger.
	 */
	public function __construct() {
		$this->_handles = [];
	}

	/**
	 * Destructor.
	 */
	public function __destruct() {
		foreach ($this->_handles as $handle) {
			if (is_resource($handle)) {
				fclose($handle);
			}
		}
	}

	/**
	 * Add a log entry to chosen file.
	 *
	 * @param string $handle
	 * @param string $message
	 *
	 * @return bool
	 */
	public function add($handle, $message) {
		$result = FALSE;
		if ($this->open($handle) && is_resource($this->_handles[ $handle ])) {
			$time   = date_i18n('m-d-Y @ H:i:s -'); // Grab Time
			$result = fwrite($this->_handles[ $handle ], $time . " " . $message . "\n");
		}
		do_action('woocommerce_log_add', $handle, $message);

		return FALSE !== $result;
	}

	/**
	 * Open log file for writing.
	 *
	 * @param string $handle
	 * @param string $mode
	 *
	 * @return bool success
	 */
	protected function open($handle, $mode = 'a') {
		if (isset($this->_handles[ $handle ])) {
			return TRUE;
		}
		if ($this->_handles[ $handle ] = @fopen(wc_get_log_file_path($handle), $mode)) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Clear entries from chosen file.
	 *
	 * @param string $handle
	 *
	 * @return bool
	 */
	public function clear($handle) {
		$result = FALSE;
		// Close the file if it's already open.
		$this->close($handle);
		/**
		 * $this->open( $handle, 'w' ) == Open the file for writing only. Place the file pointer at the beginning of the file,
		 * and truncate the file to zero length.
		 */
		if ($this->open($handle, 'w') && is_resource($this->_handles[ $handle ])) {
			$result = TRUE;
		}
		do_action('woocommerce_log_clear', $handle);

		return $result;
	}

	/**
	 * Close a handle.
	 *
	 * @param string $handle
	 *
	 * @return bool success
	 */
	protected function close($handle) {
		$result = FALSE;
		if (is_resource($this->_handles[ $handle ])) {
			$result = fclose($this->_handles[ $handle ]);
			unset($this->_handles[ $handle ]);
		}

		return $result;
	}
}
