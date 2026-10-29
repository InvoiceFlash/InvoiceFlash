<?php
class Config {
	private $data = array();

	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	public function has($key) {
		return isset($this->data[$key]);
	}

	// Modelos de Claude que se pueden elegir en Ajustes > IA (id => nombre).
	public function claudeModels() {
		return array(
			'claude-opus-5-5'  => 'Claude Opus 5.5',
			'claude-haiku-5-5' => 'Claude Haiku 5.5'
		);
	}

	// Modelos de Ollama que se pueden elegir en Ajustes > IA (id => nombre).
	public function ollamaModels() {
		return array(
			'qwen3:1.7b' => 'Qwen3 1.7B',
			'qwen3:4b'   => 'Qwen3 4B'
		);
	}

	// Modelo de Ollama a usar (setting config_ollama_model); qwen3:1.7b si no hay uno valido guardado.
	public function getOllamaModel() {
		$model  = $this->get('config_ollama_model');
		$models = $this->ollamaModels();

		return (is_string($model) && isset($models[$model])) ? $model : 'qwen3:1.7b';
	}

	// Modelo de Claude a usar en todas las llamadas (setting config_claude_model); Opus 5.5 si no hay uno valido guardado.
	public function getClaudeModel() {
		$model  = $this->get('config_claude_model');
		$models = $this->claudeModels();

		return (is_string($model) && isset($models[$model])) ? $model : 'claude-opus-5-5';
	}

	public function load($filename) {
		$file = DIR_CONFIG . $filename . '.php';

		if (file_exists($file)) {
			$_ = array();

			require($file);

			$this->data = array_merge($this->data, $_);
		} else {
			trigger_error('Error: Could not load config ' . $filename . '!');
			exit();
		}
	}
}
?>