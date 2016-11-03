<?php

/**
 * Class MC4WP_Queue
 *
 * @ignore
 */
class MC4WP_Queue {
	/**
	 * @var MC4WP_Queue_Job[]
	 */
	protected $jobs;
	/**
	 * @var string
	 */
	protected $option_name;
	/**
	 * @var bool
	 */
	protected $dirty = FALSE;

	/**
	 * MC4WP_Ecommerce_Queue constructor.
	 *
	 * @param string $option_name
	 */
	public function __construct($option_name) {
		$this->option_name = $option_name;
		register_shutdown_function([$this, 'save']);
	}

	/**
	 * Get all jobs in the queue
	 *
	 * @return MC4WP_Queue_Job[] Array of jobs
	 */
	public function all() {
		if (is_null($this->jobs)) {
			$this->load();
		}

		return $this->jobs;
	}

	/**
	 * Load jobs from option
	 */
	protected function load() {
		$jobs = get_option($this->option_name, []);
		if (!is_array($jobs)) {
			$jobs = [];
		}
		$this->jobs = $jobs;
	}

	/**
	 * Add job to queue
	 *
	 * @param mixed $data
	 */
	public function put($data) {
		if (is_null($this->jobs)) {
			$this->load();
		}
		$job          = new MC4WP_Queue_Job($data);
		$this->jobs[] = $job;
		$this->dirty  = TRUE;
	}

	/**
	 * Get all jobs in the queue
	 *
	 * @return MC4WP_Queue_Job|false
	 */
	public function get() {
		if (is_null($this->jobs)) {
			$this->load();
		}
		// do we have jobs?
		if (count($this->jobs) === 0) {
			return FALSE;
		}

		// return first element
		return reset($this->jobs);
	}

	/**
	 * @param MC4WP_Queue_Job $job
	 */
	public function delete(MC4WP_Queue_Job $job) {
		if (is_null($this->jobs)) {
			$this->load();
		}
		$index = array_search($job, $this->jobs, TRUE);
		// check for "false" here, as 0 is a valid index.
		if ($index !== FALSE) {
			unset($this->jobs[ $index ]);
			$this->jobs  = array_values($this->jobs);
			$this->dirty = TRUE;
		}
	}

	/**
	 * Reset queue
	 */
	public function reset() {
		$this->jobs  = [];
		$this->dirty = TRUE;
	}

	/**
	 * Save the queue
	 */
	public function save() {
		if (!$this->dirty || is_null($this->jobs)) {
			return FALSE;
		}
		$success = update_option($this->option_name, $this->jobs, FALSE);
		if ($success) {
			$this->dirty = FALSE;
		}

		return $success;
	}
}