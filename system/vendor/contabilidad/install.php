<?php
/**
 * Instalador automático del módulo Contabilidad.
 *
 * Se invoca (require_once + llamada a contabilidad_auto_install()) desde
 * admin/controller/common/header.php, parcheado por
 * vqmod/xml/contabilidad.xml, al principio del método index() - en cada
 * carga de página del panel admin ya logueado. Mismo patrón que el
 * instalador automático del módulo Compras (system/vendor/compras/install.php).
 *
 * No hace falta ningún install.php/*.sql manual: la primera vez que alguien
 * entra al admin tras desplegar este módulo (copiar los ficheros admin/ +
 * este system/vendor/contabilidad/ + vqmod/xml/contabilidad.xml), esta
 * función:
 *
 *   1. Crea `ctab6` (plan de cuentas), `ctab9` (estructura PyG) y `ctab10`
 *      (estructura Modelo 303) SOLO si no existen ya (CREATE TABLE IF NOT
 *      EXISTS, nunca DROP TABLE), con sus datos de catálogo genéricos
 *      (seed_catalogs.sql, INSERT IGNORE - no duplica ni pisa datos ya
 *      editados si la tabla ya existía).
 *   2. Crea `ctab61` (subcuentas de terceros) y `ctab8` (apuntes/asientos)
 *      SOLO si no existen ya, vacías (empty_tables.sql).
 *   3. Concede acceso + modificación a las rutas accounting/* y a los 7
 *      informes report/* de este módulo al grupo "Top Administrator"
 *      (user_group_id = 1).
 *   4. Escribe un fichero flag en system/cache/ para que, a partir de ahí,
 *      cada carga de página del admin cueste solo un file_exists().
 *
 * Usa su propia conexión mysqli (no $this->db del framework) a propósito:
 * DBMySQLi::query() hace exit() en el primer error, y preferimos abortar en
 * silencio (el flag no se escribe, se reintenta en la siguiente carga de
 * página) a poder dejar el panel admin entero en blanco.
 */

function contabilidad_auto_install() {
	$flag = DIR_SYSTEM . 'cache/contabilidad_installed.flag';

	if (file_exists($flag)) {
		return;
	}

	if (!defined('DB_HOSTNAME') || !defined('DB_USERNAME') || !defined('DB_PASSWORD') || !defined('DB_DATABASE')) {
		return;
	}

	$sqlFiles = array(
		__DIR__ . '/seed_catalogs.sql' => array('ctab6', 'ctab9', 'ctab10'),
		__DIR__ . '/empty_tables.sql'  => array('ctab61', 'ctab8'),
	);

	foreach (array_keys($sqlFiles) as $sqlFile) {
		if (!is_file($sqlFile)) {
			return;
		}
	}

	mysqli_report(MYSQLI_REPORT_OFF);

	$port = defined('DB_PORT') ? (int)DB_PORT : 3306;
	$mysqli = @new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, $port);

	if (!$mysqli || $mysqli->connect_errno) {
		return;
	}

	$prefix = defined('DB_PREFIX') ? DB_PREFIX : '';

	foreach ($sqlFiles as $sqlFile => $tables) {
		$sql = file_get_contents($sqlFile);

		// Nunca DROP TABLE en el auto-instalador, y las CREATE/INSERT se
		// vuelven idempotentes (IF NOT EXISTS / IGNORE) para poder
		// ejecutarse sin comprobar antes si la tabla ya existía.
		$sql = preg_replace('/DROP TABLE IF EXISTS `[a-z0-9_]+`;\s*/i', '', $sql);
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
				// carga de página. No tira el panel: la petición actual
				// sigue su curso normal en el resto de header.php.
				$mysqli->close();
				return;
			}
		}
	}

	// Permisos del grupo "Top Administrator" (user_group_id = 1)
	$routes = array(
		'accounting/chart', 'accounting/subaccount', 'accounting/pyg', 'accounting/entry',
		'accounting/review', 'accounting/regularization', 'accounting/mod303',
		'report/journal', 'report/ledger', 'report/trial_balance', 'report/pyg',
		'report/balance_sheet', 'report/mod111', 'report/mod303',
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
