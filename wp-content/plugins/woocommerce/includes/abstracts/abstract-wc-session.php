<?php

/**
 * Handle data for the current customers session
 *
 * @class       WC_Session
 * @version     2.0.0
 * @package     WooCommerce/Abstracts
 * @category    Abstract Class
 * @author      WooThemes
 */
abstract class WC_Session {
	/** @var int $_customer_id */
	protected $_customer_id;
	/** @var array $_data */
	protected $_data = [];
	/** @var bool $_dirty When something changes */
	protected $_dirty = FALSE;

	/**
	 * __get function.
	 *
	 * @param mixed $key
	 *
	 * @return mixed
	 */
	public function __get($key) {
		return $this->get($key);
	}

	/**
	 * __set function.
	 *
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function __set($key, $value) {
		$this->set($key, $value);
	}

	/**
	 * Get a session variable.
	 *
	 * @param string $key
	 * @param  mixed $default used if the session variable isn't set
	 *
	 * @return array|string value of session variable
	 */
	public function get($key, $default = NULL) {
		$key = sanitize_key($key);

		return isset($this->_data[ $key ]) ? maybe_unserialize($this->_data[ $key ]) : $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $key
	 * @param mixed  $value
	 */
	public function set($key, $value) {
		if ($value !== $this->get($key)) {
			$this->_data[ sanitize_key($key) ] = maybe_serialize($value);
			$this->_dirty                      = TRUE;
		}
	}

	/**
	 * __isset function.
	 *
	 * @param mixed $key
	 *
	 * @return bool
	 */
	public function __isset($key) {
		return isset($this->_data[ sanitize_title($key) ]);
	}

	/**
	 * __unset function.
	 *
	 * @param mixed $key
	 */
	public function __unset($key) {
		if (isset($this->_data[ $key ])) {
			unset($this->_data[ $key ]);
			$this->_dirty = TRUE;
		}
	}

	/**
	 * get_customer_id function.
	 *
	 * @access public
	 * @return int
	 */
	public function get_customer_id() {
		return $this->_customer_id;
	}
}
