<?php
// Cron "user_mail_download": descarga por POP el correo de cada usuario con cuenta propia (ficha de usuario)
// y lo guarda en las fichas de cliente/proveedor/potencial (ModelCatalogMail::downloadAllUsersMails()).
// Autocontenido: se invoca desde cron_actions.php (sin Config cargado) y desde ControllerSettingCron::runcron().

require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../../../admin/config.php');

if (!function_exists('user_mail_download_run')) {
	class UserMailCronUser {
		public function getId() {
			return 0;
		}
	}

	function user_mail_download_run() {
		$registry = new Registry();

		$config = new Config();
		$db     = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? DB_PORT : 3306);

		$registry->set('config', $config);
		$registry->set('db', $db);
		$registry->set('load', new Loader($registry));
		$registry->set('user', new UserMailCronUser());

		foreach ($db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'")->rows as $setting) {
			$config->set($setting['key'], $setting['serialized'] ? unserialize($setting['value']) : $setting['value']);
		}

		$registry->get('load')->model('catalog/mail');

		$log = new Log('mail.log');

		foreach ($registry->get('model_catalog_mail')->downloadAllUsersMails(100) as $user_id => $result) {
			if ($result['error'] !== '') {
				$log->write('Cron correo (usuario ' . $user_id . '): ' . $result['error']);
			}
		}
	}
}

user_mail_download_run();
