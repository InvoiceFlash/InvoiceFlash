<?php
// Puente de cron: lanza en segundo plano el script Python que revisa el buzón
// de facturas de proveedores (system/vendor/supplier_invoice_import/supplier_invoice_import.py).
// Autocontenido a propósito (no depende de $registry/$db/$config del script que
// hace el require_once) porque se invoca desde dos contextos distintos:
// cron_actions.php (script suelto, sin Config cargado) y
// ControllerSettingCron::runcron() (dentro de un método, con $this disponible).

require_once(str_replace('//', '/', dirname(__FILE__) . '/') . '../../../admin/config.php');

// Comprobar Python antes de tocar la BD: si no hay intérprete disponible, no
// tiene sentido ni conectar ni leer los ajustes del buzón.
$python = supplier_invoice_import_find_python();

if (!$python) {
	error_log('[supplier_invoice_import] No se encontró un intérprete de Python en el sistema.');
	return;
}

$mysqli = @new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, defined('DB_PORT') ? DB_PORT : 3306);

if ($mysqli->connect_error) {
	error_log('[supplier_invoice_import] No se pudo conectar a la BD: ' . $mysqli->connect_error);
	return;
}

$keys = array(
	'config_import_supplier_invoices',
	'config_supplier_invoice_email',
	'config_supplier_invoice_email_password',
	'config_supplier_invoice_imap_host',
	'config_supplier_invoice_imap_port',
	'config_supplier_invoice_imap_ssl',
	'config_ocr_ollama_url',
);

$settings = array();
$result = $mysqli->query("SELECT `key`, `value` FROM `" . DB_PREFIX . "setting` WHERE store_id = 0 AND `key` IN ('" . implode("','", $keys) . "')");

while ($row = $result->fetch_assoc()) {
	$settings[$row['key']] = $row['value'];
}

$mysqli->close();

if (empty($settings['config_import_supplier_invoices'])) {
	// Importación desactivada en Ajustes > IA: no hacemos nada.
	return;
}

$script_path = DIR_SYSTEM . 'vendor/supplier_invoice_import/supplier_invoice_import.py';
$status_file = DIR_SYSTEM . 'vendor/supplier_invoice_import/status.json';
$log_file    = DIR_SYSTEM . 'vendor/supplier_invoice_import/last_run.log';
$attach_dir  = DIR_DOWNLOAD . 'suppliers/invoices/';

if (!is_dir(dirname($status_file))) {
	mkdir(dirname($status_file), 0755, true);
}

if (!is_dir($attach_dir)) {
	mkdir($attach_dir, 0755, true);
}

$env = array(
	'DB_HOST'              => DB_HOSTNAME,
	'DB_USER'              => DB_USERNAME,
	'DB_PASS'              => DB_PASSWORD,
	'DB_NAME'              => DB_DATABASE,
	'DB_PORT'              => defined('DB_PORT') ? (string)DB_PORT : '3306',
	'DB_PREFIX'            => DB_PREFIX,
	'IMAP_HOST'            => isset($settings['config_supplier_invoice_imap_host']) ? $settings['config_supplier_invoice_imap_host'] : '',
	'IMAP_PORT'            => !empty($settings['config_supplier_invoice_imap_port']) ? $settings['config_supplier_invoice_imap_port'] : '993',
	'IMAP_SSL'             => (isset($settings['config_supplier_invoice_imap_ssl']) && $settings['config_supplier_invoice_imap_ssl'] === '0') ? '0' : '1',
	'IMAP_EMAIL'           => isset($settings['config_supplier_invoice_email']) ? $settings['config_supplier_invoice_email'] : '',
	'IMAP_PASSWORD'        => isset($settings['config_supplier_invoice_email_password']) ? $settings['config_supplier_invoice_email_password'] : '',
	'OLLAMA_URL'           => !empty($settings['config_ocr_ollama_url']) ? $settings['config_ocr_ollama_url'] : 'http://127.0.0.1:11434/api/chat',
	'ATTACHMENT_DIR'       => $attach_dir,
	'STATUS_FILE'          => $status_file,
);

supplier_invoice_import_spawn_python($python, $script_path, $env, $log_file);

function supplier_invoice_import_find_python() {
	$candidates = array(
		'C:\\Users\\AlcuinoGarcia\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
		'python3',
		'python',
	);

	foreach ($candidates as $candidate) {
		if (stripos(PHP_OS, 'WIN') === 0 && strpos($candidate, ':\\') === false) {
			exec('where ' . escapeshellarg($candidate) . ' 2>NUL', $out, $code);
			if ($code === 0) {
				return $candidate;
			}
			continue;
		}

		if (strpos($candidate, ':\\') !== false) {
			if (file_exists($candidate)) {
				return $candidate;
			}
			continue;
		}

		exec('command -v ' . escapeshellarg($candidate) . ' 2>/dev/null', $out, $code);
		if ($code === 0) {
			return $candidate;
		}
	}

	return null;
}

function supplier_invoice_import_spawn_python($python, $script_path, $env, $log_file) {
	if (stripos(PHP_OS, 'WIN') === 0) {
		foreach ($env as $key => $value) {
			putenv($key . '=' . $value);
		}

		$cmd = 'start /B "" ' . escapeshellarg($python) . ' ' . escapeshellarg($script_path)
			. ' > ' . escapeshellarg($log_file) . ' 2>&1';

		$handle = popen('cmd /c ' . $cmd, 'r');

		foreach ($env as $key => $value) {
			putenv($key);
		}

		if ($handle !== false) {
			pclose($handle);
		}

		return;
	}

	$env_prefix = '';
	foreach ($env as $key => $value) {
		$env_prefix .= $key . '=' . escapeshellarg($value) . ' ';
	}

	$cmd = $env_prefix . escapeshellarg($python) . ' ' . escapeshellarg($script_path)
		. ' > ' . escapeshellarg($log_file) . ' 2>&1 &';

	exec($cmd);
}
