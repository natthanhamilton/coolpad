<?php

class WPCF7_Pipe {
	public $before = '';
	public $after  = '';

	public function __construct($text) {
		$text = (string)$text;
		$pipe_pos = strpos($text, '|');
		if (FALSE === $pipe_pos) {
			$this->before = $this->after = trim($text);
		} else {
			$this->before = trim(substr($text, 0, $pipe_pos));
			$this->after  = trim(substr($text, $pipe_pos + 1));
		}
	}
}

class WPCF7_Pipes {
	private $pipes = [];

	public function __construct(array $texts) {
		foreach ($texts as $text) {
			$this->add_pipe($text);
		}
	}

	private function add_pipe($text) {
		$pipe          = new WPCF7_Pipe($text);
		$this->pipes[] = $pipe;
	}

	public function do_pipe($before) {
		foreach ($this->pipes as $pipe) {
			if ($pipe->before == $before) {
				return $pipe->after;
			}
		}

		return $before;
	}

	public function collect_befores() {
		$befores = [];
		foreach ($this->pipes as $pipe) {
			$befores[] = $pipe->before;
		}

		return $befores;
	}

	public function collect_afters() {
		$afters = [];
		foreach ($this->pipes as $pipe) {
			$afters[] = $pipe->after;
		}

		return $afters;
	}

	public function random_pipe() {
		if ($this->zero()) {
			return NULL;
		}

		return $this->pipes[ array_rand($this->pipes) ];
	}

	public function zero() {
		return empty($this->pipes);
	}
}

?>