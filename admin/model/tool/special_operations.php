<?php
class ModelToolSpecialOperations extends Model {
	// Tablas que nunca se tocan: configuración, acceso al panel y todo lo que
	// cuelga de Settings > Localisation (país/región/zona geográfica, moneda,
	// idioma, impuestos, clases de peso/longitud, formas de pago y envío).
	private function getPreservedTables() {
		return array(
			'setting', 'user', 'user_group', 'api',
			'language', 'currency',
			'country', 'zone', 'geo_zone', 'zone_to_geo_zone',
			'return_action', 'return_reason',
			'tax_class', 'tax_rate', 'tax_rule', 'tax_rate_to_customer_group',
			'length_class', 'length_class_description',
			'weight_class', 'weight_class_description',
			'payment', 'payment_description',
			'shipping_methods'
		);
	}

	public function getTables() {
		$preserved = $this->getPreservedTables();

		$query = $this->db->query("SELECT TABLE_NAME AS name FROM information_schema.tables WHERE table_schema = DATABASE() ORDER BY TABLE_NAME ASC");

		$tables = array();

		foreach ($query->rows as $row) {
			$name = $row['name'];

			if (in_array($name, $preserved)) {
				continue;
			}

			// Cualquier tabla *_status (invoice_status, order_status, etc.) conserva su contenido.
			if (substr($name, -7) === '_status') {
				continue;
			}

			$tables[] = $name;
		}

		return $tables;
	}

	public function wipeData() {
		$tables = $this->getTables();

		$this->db->query("SET FOREIGN_KEY_CHECKS = 0");

		foreach ($tables as $table) {
			$this->db->query("TRUNCATE TABLE `" . $table . "`");
		}

		$this->db->query("SET FOREIGN_KEY_CHECKS = 1");

		return count($tables);
	}
}
