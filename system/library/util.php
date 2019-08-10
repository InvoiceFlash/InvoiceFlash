<?php
class Util {
	protected $registry;	
	private static $instance;

	public function __construct($registry) {
		$this->template = $registry->get('template');
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
	}
	
	public static function get_instance($registry) {
		if (is_null(static::$instance)) {
			static::$instance = new static($registry);
		}
		
		return static::$instance;
	}
	
	public function checkTableExists($table) {
		$res = $this->db->query("SELECT table_name FROM information_schema.tables WHERE table_schema = '" . DB_DATABASE . "' AND table_name = '" . DB_PREFIX . "$table'");

		if ($res->rows) {
			return true;
		} else {
			return false;
		}
	}

}
?>