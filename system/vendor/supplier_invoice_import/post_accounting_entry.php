<?php
/**
 * Puente CLI para que el importador Python de facturas de proveedor
 * (supplier_invoice_import.py) dispare la contabilización automática de la
 * factura de compra que acaba de crear, reutilizando la misma lógica que ya
 * usa el panel (ModelAccountingAutoEntry::postPurchaseInvoice(), del módulo
 * Contabilidad) en vez de reimplementarla en Python.
 *
 * Uso: php post_accounting_entry.php --type=purchase_invoice --invoice-id=123
 *
 * Si el módulo Contabilidad no está instalado (falta
 * admin/model/accounting/auto_entry.php) o "Activar contabilidad" está en
 * No, no hace nada - sale con código 0 en silencio, sin romper el import.
 * Cualquier fallo se traga igual (exit 0) para no interrumpir el borrado del
 * email/marcado del log en el script que lo invoca; los detalles del error
 * quedan en el log de PHP de la instalación.
 */

$type = null;
$invoice_id = null;

foreach ($argv as $arg) {
	if (strpos($arg, '--type=') === 0) {
		$type = substr($arg, 7);
	} elseif (strpos($arg, '--invoice-id=') === 0) {
		$invoice_id = (int)substr($arg, 13);
	}
}

if (!$invoice_id || !in_array($type, array('purchase_invoice', 'sale_invoice'), true)) {
	exit(0);
}

// __DIR__ = <raiz>/system/vendor/supplier_invoice_import
$projectRoot = dirname(dirname(dirname(__DIR__)));
$adminDir = $projectRoot . '/admin/';
$configFile = $adminDir . 'config.php';

if (!is_file($configFile)) {
	exit(0);
}

require_once($configFile);

$autoEntryModel = DIR_APPLICATION . 'model/accounting/auto_entry.php';

if (!is_file($autoEntryModel)) {
	// Módulo Contabilidad no instalado - no hacer nada.
	exit(0);
}

try {
	require_once(DIR_SYSTEM . 'engine/registry.php');
	require_once(DIR_SYSTEM . 'engine/loader.php');
	require_once(DIR_SYSTEM . 'engine/model.php');
	require_once(DIR_SYSTEM . 'library/config.php');
	require_once(DIR_SYSTEM . 'library/db.php');

	$registry = new Registry();

	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);
	$registry->set('db', $db);

	$config = new Config();
	$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

	foreach ($query->rows as $setting) {
		$config->set($setting['key'], $setting['serialized'] ? unserialize($setting['value']) : $setting['value']);
	}

	$registry->set('config', $config);
	$loader = new Loader($registry);
	$registry->set('load', $loader);

	$loader->model('accounting/auto_entry');

	$user = array('user_id' => 0, 'username' => 'import-ia');
	$autoEntry = $registry->get('model_accounting_auto_entry');

	if ($type === 'purchase_invoice') {
		$autoEntry->postPurchaseInvoice($invoice_id, $user);
	} else {
		$autoEntry->postSaleInvoice($invoice_id, $user);
	}
} catch (\Throwable $e) {
	// Se traga el error a propósito - ver cabecera del fichero.
}

exit(0);
