<?php
//
// Read cron actions
//

require_once(str_replace('//','/',dirname(__FILE__).'/') .'../../../admin/config.php');

//VirtualQMOD
require_once('../vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

// Registry
$registry = new Registry();

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Select rows in queue ready for processing
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "cron`  WHERE status = 1 and date_next > now()");

foreach ($query->rows as $result) {
	require_once(DIR_SYSTEM. 'vendor/cron/'. $result['action']);
	
	$db->query("UPDATE `" . DB_PREFIX . "cron` SET `date_last` = now(),  `date_next` = now() + INTERVAL '" . (int)$result['cycle'] . "' MINUTE WHERE cron_id = '" . (int)$result[cron_id] . "'");
}
?>