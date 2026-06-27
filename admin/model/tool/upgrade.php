<?php
class ModelToolUpgrade extends Model {
	const REPO = 'InvoiceFlash/InvoiceFlash';
	const BRANCH = 'master';
	const CHECK_INTERVAL = 86400;

	public function getStatus() {
		$last_check = (int)$this->config->get('config_update_last_check');

		if (!$last_check || (time() - $last_check) > self::CHECK_INTERVAL) {
			$this->check();
		}

		return array(
			'current_commit' => $this->config->get('config_update_current_commit'),
			'latest_commit'  => $this->config->get('config_update_latest_commit'),
			'latest_message' => $this->config->get('config_update_latest_message'),
			'latest_date'    => $this->config->get('config_update_latest_date'),
			'last_check'     => $this->config->get('config_update_last_check')
		);
	}

	public function check() {
		$data = $this->apiRequest('https://api.github.com/repos/' . self::REPO . '/commits/' . self::BRANCH);

		$values = array('config_update_last_check' => time());

		if ($data && !empty($data['sha'])) {
			$values['config_update_latest_commit'] = $data['sha'];
			$values['config_update_latest_message'] = isset($data['commit']['message']) ? $data['commit']['message'] : '';
			$values['config_update_latest_date'] = isset($data['commit']['committer']['date']) ? $data['commit']['committer']['date'] : '';

			// First check ever: there is no way to know which commit the
			// files on this server actually correspond to, so the commit we
			// just saw becomes the baseline. Only commits pushed to master
			// after this point will be reported as an available update.
			if (!$this->config->get('config_update_current_commit')) {
				$values['config_update_current_commit'] = $data['sha'];
			}
		}

		$this->save($values);

		return $values;
	}

	public function getCompareUrl($status) {
		if ($status['current_commit'] && $status['latest_commit'] && ($status['current_commit'] != $status['latest_commit'])) {
			return 'https://github.com/' . self::REPO . '/compare/' . $status['current_commit'] . '...' . $status['latest_commit'];
		}

		return 'https://github.com/' . self::REPO . '/commits/' . self::BRANCH;
	}

	// Downloads the master branch as a zip, overlays it on top of the
	// current installation (skipping install/ and php.ini) and keeps a
	// zip backup of every file it replaces under system/backup/.
	// config.php is never touched: it is excluded from the repository
	// itself, so it is never present in the downloaded zip.
	public function upgrade() {
		if (!function_exists('curl_init')) {
			return array('success' => false, 'error' => 'cURL no esta disponible en este servidor.');
		}

		if (!class_exists('ZipArchive')) {
			return array('success' => false, 'error' => 'La extension ZipArchive de PHP no esta disponible en este servidor.');
		}

		$status = $this->getStatus();

		if (!$status['latest_commit']) {
			return array('success' => false, 'error' => 'No se ha podido comprobar la ultima version disponible.');
		}

		if (!is_dir(DIR_CACHE)) {
			mkdir(DIR_CACHE, 0777, true);
		}

		$tmp_zip = DIR_CACHE . 'update_' . uniqid() . '.zip';
		$tmp_dir = DIR_CACHE . 'update_' . uniqid() . '/';

		if (!$this->download('https://github.com/' . self::REPO . '/archive/refs/heads/' . self::BRANCH . '.zip', $tmp_zip)) {
			return array('success' => false, 'error' => 'No se ha podido descargar la actualizacion desde GitHub.');
		}

		$zip = new ZipArchive();

		if ($zip->open($tmp_zip) !== true) {
			unlink($tmp_zip);

			return array('success' => false, 'error' => 'El archivo descargado no es un zip valido.');
		}

		mkdir($tmp_dir, 0777, true);

		$zip->extractTo($tmp_dir);
		$zip->close();

		unlink($tmp_zip);

		$extracted_root = $this->findExtractedRoot($tmp_dir);

		if (!$extracted_root) {
			$this->removeDirectory($tmp_dir);

			return array('success' => false, 'error' => 'No se ha podido localizar el contenido descargado.');
		}

		$root = realpath(DIR_SYSTEM . '..') . '/';

		if (!is_dir(DIR_SYSTEM . 'backup')) {
			mkdir(DIR_SYSTEM . 'backup', 0777, true);
		}

		$backup_file = DIR_SYSTEM . 'backup/backup_' . date('Y-m-d_H-i-s') . '.zip';

		$backup_zip = new ZipArchive();
		$backup_zip->open($backup_file, ZipArchive::CREATE);

		$this->copyDirectory($extracted_root, $root, $backup_zip, '');

		$backup_zip->close();

		$this->removeDirectory($tmp_dir);

		$this->save(array(
			'config_update_current_commit' => $status['latest_commit'],
			'config_update_last_upgrade'   => time()
		));

		return array('success' => true, 'backup' => $backup_file);
	}

	private function findExtractedRoot($dir) {
		$items = array_diff(scandir($dir), array('.', '..'));

		foreach ($items as $item) {
			if (is_dir($dir . $item)) {
				return $dir . $item . '/';
			}
		}

		return false;
	}

	private function copyDirectory($source, $destination, $backup_zip, $relative) {
		$skip = array('install', 'php.ini');

		$items = array_diff(scandir($source), array('.', '..'));

		foreach ($items as $item) {
			$rel_path = $relative ? $relative . '/' . $item : $item;

			if (in_array($rel_path, $skip)) {
				continue;
			}

			$source_path = $source . $item;
			$destination_path = $destination . $rel_path;

			if (is_dir($source_path)) {
				if (!is_dir($destination_path)) {
					mkdir($destination_path, 0777, true);
				}

				$this->copyDirectory($source_path . '/', $destination, $backup_zip, $rel_path);
			} else {
				if (file_exists($destination_path)) {
					$backup_zip->addFile($destination_path, $rel_path);
				}

				copy($source_path, $destination_path);
			}
		}
	}

	private function removeDirectory($dir) {
		if (!is_dir($dir)) {
			return;
		}

		$items = array_diff(scandir($dir), array('.', '..'));

		foreach ($items as $item) {
			$path = $dir . $item;

			if (is_dir($path)) {
				$this->removeDirectory($path . '/');
			} else {
				unlink($path);
			}
		}

		rmdir($dir);
	}

	private function apiRequest($url) {
		$response = $this->httpRequest($url, array('User-Agent: InvoiceFlash-Update-Check', 'Accept: application/vnd.github+json'));

		if (!$response) {
			return false;
		}

		$data = json_decode($response, true);

		return is_array($data) ? $data : false;
	}

	private function download($url, $destination) {
		$fp = fopen($destination, 'w');

		if (!$fp) {
			return false;
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_USERAGENT, 'InvoiceFlash-Update');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$failed = curl_errno($ch) || ($http_code >= 400);

		curl_close($ch);
		fclose($fp);

		if ($failed) {
			if (file_exists($destination)) {
				unlink($destination);
			}

			return false;
		}

		return true;
	}

	private function httpRequest($url, $headers = array()) {
		if (!function_exists('curl_init')) {
			return false;
		}

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
		curl_setopt($ch, CURLOPT_TIMEOUT, 8);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

		if ($headers) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);
		$failed = curl_errno($ch) || !$response;

		curl_close($ch);

		return $failed ? false : $response;
	}

	private function save($data) {
		foreach ($data as $key => $value) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `key` = '" . $this->db->escape($key) . "'");
			$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = 'upgrade', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");

			$this->config->set($key, $value);
		}
	}
}
?>
