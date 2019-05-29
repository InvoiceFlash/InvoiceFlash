<?php
require_once(str_replace('//','/',dirname(__FILE__).'/') .'../../../admin/config.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_SYSTEM . 'library/mailfetch.php');

// Registry
$registry = new Registry();

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);
$prefix = DB_PREFIX;

// Config
$config = new Config();
$registry->set('config', $config);

// Conexion al servidor de correo
$server = $config->get('config_smtp_host');
$port = $config->get('config_smtp_port');
$user = $config->get('config_smtp_username');
$pass = $config->get('config_smtp_password');

$server_type = 'pop3/notls';

if ($server!='' && $port!='' && $user!='' && $pass!='') {
	$oMail = new mailfetch($server, $user, $pass, $port, $server_type);
	$mails = $oMail->inbox();
	$n_mails = 0;

	if ($mails) {
		foreach ($mails as $m) {
			// Comprobar que el mensaje no existe ya en la base de datos
			$qry_exist = $db->query("SELECT * FROM " . $prefix . "fl_mails WHERE code LIKE '" . $m['msg_no'] . "'");

			if (!$qry_exist->rows) {
				// Si no existe lo metemos
				$sql = "INSERT INTO `" . $prefix . "fl_mails` SET 
					`client` = '" . $m['from'] . "', 
					`code` = '" . $m['msg_no'] . "', 
					`title` = '" . iconv_mime_decode($m['subject'], 0, "ISO-8859-1") . "',
					`message` = '" . $db->escape($m['body']) . "',
					`date_added` = now(),
					`type` = 'R',
					`bleido` = 0,
					`tag_id` = 0,
					`customer_id` = 0,
					`supplier_id` = 0,
					`potential_id` = 0";

				if ($db->query($sql) === TRUE) {
					$n_mails++;

					if ($mail['attachments']) {
						foreach ($mail['attachments'] as $attach) {
							
							// Sacar los datos del archivo para insertarlo en la base de datos
							$file = $attach['filename'];
							$type = filetype($file);
							$size = filesize($file);
			
							// Si contiene datos, lo metemos en la base de datos.
							if ($size>0) {
								// Cargar los datos del archivo en una variable
								$fp = fopen($file, 'r');
								if ($fp){
									$datos = fread($fp, $size);
								}
								fclose($fp);
								$datos = base64_encode($datos);
			
								$sql_attach = "INSERT INTO `".$prefix."fl_mail_files` SET 
								`mail_id` = '" . (int)$mail_id . "', 
								`type` = '" . $type . "', 
								`size` = '" . (int)$size . "', 
								`name` = '" . $file . "', 
								`created` = now(), 
								`data` = ' . $datos . '";
							}
						}
					}
				}
			}
		}

	}
}
?>