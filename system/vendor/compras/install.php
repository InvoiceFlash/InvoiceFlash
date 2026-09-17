<?php
/**
 * Instalador automático del módulo Compras.
 *
 * Se invoca (require_once + llamada a compras_auto_install()) desde
 * admin/controller/common/header.php, parcheado por vqmod/xml/compras.xml,
 * al principio del método index() - es decir, en cada carga de página del
 * panel admin ya logueado.
 *
 * No hace falta ningún install.php/*.sql manual: la primera vez que alguien
 * entra al admin tras desplegar este módulo (copiar los ficheros admin/ +
 * este system/vendor/compras/ + vqmod/xml/compras.xml), esta función:
 *
 *   1. Crea las 19 tablas de tables.sql (mismo fichero de este directorio)
 *      SOLO si no existen ya (CREATE TABLE IF NOT EXISTS, nunca DROP TABLE
 *      - un DROP en un instalador automático que corre en cada request
 *      sería demasiado peligroso), con los catálogos de purchase_order_status
 *      y reception_status precargados (INSERT IGNORE, no falla si ya están).
 *   2. Concede acceso + modificación a las rutas purchase/* y a los informes
 *      report/purchase_invoice / report/purchases_orders al grupo
 *      "Top Administrator" (user_group_id = 1).
 *   3. Escribe un fichero flag en system/cache/ para que, a partir de ahí,
 *      cada carga de página del admin cueste solo un file_exists() (nada de
 *      SHOW TABLES/consultas de más en el camino caliente).
 *
 * Usa su propia conexión mysqli (no $this->db del framework) a propósito:
 * DBMySQLi::query() hace exit() en el primer error, y no queremos poder
 * tirar el panel admin entero si algo falla aquí - preferimos abortar en
 * silencio (el flag no se escribe, así que se reintenta en la siguiente
 * carga de página) a dejar al usuario con una pantalla en blanco.
 */

function compras_auto_install() {
	$flag = DIR_SYSTEM . 'cache/compras_installed.flag';

	if (file_exists($flag)) {
		return;
	}

	if (!defined('DB_HOSTNAME') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_DATABASE')) {
		return;
	}

	$sqlFile = __DIR__ . '/tables.sql';

	if (!is_file($sqlFile)) {
		return;
	}

	mysqli_report(MYSQLI_REPORT_OFF);

	$port = defined('DB_PORT') ? (int)DB_PORT : 3306;
	$mysqli = @new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, $port);

	if (!$mysqli || $mysqli->connect_errno) {
		return;
	}

	$mysqli->set_charset('utf8');

	$prefix = defined('DB_PREFIX') ? DB_PREFIX : '';

	$tables = array(
		'supplier', 'supplier_contacts', 'supplier_document', 'supplier_history',
		'purchase_order', 'purchase_order_history', 'purchase_order_product', 'purchase_order_status', 'purchase_order_total',
		'reception', 'reception_history', 'reception_product', 'reception_status', 'reception_total',
		'purchase_invoice', 'purchase_invoice_history', 'purchase_invoice_option', 'purchase_invoice_product', 'purchase_invoice_total',
	);

	$sql = file_get_contents($sqlFile);

	// Nunca DROP TABLE en el auto-instalador, y las CREATE/INSERT se
	// vuelven idempotentes (IF NOT EXISTS / IGNORE) para poder ejecutarse
	// sin comprobar antes si la tabla ya existía.
	$sql = preg_replace('/DROP TABLE IF EXISTS `[a-z_]+`;\s*/i', '', $sql);
	$sql = preg_replace('/CREATE TABLE `/i', 'CREATE TABLE IF NOT EXISTS `', $sql);
	$sql = preg_replace('/INSERT INTO `/i', 'INSERT IGNORE INTO `', $sql);

	if ($prefix !== '') {
		$sql = preg_replace('/`(' . implode('|', $tables) . ')`/', '`' . $prefix . '$1`', $sql);
	}

	foreach (preg_split('/;\s*[\r\n]+/', $sql) as $statement) {
		$statement = trim($statement);

		if ($statement === '') {
			continue;
		}

		if (!$mysqli->query($statement)) {
			// Aborta sin escribir el flag - se reintentará en la próxima
			// carga de página. No tira el panel: la petición actual sigue
			// su curso normal en el resto de header.php.
			$mysqli->close();
			return;
		}
	}

	// Permisos del grupo "Top Administrator" (user_group_id = 1)
	$routes = array(
		'purchase/supplier', 'purchase/purchase_order', 'purchase/reception', 'purchase/invoice', 'purchase/suppliers_status',
		'report/purchase_invoice', 'report/purchases_orders',
	);

	$result = $mysqli->query("SELECT permission FROM `{$prefix}user_group` WHERE user_group_id = 1");

	if ($result && ($row = $result->fetch_assoc())) {
		$perm = unserialize($row['permission']);

		if (is_array($perm) && isset($perm['access']) && isset($perm['modify'])) {
			$changed = false;

			foreach ($routes as $route) {
				if (!in_array($route, $perm['access'])) {
					$perm['access'][] = $route;
					$changed = true;
				}

				if (!in_array($route, $perm['modify'])) {
					$perm['modify'][] = $route;
					$changed = true;
				}
			}

			if ($changed) {
				$serialized = $mysqli->real_escape_string(serialize($perm));
				$mysqli->query("UPDATE `{$prefix}user_group` SET permission = '{$serialized}' WHERE user_group_id = 1");
			}
		}
	}

	$mysqli->close();

	@file_put_contents($flag, date('Y-m-d H:i:s'));
}
