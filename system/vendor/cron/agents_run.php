<?php
// Cron "agents": lanza los agentes de IA (catalog/agent) que les toque: los de hora fija una vez al dia a partir de su hora
// y los "siempre en ejecucion" en cada pasada (el ciclo de esta tarea es de 1 minuto).
// Autocontenido: se invoca desde cron_actions.php (sin Config cargado) y desde ControllerSettingCron::runcron().

require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../../../admin/config.php');

if (!function_exists('agents_run')) {
	class AgentsCronUser {
		public function getId() {
			return 0;
		}

		public function getUserName() {
			return 'agent';
		}

		public function hasPermission($key, $value) {
			return true;
		}
	}

	function agents_run() {
		set_time_limit(0);

		$registry = new Registry();

		$config = new Config();
		$db     = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? DB_PORT : 3306);

		$registry->set('config', $config);
		$registry->set('db', $db);
		$registry->set('load', new Loader($registry));
		$registry->set('user', new AgentsCronUser());

		foreach ($db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'")->rows as $setting) {
			$config->set($setting['key'], $setting['serialized'] ? unserialize($setting['value']) : $setting['value']);
		}

		$registry->get('load')->model('catalog/agent');

		$log = new Log('agents.log');

		foreach ($registry->get('model_catalog_agent')->runDueAgents() as $agent_id => $result) {
			if ($result['status'] != 'ok') {
				$log->write('Agente ' . $agent_id . ': ' . $result['error']);
			}
		}
	}
}

agents_run();
