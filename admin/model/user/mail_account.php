<?php
// Cuenta de correo de cada usuario (login + contrasena). El servidor es el mismo para todos y se configura
// una sola vez en Ajustes > Correo (SMTP y POP); aqui solo va lo propio de cada usuario.
// La contrasena se guarda CIFRADA (AES-256-CBC + HMAC) con una clave aleatoria del servidor, nunca en claro.
class ModelUserMailAccount extends Model {
	private $secret = null;

	// Crea la tabla si no existe (instalaciones anteriores a esta funcion). Una comprobacion por peticion.
	public function ensureTable() {
		static $done = false;

		if ($done) {
			return;
		}

		$done = true;

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "user_mail_account` (
			`user_id` int(11) NOT NULL,
			`mail_username` varchar(255) NOT NULL DEFAULT '',
			`mail_password` text,
			`date_modified` datetime DEFAULT NULL,
			PRIMARY KEY (`user_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");
	}

	public function isEncryptionAvailable() {
		return function_exists('openssl_encrypt') && function_exists('openssl_random_pseudo_bytes') && function_exists('hash_hmac');
	}

	// Datos para el formulario: nunca devuelve la contrasena, solo si hay una guardada.
	public function getAccount($user_id) {
		$this->ensureTable();

		$query = $this->db->query("SELECT mail_username, mail_password FROM `" . DB_PREFIX . "user_mail_account` WHERE user_id = '" . (int)$user_id . "'");

		if (!$query->num_rows) {
			return array('mail_username' => '', 'has_password' => false);
		}

		return array(
			'mail_username' => $query->row['mail_username'],
			'has_password'  => ($query->row['mail_password'] !== null) && ($query->row['mail_password'] !== '')
		);
	}

	// Login y contrasena en claro, para conectar al servidor (descargar o enviar). Falso si no hay cuenta completa.
	public function getCredentials($user_id) {
		$this->ensureTable();

		$query = $this->db->query("SELECT mail_username, mail_password FROM `" . DB_PREFIX . "user_mail_account` WHERE user_id = '" . (int)$user_id . "'");

		if (!$query->num_rows || ($query->row['mail_username'] === '') || ($query->row['mail_password'] === '') || ($query->row['mail_password'] === null)) {
			return false;
		}

		$password = $this->decrypt($query->row['mail_password']);

		if ($password === false) {
			return false;
		}

		return array('username' => $query->row['mail_username'], 'password' => $password);
	}

	// $password vacio = conservar la que hubiera. Login y contrasena vacios = quitar la cuenta.
	// Devuelve true o un texto de error.
	public function saveAccount($user_id, $username, $password) {
		$this->ensureTable();

		$username = trim((string)$username);
		$password = (string)$password;

		if (($username === '') && ($password === '')) {
			$this->deleteAccount($user_id);

			return true;
		}

		$sql = "INSERT INTO `" . DB_PREFIX . "user_mail_account` SET user_id = '" . (int)$user_id . "', mail_username = '" . $this->db->escape($username) . "', date_modified = NOW()";
		$upd = "mail_username = '" . $this->db->escape($username) . "', date_modified = NOW()";

		if ($password !== '') {
			$encrypted = $this->encrypt($password);

			if ($encrypted === false) {
				return 'encryption';
			}

			$sql .= ", mail_password = '" . $this->db->escape($encrypted) . "'";
			$upd .= ", mail_password = '" . $this->db->escape($encrypted) . "'";
		}

		$this->db->query($sql . " ON DUPLICATE KEY UPDATE " . $upd);

		return true;
	}

	public function deleteAccount($user_id) {
		$this->ensureTable();

		$this->db->query("DELETE FROM `" . DB_PREFIX . "user_mail_account` WHERE user_id = '" . (int)$user_id . "'");
	}

	// ---- cifrado ----

	// Clave aleatoria de la instalacion. Vive en la tabla setting con un grupo propio ("user_mail"): al guardar
	// Ajustes solo se reescribe el grupo "config", asi que esta clave no se pierde.
	private function getSecret() {
		if ($this->secret !== null) {
			return $this->secret;
		}

		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `group` = 'user_mail' AND `key` = 'user_mail_secret' AND store_id = '0'");

		if ($query->num_rows && ($query->row['value'] !== '')) {
			return $this->secret = $query->row['value'];
		}

		$secret = bin2hex(openssl_random_pseudo_bytes(32));

		$this->db->query("INSERT INTO `" . DB_PREFIX . "setting` SET store_id = '0', `group` = 'user_mail', `key` = 'user_mail_secret', `value` = '" . $this->db->escape($secret) . "'");

		// Si otra peticion la creo a la vez, se usa la que haya quedado guardada.
		$query = $this->db->query("SELECT `value` FROM `" . DB_PREFIX . "setting` WHERE `group` = 'user_mail' AND `key` = 'user_mail_secret' AND store_id = '0' ORDER BY setting_id ASC LIMIT 1");

		return $this->secret = $query->row['value'];
	}

	private function keys() {
		$secret = $this->getSecret();

		return array(hash('sha256', 'enc|' . $secret, true), hash('sha256', 'mac|' . $secret, true));
	}

	// Formato: "v1:" + base64(iv[16] . hmac[32] . cifrado)
	public function encrypt($plain) {
		if (!$this->isEncryptionAvailable()) {
			return false;
		}

		list($enc_key, $mac_key) = $this->keys();

		$iv     = openssl_random_pseudo_bytes(16);
		$raw    = defined('OPENSSL_RAW_DATA') ? OPENSSL_RAW_DATA : 1;
		$cipher = openssl_encrypt((string)$plain, 'aes-256-cbc', $enc_key, $raw, $iv);

		if ($cipher === false) {
			return false;
		}

		return 'v1:' . base64_encode($iv . hash_hmac('sha256', $iv . $cipher, $mac_key, true) . $cipher);
	}

	// Falso si el dato esta alterado, no es de esta instalacion o no se puede descifrar.
	public function decrypt($blob) {
		if (!$this->isEncryptionAvailable() || (strpos((string)$blob, 'v1:') !== 0)) {
			return false;
		}

		$data = base64_decode(substr($blob, 3), true);

		if (($data === false) || (strlen($data) < 49)) {
			return false;
		}

		list($enc_key, $mac_key) = $this->keys();

		$iv     = substr($data, 0, 16);
		$mac    = substr($data, 16, 32);
		$cipher = substr($data, 48);
		$check  = hash_hmac('sha256', $iv . $cipher, $mac_key, true);

		$diff = 0;

		for ($i = 0; $i < 32; $i++) {
			$diff |= ord($mac[$i]) ^ ord($check[$i]);
		}

		if ($diff !== 0) {
			return false;
		}

		$raw   = defined('OPENSSL_RAW_DATA') ? OPENSSL_RAW_DATA : 1;
		$plain = openssl_decrypt($cipher, 'aes-256-cbc', $enc_key, $raw, $iv);

		return ($plain === false) ? false : $plain;
	}
}
?>
