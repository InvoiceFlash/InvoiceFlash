<?php
/**
 * Envio de facturas Facturae a FACe (Punto General de Entrada de Facturas Electronicas)
 * sobre system/library/face.php.
 *
 * El XML Facturae firmado lo genera el propio core (ControllerSaleInvoice::facturae()); este
 * modelo lo recibe ya firmado, lo manda tal cual y guarda en `face_invoice` el numero de
 * registro y el estado de tramitacion/anulacion que devuelve FACe. Una factura registrada en
 * FACe no se vuelve a enviar: para corregirla hay que pedir su anulacion y emitir otra.
 */
class ModelFaceFace extends Model {
	const STATUS_REGISTERED = 'registered';
	const STATUS_ERROR      = 'error';

	// Estados de tramitacion de FACe (consultarEstados) a los que da igual lo que venga despues.
	const TRAMIT_REJECTED = '2600';
	const TRAMIT_PAID     = '2500';
	const TRAMIT_CANCELED = '3100';

	private static $tables_checked = false;
	private $texts = null;

	public function install() {
		if (self::$tables_checked) {
			return;
		}

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "face_invoice` (
			`face_invoice_id` INT(11) NOT NULL AUTO_INCREMENT,
			`invoice_id` INT(11) NOT NULL,
			`production` TINYINT(1) NOT NULL DEFAULT '0',
			`status` VARCHAR(16) NOT NULL DEFAULT 'error',
			`registry_number` VARCHAR(64) NOT NULL DEFAULT '',
			`tracking_code` VARCHAR(64) NOT NULL DEFAULT '',
			`management_body` VARCHAR(32) NOT NULL DEFAULT '',
			`processing_unit` VARCHAR(32) NOT NULL DEFAULT '',
			`accounting_office` VARCHAR(32) NOT NULL DEFAULT '',
			`tramit_code` VARCHAR(8) NOT NULL DEFAULT '',
			`tramit_text` VARCHAR(255) NOT NULL DEFAULT '',
			`tramit_reason` VARCHAR(500) NOT NULL DEFAULT '',
			`cancel_code` VARCHAR(8) NOT NULL DEFAULT '',
			`cancel_text` VARCHAR(255) NOT NULL DEFAULT '',
			`cancel_reason` VARCHAR(500) NOT NULL DEFAULT '',
			`message` TEXT NOT NULL,
			`signed_xml` MEDIUMTEXT NOT NULL,
			`attempts` INT(11) NOT NULL DEFAULT '0',
			`date_sent` DATETIME NULL DEFAULT NULL,
			`date_checked` DATETIME NULL DEFAULT NULL,
			PRIMARY KEY (`face_invoice_id`),
			UNIQUE KEY `invoice_id` (`invoice_id`),
			KEY `status` (`status`),
			KEY `registry_number` (`registry_number`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		self::$tables_checked = true;
	}

	public function isActive() {
		return (bool)$this->config->get('face_active');
	}

	public function isProduction() {
		return $this->config->get('face_environment') == 'production';
	}

	public function getCertificatePath() {
		return DIR_DOWNLOAD . $this->config->get('certificado');
	}

	// '' si el servidor puede firmar y hablar con FACe; si no, el motivo.
	public function requirementsError() {
		foreach (array('openssl', 'curl', 'dom') as $extension) {
			if (!extension_loaded($extension)) {
				return sprintf($this->text('error_extension'), $extension);
			}
		}

		if (!is_file(DIR_SYSTEM . 'library/face.php')) {
			return $this->text('error_library');
		}

		return '';
	}

	// Motivo por el que no se puede enviar con la configuracion actual ('' si esta lista).
	public function configurationError() {
		if (!$this->config->get('certificado') || !$this->config->get('clave') || !is_file($this->getCertificatePath())) {
			return $this->text('error_certificate');
		}

		if (!filter_var((string)$this->config->get('face_email'), FILTER_VALIDATE_EMAIL)) {
			return $this->text('error_email');
		}

		return '';
	}

	public function getRecord($invoice_id) {
		$this->install();

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "face_invoice` WHERE invoice_id = '" . (int)$invoice_id . "'");

		return $query->row;
	}

	public function getRecords($data = array()) {
		$this->install();

		$sql = "SELECT f.face_invoice_id, f.invoice_id, f.production, f.status, f.registry_number, f.management_body, f.processing_unit, f.accounting_office, f.tramit_code, f.tramit_text, f.tramit_reason, f.cancel_code, f.cancel_text, f.cancel_reason, f.message, f.attempts, f.date_sent, f.date_checked, i.invoice_prefix, i.invoice_no, i.total, i.date_added, i.payment_company, i.firstname, i.lastname FROM `" . DB_PREFIX . "face_invoice` f LEFT JOIN `" . DB_PREFIX . "invoice` i ON (i.invoice_id = f.invoice_id)" . $this->getRecordsWhere($data) . " ORDER BY f.face_invoice_id DESC";

		$start = isset($data['start']) ? max(0, (int)$data['start']) : 0;
		$limit = isset($data['limit']) ? max(1, (int)$data['limit']) : 20;

		$query = $this->db->query($sql . " LIMIT " . $start . "," . $limit);

		return $query->rows;
	}

	public function getTotalRecords($data = array()) {
		$this->install();

		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "face_invoice` f" . $this->getRecordsWhere($data));

		return (int)$query->row['total'];
	}

	private function getRecordsWhere($data) {
		$where = array();

		if (!empty($data['filter_status'])) {
			if ($data['filter_status'] == self::STATUS_ERROR) {
				$where[] = "f.status = '" . self::STATUS_ERROR . "'";
			} else {
				$where[] = "f.tramit_code = '" . $this->db->escape($data['filter_status']) . "'";
			}
		}

		return $where ? " WHERE " . implode(" AND ", $where) : '';
	}

	/**
	 * Envia a FACe el XML Facturae firmado de una factura.
	 *
	 * Devuelve array('success' => bool, 'message' => string).
	 */
	public function send($invoice_id, $signed_xml, $filename) {
		$this->install();

		$error = $this->requirementsError();

		if (!$error) {
			$error = $this->configurationError();
		}

		if ($error) {
			return array('success' => false, 'message' => $error);
		}

		$record = $this->getRecord($invoice_id);

		if ($record && $record['status'] == self::STATUS_REGISTERED) {
			return array('success' => false, 'message' => sprintf($this->text('error_already_sent'), $record['registry_number']));
		}

		$production = $this->isProduction() ? 1 : 0;

		try {
			$face = $this->client();

			$result = $face->sendInvoice((string)$this->config->get('face_email'), $signed_xml, $filename);
		} catch (FaceException $e) {
			$this->saveFailure($invoice_id, $record, $production, $signed_xml, $e->getMessage());

			return array('success' => false, 'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
		}

		$data = "invoice_id = '" . (int)$invoice_id . "', production = '" . $production . "', status = '" . self::STATUS_REGISTERED . "'"
			. ", registry_number = '" . $this->db->escape($result['numeroRegistro']) . "'"
			. ", tracking_code = '" . $this->db->escape($result['codigoSeguimiento']) . "'"
			. ", management_body = '" . $this->db->escape($this->scalar($result, 'organoGestor')) . "'"
			. ", processing_unit = '" . $this->db->escape($this->scalar($result, 'unidadTramitadora')) . "'"
			. ", accounting_office = '" . $this->db->escape($this->scalar($result, 'oficinaContable')) . "'"
			. ", tramit_code = '', tramit_text = '', tramit_reason = '', cancel_code = '', cancel_text = '', cancel_reason = ''"
			. ", message = ''"
			. ", signed_xml = '" . $this->db->escape($signed_xml) . "'"
			. ", attempts = " . ($record ? (int)$record['attempts'] : 0) . " + 1"
			. ", date_sent = NOW()";

		if ($record) {
			$this->db->query("UPDATE `" . DB_PREFIX . "face_invoice` SET " . $data . " WHERE face_invoice_id = '" . (int)$record['face_invoice_id'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "face_invoice` SET " . $data);
		}

		// Primer estado de tramitacion, para no dejar la fila "en blanco" hasta la siguiente consulta.
		$this->refresh($invoice_id);

		return array('success' => true, 'message' => sprintf($this->text('text_success_sent'), htmlspecialchars($result['numeroRegistro'], ENT_QUOTES, 'UTF-8')));
	}

	/**
	 * Consulta a FACe el estado de tramitacion y anulacion de una factura registrada.
	 */
	public function refresh($invoice_id) {
		$record = $this->getRecord($invoice_id);

		if (!$record || $record['status'] != self::STATUS_REGISTERED || $record['registry_number'] === '') {
			return array('success' => false, 'message' => $this->text('error_not_registered'));
		}

		try {
			$state = $this->client()->getInvoice($record['registry_number']);
		} catch (FaceException $e) {
			return array('success' => false, 'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
		}

		$this->db->query("UPDATE `" . DB_PREFIX . "face_invoice` SET
			tramit_code = '" . $this->db->escape($state['tramitacion']['codigo']) . "',
			tramit_text = '" . $this->db->escape($state['tramitacion']['descripcion']) . "',
			tramit_reason = '" . $this->db->escape($state['tramitacion']['motivo']) . "',
			cancel_code = '" . $this->db->escape($state['anulacion']['codigo']) . "',
			cancel_text = '" . $this->db->escape($state['anulacion']['descripcion']) . "',
			cancel_reason = '" . $this->db->escape($state['anulacion']['motivo']) . "',
			date_checked = NOW()
			WHERE face_invoice_id = '" . (int)$record['face_invoice_id'] . "'");

		return array('success' => true, 'message' => $this->text('text_success_refresh'));
	}

	/**
	 * Actualiza el estado de todas las facturas registradas que todavia pueden cambiar
	 * (ni pagadas, ni rechazadas, ni anuladas). Devuelve cuantas se han actualizado.
	 */
	public function refreshPending($limit = 50) {
		$this->install();

		$query = $this->db->query("SELECT invoice_id FROM `" . DB_PREFIX . "face_invoice` WHERE status = '" . self::STATUS_REGISTERED . "' AND registry_number != '' AND tramit_code NOT IN ('" . self::TRAMIT_REJECTED . "', '" . self::TRAMIT_PAID . "', '" . self::TRAMIT_CANCELED . "') ORDER BY date_checked ASC, face_invoice_id ASC LIMIT " . (int)$limit);

		$updated = 0;

		foreach ($query->rows as $row) {
			$result = $this->refresh($row['invoice_id']);

			if ($result['success']) {
				$updated++;
			}
		}

		return $updated;
	}

	/**
	 * Pide a FACe la anulacion de una factura registrada.
	 */
	public function cancel($invoice_id, $reason) {
		$record = $this->getRecord($invoice_id);

		if (!$record || $record['status'] != self::STATUS_REGISTERED) {
			return array('success' => false, 'message' => $this->text('error_not_registered'));
		}

		if (trim($reason) === '') {
			return array('success' => false, 'message' => $this->text('error_reason'));
		}

		try {
			$this->client()->cancelInvoice($record['registry_number'], $reason);
		} catch (FaceException $e) {
			return array('success' => false, 'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
		}

		$this->refresh($invoice_id);

		return array('success' => true, 'message' => $this->text('text_success_cancel'));
	}

	/**
	 * Prueba el certificado y la conexion pidiendo a FACe su catalogo de estados.
	 */
	public function testConnection() {
		$error = $this->requirementsError();

		if (!$error) {
			$error = $this->configurationError();
		}

		if ($error) {
			return array('success' => false, 'message' => $error);
		}

		try {
			$statuses = $this->client()->getStatuses();
		} catch (FaceException $e) {
			return array('success' => false, 'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
		}

		return array('success' => true, 'message' => sprintf($this->text('text_connection_ok'), count($statuses)));
	}

	protected function client() {
		require_once(DIR_SYSTEM . 'library/face.php');

		return new Face($this->getCertificatePath(), $this->config->get('clave'), $this->isProduction());
	}

	// El XML se guarda aunque falle el envio, para poder descargarlo y reenviarlo.
	private function saveFailure($invoice_id, $record, $production, $signed_xml, $message) {
		$data = "production = '" . (int)$production . "', status = '" . self::STATUS_ERROR . "', message = '" . $this->db->escape($message) . "', signed_xml = '" . $this->db->escape($signed_xml) . "', attempts = " . ($record ? (int)$record['attempts'] : 0) . " + 1, date_sent = NOW()";

		if ($record) {
			$this->db->query("UPDATE `" . DB_PREFIX . "face_invoice` SET " . $data . " WHERE face_invoice_id = '" . (int)$record['face_invoice_id'] . "'");
		} else {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "face_invoice` SET invoice_id = '" . (int)$invoice_id . "', " . $data);
		}
	}

	private function scalar($array, $key) {
		return isset($array[$key]) && !is_array($array[$key]) ? (string)$array[$key] : '';
	}

	// Texto del modulo para pantallas que no cargan su fichero de idioma (boton de la ficha de factura).
	public function getText($key) {
		return $this->text($key);
	}

	// Textos propios: no pisan el idioma de la pantalla que llama al modelo.
	private function text($key) {
		if ($this->texts === null) {
			$this->texts = array();

			$directory = 'en-gb';

			$query = $this->db->query("SELECT directory FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape((string)$this->config->get('config_admin_language')) . "' LIMIT 1");

			if ($query->num_rows && $query->row['directory']) {
				$directory = $query->row['directory'];
			}

			foreach (array_unique(array('en-gb', $directory)) as $dir) {
				$file = DIR_LANGUAGE . $dir . '/face/face.php';

				if (is_file($file)) {
					$_ = array();

					require($file);

					$this->texts = array_merge($this->texts, $_);
				}
			}
		}

		return isset($this->texts[$key]) ? $this->texts[$key] : $key;
	}
}
